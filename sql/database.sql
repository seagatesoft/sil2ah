CREATE DATABASE IF NOT EXISTS sil2ah;

USE sil2ah;

CREATE TABLE settings (
    name VARCHAR(255) PRIMARY KEY,
    value VARCHAR(255)
);

CREATE TABLE persons (
    uuid CHAR(36) PRIMARY KEY,
    name VARCHAR(255),
    gender ENUM('M', 'F'),
    father CHAR(36),
    mother CHAR(36),
    sibling_index INT,
    person_type ENUM('R', 'D', 'S')
);

CREATE TABLE spouses (
    person_uuid CHAR(36),
    spouse_uuid CHAR(36),
    PRIMARY KEY(person_uuid, spouse_uuid),
    FOREIGN KEY(person_uuid) REFERENCES persons(uuid),
    FOREIGN KEY(spouse_uuid) REFERENCES persons(uuid)
);

CREATE TABLE person_attributes (
    person_uuid CHAR(36) PRIMARY KEY,
    address VARCHAR(255),
    phone_number VARCHAR(16),
    bbm VARCHAR(8),
    facebook VARCHAR(255),
    occupation VARCHAR(255),
    FOREIGN KEY(person_uuid) REFERENCES persons(uuid)
);

CREATE TABLE users (
  username VARCHAR(255) PRIMARY KEY,
  password VARCHAR(255)
);
