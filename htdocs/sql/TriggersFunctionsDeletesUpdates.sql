-------------------------------------------
--------    3.2 PREVENT DELETE.      ------
-------------------------------------------

-- Trigger Function to prevent deletion of pets
CREATE OR REPLACE FUNCTION prevent_pets_deletion()
RETURNS TRIGGER AS 
$$
DECLARE 
    bidamt INTEGER;
BEGIN

    IF NOT EXISTS (SELECT 1 FROM Users WHERE userId = OLD.userId)
    THEN
        RETURN OLD;
    END IF;

    UPDATE 	Pets 
    SET 	status = 'INACTIVE'
    WHERE 	userId = OLD.userId
    AND		petId	= OLD.petId;
    raise notice 'prevented deletion of user: % and pet: %', OLD.userId, OLD.petId;

    -- Delete those bids
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

-- Trigger Function to prevent deletion of services
CREATE OR REPLACE FUNCTION prevent_service_deletion()
RETURNS TRIGGER AS 
$$
BEGIN

    IF NOT EXISTS (SELECT 1 FROM Users WHERE userId = OLD.userId)
    THEN
        RETURN OLD;
    END IF;

    UPDATE 	Service 
    SET 	status = 'INACTIVE'
    WHERE 	serviceId = OLD.serviceId
    AND		userId = OLD.userId;
    raise notice 'prevented deletion of user: % and relevant service: %', OLD.userId, OLD.serviceId;

    -- Delete those bids
    DELETE FROM 	bids
    WHERE 			sellerId = OLD.userId
    AND 			serviceId = OLD.serviceId
    AND             winner = FALSE;
    
    RETURN NULL;
END; 
$$
LANGUAGE PLPGSQL;

CREATE TRIGGER prevent_service_deletion 
BEFORE DELETE
ON Service
FOR EACH ROW
EXECUTE PROCEDURE prevent_service_deletion();



---------------------------------------------------------
--------         PREVENT SERVICE UPDATE            ------
---------------------------------------------------------
CREATE OR REPLACE FUNCTION prevent_service_update ()
RETURNS TRIGGER AS $$
BEGIN
    IF OLD.status <> NEW.status
    THEN
        RETURN NEW;
    END IF;

    raise exception 'Update of service not allowed!';
    RETURN OLD;
END;
$$ LANGUAGE PLPGSQL;

CREATE TRIGGER prevent_service_update
BEFORE UPDATE
ON Service 
FOR EACH ROW 
EXECUTE PROCEDURE prevent_service_update();






---------------------------------------------------------
--------    3.2.3 UPDATE PETS WITH(OUT) BIDS.      ------
---------------------------------------------------------
-- Trigger Function to prevent update of pets if they have bidded
CREATE OR REPLACE FUNCTION check_before_update_pets()
RETURNS TRIGGER AS 
$$
BEGIN
    IF OLD.petId = (SELECT  petId 
                    FROM    Bids 
                    WHERE   petId = OLD.petId 
                    AND     buyerId = OLD.userId 
                    AND     OLD.status = 'ACTIVE')
    AND OLD.status = NEW.status 
    THEN 
        raise exception 'Pets have bidded service, prevented deletion.';
        RETURN NULL;
    ELSE 
        raise notice 'Pets have not bidded for any service, allow update.';
    END IF;
    RETURN NEW;
END;
$$
LANGUAGE PLPGSQL;

CREATE TRIGGER check_before_update_pets 
BEFORE UPDATE
ON Pets
FOR EACH ROW
EXECUTE PROCEDURE check_before_update_pets();
