-- =====================================================================
-- RentX (RentX_Fixed) — Database Setup Script
-- =====================================================================
-- Run this once in phpMyAdmin (or `mysql -u root -p < setup.sql`) to
-- create a fresh database with every table the application code uses.
--
-- The `vehicles` table below is an EXACT match of the schema confirmed
-- from this project's live database (via DESCRIBE vehicles).
--
-- The `users`, `bookings`, `payments`, `admin`, and `contact_messages`
-- tables are built from every column name referenced across the PHP
-- code (register.php, login.php, profile.php, booking.php,
-- payment.php, invoice.php, admin/manage_bookings.php, etc.), which
-- were internally consistent everywhere they're used. If your original
-- install already has these tables with different column names, run
-- DESCRIBE on them and let me know so I can double-check.
-- =====================================================================

-- CREATE DATABASE IF NOT EXISTS rentx_fixed
--     CHARACTER SET utf8mb4
--     COLLATE utf8mb4_general_ci;
-- 
-- USE rentx_fixed;

-- ---------------------------------------------------------------------
-- vehicles
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS vehicles (
    id              INT(11)         NOT NULL AUTO_INCREMENT,
    vehicle_name    VARCHAR(100)    NOT NULL,
    vehicle_type    ENUM('Car','Bike') NOT NULL,
    brand           VARCHAR(100)    NOT NULL,
    fuel_type       VARCHAR(50)     NOT NULL,
    transmission    VARCHAR(50)     NOT NULL,
    city            VARCHAR(100)    NOT NULL,
    pickup_address  TEXT            NOT NULL,
    service_radius  INT(11)         DEFAULT 30,
    price_per_km    DECIMAL(10,2)   NOT NULL,
    vehicle_image   VARCHAR(255)    NOT NULL,
    description     TEXT            NULL,
    status          ENUM('Available','Booked') DEFAULT 'Available',
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- users
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id              INT(11)         NOT NULL AUTO_INCREMENT,
    fullname        VARCHAR(100)    NOT NULL,
    email           VARCHAR(150)    NOT NULL UNIQUE,
    mobile          VARCHAR(20)     NOT NULL,
    dob             DATE            NOT NULL,
    gender          VARCHAR(10)     NOT NULL,
    address         TEXT            NOT NULL,
    licence_number  VARCHAR(50)     NOT NULL,
    licence_image   VARCHAR(255)    NOT NULL,
    password        VARCHAR(255)    NOT NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- bookings
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS bookings (
    id              INT(11)         NOT NULL AUTO_INCREMENT,
    user_id         INT(11)         NOT NULL,
    vehicle_id      INT(11)         NOT NULL,
    pickup_date     DATE            NOT NULL,
    return_date     DATE            NOT NULL,
    estimated_km    INT(11)         NOT NULL,
    total_amount    DECIMAL(10,2)   NOT NULL,
    booking_status  ENUM('Pending','Approved','Rejected') DEFAULT 'Pending',
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY user_id (user_id),
    KEY vehicle_id (vehicle_id),
    CONSTRAINT fk_bookings_user    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE,
    CONSTRAINT fk_bookings_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- payments
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS payments (
    id              INT(11)         NOT NULL AUTO_INCREMENT,
    booking_id      INT(11)         NOT NULL,
    user_id         INT(11)         NOT NULL,
    amount          DECIMAL(10,2)   NOT NULL,
    payment_method  VARCHAR(30)     NOT NULL,
    payment_status  VARCHAR(20)     DEFAULT 'Paid',
    payment_date    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY booking_id (booking_id),
    KEY user_id (user_id),
    CONSTRAINT fk_payments_booking FOREIGN KEY (booking_id) REFERENCES bookings(id) ON DELETE CASCADE,
    CONSTRAINT fk_payments_user    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- contact_messages
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
    id          INT(11)      NOT NULL AUTO_INCREMENT,
    fullname    VARCHAR(100) NOT NULL,
    email       VARCHAR(150) NOT NULL,
    subject     VARCHAR(150) NOT NULL,
    message     TEXT         NOT NULL,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- admin
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    id          INT(11)      NOT NULL AUTO_INCREMENT,
    username    VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- Default admin login: username "admin", password "admin123"
-- (This hash was generated with PHP's password_hash(), PASSWORD_DEFAULT.)
-- CHANGE THIS PASSWORD after your first login.
INSERT INTO admin (username, password) VALUES
('admin', '$2y$10$0dpxLBa9k8jMumtDs6lr0Ospe8PSKlmJ/8bbTOeri438Ke4yplN1u')
ON DUPLICATE KEY UPDATE username = username;

-- ---------------------------------------------------------------------
-- reviews
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS reviews (
    id              INT(11)         NOT NULL AUTO_INCREMENT,
    booking_id      INT(11)         DEFAULT NULL,
    vehicle_id      INT(11)         NOT NULL,
    user_id         INT(11)         NOT NULL,
    rating          INT(1)          NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment         TEXT            NOT NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY vehicle_id (vehicle_id),
    KEY user_id (user_id),
    CONSTRAINT fk_reviews_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE,
    CONSTRAINT fk_reviews_user    FOREIGN KEY (user_id)    REFERENCES users(id)    ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- vehicle_images
-- ---------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS vehicle_images (
    id              INT(11)         NOT NULL AUTO_INCREMENT,
    vehicle_id      INT(11)         NOT NULL,
    image_path      VARCHAR(255)    NOT NULL,
    created_at      TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY vehicle_id (vehicle_id),
    CONSTRAINT fk_images_vehicle FOREIGN KEY (vehicle_id) REFERENCES vehicles(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- Schema migrations for existing tables
-- ---------------------------------------------------------------------
SET @dbname = DATABASE();
SET @tablename = "vehicles";
SET @columnname = "maintenance_status";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE vehicles ADD COLUMN maintenance_status ENUM('Available','In Maintenance','Out of Service') DEFAULT 'Available';"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

SET @tablename = "bookings";
SET @columnname = "add_ons";
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (table_name = @tablename)
      AND (table_schema = @dbname)
      AND (column_name = @columnname)
  ) > 0,
  "SELECT 1",
  "ALTER TABLE bookings ADD COLUMN add_ons TEXT NULL, ADD COLUMN insurance_plan VARCHAR(50) DEFAULT 'Basic', ADD COLUMN extra_amount DECIMAL(10,2) DEFAULT 0.00, ADD COLUMN rejection_reason TEXT NULL;"
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

