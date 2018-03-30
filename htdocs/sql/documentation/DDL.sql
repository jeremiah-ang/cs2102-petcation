-- CS2102 Project

DROP TABLE IF EXISTS Users, Pets, Bids, Service, PetType;

-- Constraint based on our own type

-- UserType
-- Create UserType Table 
CREATE TABLE PetType (
    typeId          serial,
    typeDescription VARCHAR(255),
    PRIMARY KEY     (typeId)
);

-- Users
-- Create Users table with userId as primary key
CREATE TABLE Users (
	userId			VARCHAR(255), 
    userName		VARCHAR(255), 
    dateOfBirth		DATE, 
    address			VARCHAR(255),
    password        VARCHAR(255) NOT NULL, 
    credits         INTEGER DEFAULT 0,    
    status          STATUS default 'ACTIVE', 
    isAdmin         BOOLEAN default FALSE,     
    PRIMARY KEY		(userId)
);

-- Pets
-- Create Pets table with petId and userId as primary key
CREATE TABLE Pets (
	petId			INTEGER, 
    userId			VARCHAR(255), 
    petName			VARCHAR(255), 
    petType 		INTEGER,
    status          STATUS default 'ACTIVE',
    PRIMARY KEY		(petId, userId),
    FOREIGN KEY		(userId) REFERENCES Users(userId) ON DELETE CASCADE,
    FOREIGN KEY     (petType) REFERENCES PetType(typeId)
);

-- Service
-- Create Service table woith serviceId and userId as primary key
CREATE TABLE Service (
	serviceId 	    INTEGER, -- auto increment type?
	userId 		    VARCHAR(255), 
    description     TEXT,
    petType         INTEGER,
	startDate 		DATE, 
	endDate 		DATE, 
    status          STATUS default 'ACTIVE',
	PRIMARY KEY		(serviceId, userId),
    FOREIGN KEY		(userId) REFERENCES Users(userId) ON DELETE CASCADE,
    FOREIGN KEY     (petType) REFERENCES PetType(typeId),
    CHECK           (endDate > startDate)
);

-- Bids
-- Create Bids table with bidId as primary key
CREATE TABLE Bids (
	buyerId			VARCHAR(255), 
    petId			INTEGER, 
    sellerId		VARCHAR(255),
    serviceId		INTEGER,
    amount			INTEGER,
    winner          BOOLEAN DEFAULT FALSE,
    PRIMARY KEY		(buyerId, petId, sellerId, serviceId),
    FOREIGN KEY		(petId, buyerId) REFERENCES Pets(petId, userId) ON DELETE CASCADE,
    FOREIGN KEY		(sellerId, serviceId) REFERENCES Service(userId, serviceId) ON DELETE CASCADE
);
