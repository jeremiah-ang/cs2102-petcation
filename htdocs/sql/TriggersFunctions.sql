-------------------------------------------
--------    AUTO INCREMENT IDs       ------
-------------------------------------------

-- Function to find next id
DROP FUNCTION IF EXISTS get_next_id (tblname NAME, col VARCHAR, uid VARCHAR);
CREATE FUNCTION get_next_id (tblname NAME, col VARCHAR, uid VARCHAR)
    RETURNS INTEGER AS $get_next_id$
    DECLARE
        next_id INTEGER;
    BEGIN
        EXECUTE format ('SELECT CASE count(%s) WHEN 0 THEN 1 ELSE max(%s) + 1 END
                            FROM %s WHERE userId = ''%s''', 
                            col, col, tblname, uid)
        INTO next_id;
        RETURN next_id;
    END
    $get_next_id$ LANGUAGE plpgsql;

-- Trigger to auto increment petId
CREATE OR REPLACE FUNCTION trigger_insert_pets()
    RETURNS TRIGGER AS $trigger_insert_pets$
    DECLARE 
        next_id INTEGER;
    BEGIN
        SELECT get_next_id (tg_table_name, 'petid'::VARCHAR, NEW.userid)
        INTO next_id;
        NEW.petid := next_id;
        RETURN NEW;
    END
    $trigger_insert_pets$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_insert_pets
    BEFORE INSERT
    ON Pets
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_insert_pets();

-- Trigger to auto increment serviceId
CREATE OR REPLACE FUNCTION trigger_insert_service()
    RETURNS TRIGGER AS $trigger_insert_service$
    DECLARE
        next_id INTEGER;
    BEGIN
        SELECT get_next_id (tg_table_name, 'serviceid'::VARCHAR, NEW.userid)
        INTO next_id;
        NEW.serviceid := next_id;
        RETURN NEW;
    END
    $trigger_insert_service$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_insert_service
    BEFORE INSERT
    ON Service
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_insert_service();














-------------------------------------------
--------    TOPUPs & TRANSACTIONS    ------
-------------------------------------------

-- Function to update user credit amount
DROP FUNCTION IF EXISTS update_credit (uid VARCHAR, amt INTEGER);
CREATE FUNCTION update_credit (uid VARCHAR, amt INTEGER)
    RETURNS INTEGER AS $update_credit$
    BEGIN
        UPDATE Users SET credits = credits + amt WHERE userid = uid;
        RETURN NULL;
    END
    $update_credit$ LANGUAGE plpgsql;


-- Function to make transaction
DROP FUNCTION IF EXISTS make_transaction(uid VARCHAR, amount INTEGER, type TRANSTYPE);
CREATE OR REPLACE FUNCTION make_transaction(uid VARCHAR, amount INTEGER, type TRANSTYPE)
    RETURNS INTEGER AS $make_transaction$
    BEGIN
        IF type = 'DEBIT' THEN
            amount = -1 * amount;
        END IF;
        PERFORM update_credit (uid, amount);
        RETURN NULL;
    END
    $make_transaction$ LANGUAGE plpgsql;

-- Trigger to increment User's credit upon topup of wallet
CREATE OR REPLACE FUNCTION topup (userid VARCHAR, amount INTEGER)
    RETURNS INTEGER AS $topup$
    BEGIN
        PERFORM make_transaction (userid, amount, 'CREDIT'::TRANSTYPE);
        RETURN NULL;
    END;
    $topup$ LANGUAGE plpgsql;














-------------------------------------------
--------        PLACE BIDS           ------
-------------------------------------------

CREATE OR REPLACE FUNCTION placebit_trigger ()
    RETURNS TRIGGER AS $placebit_trigger$
    BEGIN
        PERFORM make_transaction (NEW.buyerid::VARCHAR, NEW.amount::INTEGER, 'DEBIT'::TRANSTYPE);
        RETURN NEW;
    END;
    $placebit_trigger$ LANGUAGE plpgsql;

CREATE TRIGGER placebit_trigger
    AFTER INSERT
    ON Bids
    FOR EACH ROW
    EXECUTE PROCEDURE placebit_trigger();

CREATE OR REPLACE FUNCTION replacebid_trigger()
    RETURNS TRIGGER AS $replacebid_trigger$
    BEGIN
        -- Ensure there's enough to bid
        IF NEW.amount > (SELECT credits FROM users WHERE userid = OLD.buyerid) + OLD.amount
        THEN
            raise exception 'Not Enough Credit! New Bid: % Wallet: %', NEW.amount, (SELECT credits FROM users WHERE userid = OLD.buyerid) + OLD.amount;
            RETURN OLD;
        END IF;

        PERFORM make_transaction (NEW.buyerid::VARCHAR, (OLD.amount - NEW.amount)::INTEGER, 'CREDIT'::TRANSTYPE);
        RETURN NEW;
    END;
    $replacebid_trigger$ LANGUAGE plpgsql;

CREATE TRIGGER replacebid_trigger
    BEFORE UPDATE
    ON Bids
    FOR EACH ROW
    EXECUTE PROCEDURE replacebid_trigger();
















-------------------------------------------
--------          BID WON            ------
-------------------------------------------
DROP FUNCTION IF EXISTS refund_bids (sellerid VARCHAR, serviceid INTEGER);
CREATE OR REPLACE FUNCTION refund_bids (_sellerid VARCHAR, _serviceid INTEGER)
    RETURNS INTEGER as $refund_bids$
    DECLARE
        total_earnings INTEGER;
    BEGIN

        -- REMOVE BIDS
        DELETE FROM bids b
        WHERE 		((b.sellerid 	= _sellerid
        AND 		b.serviceid = _serviceid)
        OR          (petid IN (SELECT    petid 
                              FROM      bids b
                              WHERE     b.sellerid = _sellerid
                              AND       b.serviceid = _serviceid
                              AND       winner = TRUE)))       
        AND         winner      = FALSE;


        -- CREDIT To Service Proivder
        SELECT sum(amount) as earnings 
        INTO 	total_earnings
        FROM 	bids b
        WHERE 	b.sellerid 	= _sellerid
        AND 	b.serviceid = _serviceid
        AND 	winner 		= TRUE;

        PERFORM make_transaction (_sellerid, total_earnings, 'CREDIT');

        RETURN NULL;
    END;
    $refund_bids$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION refundbid_trigger ()
    RETURNS TRIGGER AS $$
    BEGIN
    IF OLD.winner = FALSE THEN
    	PERFORM make_transaction (OLD.buyerid::VARCHAR, OLD.amount::INTEGER, 'CREDIT');
    END IF;
    RETURN OLD;
    END;
    $$ LANGUAGE plpgsql;

CREATE TRIGGER refundbid_trigger
    BEFORE DELETE
    ON bids
    FOR EACH ROW
    EXECUTE PROCEDURE refundbid_trigger()