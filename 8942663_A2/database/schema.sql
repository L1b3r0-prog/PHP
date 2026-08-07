-- ============================================================
-- MyRecordingStudio Database Schema
-- ISIT307 Assignment #2
-- ============================================================

DROP DATABASE IF EXISTS myrecordingstudio;
CREATE DATABASE myrecordingstudio;
USE myrecordingstudio;

-- ------------------------------------------------------------
-- USERS (Administrator or Client)
-- ------------------------------------------------------------
CREATE TABLE users (
    user_id     INT AUTO_INCREMENT PRIMARY KEY,
    name        VARCHAR(100) NOT NULL,
    phone       VARCHAR(20)  NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    type        ENUM('admin','client') NOT NULL,
    created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- LOCATIONS
-- ------------------------------------------------------------
CREATE TABLE locations (
    location_id     INT AUTO_INCREMENT PRIMARY KEY,
    description     VARCHAR(255) NOT NULL,
    num_studios     INT NOT NULL CHECK (num_studios > 0),
    cost_per_hour   DECIMAL(8,2) NOT NULL CHECK (cost_per_hour >= 0)
);

-- ------------------------------------------------------------
-- STUDIOS (each location has num_studios individually bookable rooms)
-- ------------------------------------------------------------
CREATE TABLE studios (
    studio_id       INT AUTO_INCREMENT PRIMARY KEY,
    location_id     INT NOT NULL,
    studio_number   INT NOT NULL,
    label           VARCHAR(50) NULL,
    FOREIGN KEY (location_id) REFERENCES locations(location_id) ON DELETE CASCADE,
    UNIQUE (location_id, studio_number)
);

-- ------------------------------------------------------------
-- BOOKINGS
-- ------------------------------------------------------------
CREATE TABLE bookings (
    booking_id      INT AUTO_INCREMENT PRIMARY KEY,
    studio_id       INT NOT NULL,
    client_id       INT NOT NULL,
    booking_date    DATE NOT NULL,
    start_time      TIME NOT NULL,
    duration_hours  INT NOT NULL CHECK (duration_hours BETWEEN 1 AND 12),
    end_time        TIME NOT NULL,
    total_cost      DECIMAL(8,2) NOT NULL,
    status          ENUM('active','cancelled') NOT NULL DEFAULT 'active',
    created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (studio_id) REFERENCES studios(studio_id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES users(user_id) ON DELETE CASCADE,
    -- business rules enforced again in PHP, mirrored here for DB-level integrity
    CHECK (start_time >= '10:00:00'),
    CHECK (end_time <= '22:00:00')
);

-- ------------------------------------------------------------
-- Sample data
-- ------------------------------------------------------------

-- Login: admin@myrecordingstudio.com / admin123  (Administrator -- company domain)
-- Login: client@gmail.com / client123             (Client -- personal webmail)
-- Hashes below are real bcrypt hashes (compatible with PHP password_verify)
INSERT INTO users (name, phone, email, password, type) VALUES
('System Admin', '61234567', 'admin@myrecordingstudio.com', '$2b$10$9NYTE7mJJ2gROox2cLHguuU4/piFA1Hn9AP4iyq8pkc22OL6Dd3GG', 'admin');

INSERT INTO locations (description, num_studios, cost_per_hour) VALUES
('Bedok Studio', 3, 50.00),
('Clementi Records', 2, 40.00),
('Punggol Vids', 4, 35.00);

INSERT INTO studios (location_id, studio_number) VALUES
(1,1),(1,2),(1,3),
(2,1),(2,2),
(3,1),(3,2),(3,3),(3,4);

