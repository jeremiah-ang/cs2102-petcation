-------------------------------------------
--------    PREVENT PET DELETE.      ------
-------------------------------------------

-- Sample of a trigger that determines if an entry is ok to be deleted

-- Trigger Function to prevent deletion of pets
CREATE OR REPLACE FUNCTION prevent_pets_deletion()
RETURNS TRIGGER AS 
$$
DECLARE 
    bidamt INTEGER;
BEGIN
    
    -- If user is already deleted
    -- remove pet entry
    IF NOT EXISTS (SELECT 1 FROM Users WHERE userId = OLD.userId)
    THEN
        RETURN OLD;
    END IF;

    -- Prevent actual deletion, simply set status to 'INACTIVE'
    UPDATE 	Pets 
    SET 	status = 'INACTIVE'
    WHERE 	userId = OLD.userId
    AND		petId	= OLD.petId;
    raise notice 'prevented deletion of user: % and pet: %', OLD.userId, OLD.petId;

    -- Delete those bids belonging to this pet
    DELETE FROM 	bids
    WHERE 			buyerId = OLD.userId
    AND 			petId = OLD.petId;

    RETURN NULL;
END; 
$$
LANGUAGE PLPGSQL;

CREATE TRIGGER prevent_pets_deletion 
BEFORE DELETE
ON Pets 
FOR EACH ROW
EXECUTE PROCEDURE prevent_pets_deletion();







-------------------------------------------
--------    TOPUPs & TRANSACTIONS    ------
-------------------------------------------

-- Sample of common procedures being abstracted into functions

-- Function to update user credit amount
CREATE FUNCTION update_credit (uid VARCHAR, amt INTEGER)
    RETURNS INTEGER AS $update_credit$
    BEGIN
        UPDATE Users SET credits = credits + amt WHERE userid = uid;
        RETURN NULL;
    END
    $update_credit$ LANGUAGE plpgsql;


-- Function to make transaction
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







-------------------------------------------
--------    Bid Winning/Refunding    ------
-------------------------------------------

-- Sample of a trigger that performs intemediate steps before updating an entry

-- This function is called after choosing winning bids 
-- The non-winning bids are deleted
-- The user selling this service gets credited the winnings
CREATE OR REPLACE FUNCTION refund_bids (_sellerid VARCHAR, _serviceid INTEGER)
    RETURNS INTEGER as $refund_bids$
    DECLARE
        total_earnings INTEGER;
    BEGIN

        -- REMOVE NON-WINNING BIDS
        DELETE FROM bids b
        WHERE       b.sellerid  = _sellerid
        AND         b.serviceid = _serviceid
        AND         winner      = FALSE;

        -- CREDIT To Service Proivder
        SELECT sum(amount) as earnings 
        INTO    total_earnings
        FROM    bids b
        WHERE   b.sellerid  = _sellerid
        AND     b.serviceid = _serviceid
        AND     winner      = TRUE;

        PERFORM make_transaction (_sellerid, total_earnings, 'CREDIT');

        RETURN NULL;
    END;
    $refund_bids$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION refundbid_trigger ()
    RETURNS TRIGGER AS $$
    BEGIN
    -- Refunds only if deleted bid is not a winner
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





