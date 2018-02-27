-- CS2102 Project
--
-- Users (userId, userName, dateOfBirth, address)
-- Pets (rname, area)
-- Bids (pizza)
-- Wallet (rname, pizza, price)
-- Transaction (cname, pizza)
-- Service (cname, pizza)
-- Winners (cname, pizza)

DROP TABLE IF EXISTS Users, Pets, Bids, Trans, Wallet, Service, Winners;

-- Users
-- Create Users table with userId as primary key
CREATE TABLE Users (
	userId			VARCHAR(255), 
    userName		VARCHAR(255), 
    dateOfBirth		DATE, 
    address			VARCHAR(255),
    credits         INTEGER DEFAULT 0,           
    PRIMARY KEY		(userId)
);

-- Pets
-- Create Pets table with petId and userId as primary key
CREATE TABLE Pets (
	petId			INTEGER, 
    userId			VARCHAR(255), 
    petName			VARCHAR(255), 
    sizeOfPet		CHAR(1),
    picture			VARCHAR(255),
    PRIMARY KEY		(petId, userId),
    FOREIGN KEY		(userId) REFERENCES Users(userId)
);

-- Service
-- Create Service table woith serviceId and sellerId as primary key
CREATE TABLE Service (
	serviceId 	    INTEGER, 
	userId 		    VARCHAR(255), 
	startDate 		DATE, 
	endDate 		DATE, 
	PRIMARY KEY		(serviceId, userId),
    FOREIGN KEY		(userId) REFERENCES Users(userId)
);

-- Bids
-- Create Bids table with bidId as primary key
CREATE TABLE Bids (
	bidId			BIGINT, 
    buyerId			VARCHAR(255), 
    petId			INTEGER, 
    sellerId		VARCHAR(255),
    serviceId		INTEGER,
    amount			NUMERIC,
    PRIMARY KEY		(bidId),
    FOREIGN KEY		(petId, buyerId) REFERENCES Pets(petId, userId),
    FOREIGN KEY		(sellerId, serviceId) REFERENCES Service(userId, serviceId)
);

-- Wallet
-- Create Wallet table woith topUpId and userId as primary key
CREATE TABLE Wallet (
	topUpId			INTEGER, 
    userId			VARCHAR(255), 
    amount			NUMERIC(6,2), 
    timeDate		TIMESTAMP,
    PRIMARY KEY		(topUpId, userId),
    FOREIGN KEY		(userId) REFERENCES Users(userId)
);

-- Transaction
-- Create Transaction table with transId and userId as primary key
CREATE TABLE Trans (
	transId 		INTEGER, 
	userId 			VARCHAR(255), 
	transDate 		TIMESTAMP, 
	transType 		VARCHAR(1),
	transAmt 		NUMERIC(6,2), 
	PRIMARY KEY		(transId, userId),
    FOREIGN KEY		(userId) REFERENCES Users(userId)
);

-- Winner
-- Create Winner table with transId and userId as primary key
CREATE TABLE Winners (
	buyerId 		VARCHAR(255), 
	petId 			INTEGER,
	sellerId 		VARCHAR(255), 
	serviceId 		INTEGER, 
	amount 			NUMERIC(6,2),
	PRIMARY KEY		(buyerId, petId, sellerId, serviceId),
    FOREIGN KEY		(petId, buyerId) REFERENCES Pets(petId, userId),
    FOREIGN KEY		(sellerId, serviceId) REFERENCES Service(userId, serviceId)
);


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

-- Trigger to auto increment touUpId
CREATE OR REPLACE FUNCTION trigger_insert_wallet()
    RETURNS TRIGGER AS $trigger_insert_wallet$
    DECLARE 
        next_id INTEGER;
    BEGIN
        SELECT get_next_id(tg_table_name, 'topupid'::VARCHAR, NEW.userid) 
        INTO next_id;
        NEW.topupid := next_id;
        RETURN NEW;
    END
    $trigger_insert_wallet$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_insert_wallet
    BEFORE INSERT
    ON Wallet
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_insert_wallet();

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

-- Trigger to auto increment transId
CREATE OR REPLACE FUNCTION trigger_insert_trans()
    RETURNS TRIGGER AS $trigger_insert_trans$
    DECLARE
        next_id INTEGER;
    BEGIN
        SELECT get_next_id (tg_table_name, 'transid'::VARCHAR, NEW.userid)
        INTO next_id;
        NEW.transid := next_id;
        RETURN NEW;
    END
    $trigger_insert_trans$ LANGUAGE plpgsql;

CREATE TRIGGER trigger_insert_trans
    BEFORE INSERT
    ON Trans
    FOR EACH ROW
    EXECUTE PROCEDURE trigger_insert_trans();

-- Function to update user credit amount
DROP FUNCTION IF EXISTS update_credit (uid VARCHAR, amt INTEGER);
CREATE FUNCTION update_credit (uid VARCHAR, amt INTEGER)
    RETURNS INTEGER AS $update_credit$
    BEGIN
        UPDATE Users SET credits = credits + amt WHERE userid = uid;
        RETURN NULL;
    END
    $update_credit$ LANGUAGE plpgsql;

-- Function to insert new entry into Transaction
DROP FUNCTION IF EXISTS insert_trans (uid VARCHAR, amt INTEGER, timedate TIMESTAMP, type VARCHAR);
CREATE FUNCTION insert_trans (uid VARCHAR, amt INTEGER, timedate TIMESTAMP, type VARCHAR)
    RETURNS INTEGER AS $insert_trans$
    BEGIN
        INSERT INTO Trans VALUES (
            DEFAULT,
            uid,
            timedate,
            type,
            amt
        );  
        RETURN NULL;
    END
    $insert_trans$ LANGUAGE plpgsql;

-- Function to make transaction
DROP FUNCTION IF EXISTS make_transaction(uid VARCHAR, amount INTEGER, timedate TIMESTAMP, type VARCHAR);
CREATE OR REPLACE FUNCTION make_transaction(uid VARCHAR, amount INTEGER, timedate TIMESTAMP, type VARCHAR)
    RETURNS INTEGER AS $make_transaction$
    BEGIN
        IF type = '0' THEN
            amount = -1 * amount;
        END IF;
        PERFORM update_credit (uid, amount);
        PERFORM insert_trans (uid, amount, timedate, type);
        RETURN NULL;
    END
    $make_transaction$ LANGUAGE plpgsql;

-- Trigger to increment User's credit upon topup of wallet
CREATE OR REPLACE FUNCTION topup_trigger ()
    RETURNS TRIGGER AS $topup_trigger$
    BEGIN
        PERFORM make_transaction (NEW.userid::VARCHAR, NEW.amount::INTEGER, NEW.timedate::TIMESTAMP, '1');
        RETURN NEW;
    END;
    $topup_trigger$ LANGUAGE plpgsql;

CREATE OR REPLACE FUNCTION placebit_trigger ()
    RETURNS TRIGGER AS $placebit_trigger$
    BEGIN
        PERFORM make_transaction (NEW.buyerid::VARCHAR, NEW.amount::INTEGER, CURRENT_TIMESTAMP::TIMESTAMP, '0');
        RETURN NEW;
    END;
    $placebit_trigger$ LANGUAGE plpgsql;

CREATE TRIGGER topup_trigger
    BEFORE INSERT 
    ON Wallet
    FOR EACH ROW
    EXECUTE PROCEDURE topup_trigger();

CREATE TRIGGER placebit_trigger
    BEFORE INSERT
    ON Bids
    FOR EACH ROW
    EXECUTE PROCEDURE placebit_trigger();

-- Triggers for when a bid has won
CREATE OR REPLACE FUNCTION bid_won ()
    RETURNS TRIGGER AS $bid_won$
    DECLARE 
        refunder record;
        totalbid INTEGER;
    BEGIN
        FOR refunder IN
            SELECT buyerid, sum(amount) as amt
                FROM Bids 
                WHERE sellerId = NEW.sellerId 
                    and serviceId = NEW.serviceId
                    and (buyerId <> NEW.buyerid
                    or petId <> NEW.petid)
                GROUP BY buyerId
        LOOP
            PERFORM make_transaction (refunder.buyerid::VARCHAR, 
                refunder.amt::INTEGER, CURRENT_TIMESTAMP::TIMESTAMP, '1');
        END LOOP;

        SELECT sum(amount) as amt
        INTO totalbid
        FROM Bids
        WHERE sellerId = NEW.sellerId
        and serviceId = NEW.serviceId
        and buyerId = NEW.buyerId
        and petId = NEW.petId
        GROUP BY (sellerId, serviceId, buyerId, petId);

        NEW.amount := totalbid;
        RETURN NEW;
    END;
    $bid_won$ LANGUAGE plpgsql;

CREATE TRIGGER bid_won
    BEFORE INSERT
    ON Winners
    FOR EACH ROW
    EXECUTE PROCEDURE bid_won();

-- Sample User Creation Test Case
-- Insert Date format: YYYY-MM-DD
-- Input Format: userId, userName, dateOfBirth, address
INSERT INTO Users
VALUES		('IAMAWESOME', 'Amos Chua', '1991-01-01', 'BLK 123 Clementi Ave 2 #12-123 S123456');

INSERT INTO Users
VALUES		('BENISGREAT', 'Ben Lim', '1992-01-01', 'BLK 124 Clementi Ave 2 #12-123 S123457');

INSERT INTO Users
VALUES		('XIAOCATS', 'Cathy Seow', '1993-01-01', 'BLK 125 Clementi Ave 2 #12-123 S123458');

INSERT INTO Users
VALUES		('DAVIDDY', 'David Lam', '1994-01-01', 'BLK 126 Clementi Ave 2 #12-123 S123459');

INSERT INTO Users
VALUES      ('u1', 'fn1', '1994-01-01', 'BLK 126 Clementi Ave 2 #12-123 S123459');

-- Sample Pets Creation Test Case
INSERT INTO Pets
VALUES		(DEFAULT, 'IAMAWESOME', 'Woffie', 'L', 'C://Users/Amos/Desktop/Woffie.jpg');

INSERT INTO Pets
VALUES		(DEFAULT, 'IAMAWESOME', 'Cuddle', 'S', 'C://Users/Amos/Desktop/Cuddle.jpg');

INSERT INTO Pets
VALUES		(DEFAULT, 'XIAOCATS', 'MiaoMiao', 'L', 'C://Users/Cathy/Desktop/MiaoMiao.jpg');

INSERT INTO Pets
VALUES		(DEFAULT, 'DAVIDDY', 'Coco', 'M', 'C://Users/David/Desktop/Coco.jpg');

INSERT INTO Pets
VALUES		(DEFAULT, 'BENISGREAT', 'Hotdog', 'S', 'C://Users/Ben/Desktop/Hotdog.jpg');

-- Sample Service Creation Test Case
-- Insert Date format: YYYY-MM-DD
INSERT INTO Service
VALUES		(DEFAULT, 'IAMAWESOME', '2018-02-28', '2018-03-28');

INSERT INTO Service
VALUES		(DEFAULT, 'XIAOCATS', '2018-03-01', '2018-03-14');

-- Sample Wallet Creation Test Case
-- TimeStamp format: YYYY-MM-DD HH:MM:SS
INSERT INTO Wallet
VALUES		(DEFAULT, 'BENISGREAT', 75.46, '2018-01-21 15:52:40');

INSERT INTO Wallet
VALUES		(DEFAULT, 'IAMAWESOME', 30.82, '2018-02-03 15:57:46');

INSERT INTO Wallet
VALUES		(DEFAULT, 'IAMAWESOME', 78.23, '2018-02-03 15:57:46');

INSERT INTO Wallet
VALUES		(DEFAULT, 'XIAOCATS', 89.20, '2018-01-18 13:35:16');

INSERT INTO Wallet
VALUES		(DEFAULT, 'DAVIDDY', 64.50, '2018-02-26 14:24:27');

-- Sample Bids Creation Test Case
INSERT INTO Bids VALUES
(DEFAULT, 'BENISGREAT', 1, 'IAMAWESOME', 1, 1),
(DEFAULT, 'XIAOCATS', 1, 'IAMAWESOME', 1, 3),
(DEFAULT, 'DAVIDDY', 1, 'IAMAWESOME', 1, 4),
(DEFAULT, 'BENISGREAT', 1, 'IAMAWESOME', 1, 5),
(DEFAULT, 'BENISGREAT', 1, 'XIAOCATS', 1, 2);

-- Sample Transaction Creation Test Case
-- Assume userName='BENISGREAT' is selected as winning bid
INSERT INTO Trans VALUES
(DEFAULT, 'BENISGREAT', '2018-01-21', '0', 7546),
(DEFAULT, 'BENISGREAT', '2018-02-01', '1', 1),
(DEFAULT, 'BENISGREAT', '2018-02-02', '1', 5),
(DEFAULT, 'BENISGREAT', '2018-02-02', '1', 2);

-- Sample Winners Creation Test Case
-- Assume userName='BENISGREAT' is selected as winning bid
INSERT INTO Winners VALUES
('BENISGREAT', 1, 'IAMAWESOME', 1, DEFAULT);