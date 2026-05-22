-- =============================================================
--  CpE Registry — Contact Tracing Database Schema
--  File:    database/schema.sql
--  Engine:  MySQL 5.7+ / MariaDB 10.3+
--  Charset: utf8mb4
--  Import via: phpMyAdmin > Import, or `mysql -u root < schema.sql`
-- =============================================================

-- -------------------------------------------------------
-- 0. Create and select the database
-- -------------------------------------------------------
CREATE DATABASE IF NOT EXISTS cpe_registry
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE cpe_registry;

-- -------------------------------------------------------
-- 1. visitors
--    Stores each unique visitor's registration details.
--    Address is split into barangay / city / province so
--    the admin can filter on each level independently.
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS visitors (
    id             INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    id_number      VARCHAR(30)     NOT NULL,           -- student / employee ID
    first_name     VARCHAR(60)     NOT NULL,
    last_name      VARCHAR(60)     NOT NULL,
    barangay       VARCHAR(80)     NOT NULL,
    city           VARCHAR(80)     NOT NULL,
    province       VARCHAR(80)     NOT NULL,
    contact_number VARCHAR(20)     NOT NULL,
    email          VARCHAR(120)    NOT NULL,
    created_at     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_id_number (id_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 2. visit_logs
--    Records every sign-in/sign-out event.
--    sign_out IS NULL  →  visitor is currently signed in.
--    sign_out NOT NULL →  visit is completed.
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS visit_logs (
    log_id      INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    visitor_id  INT UNSIGNED    NOT NULL,
    sign_in     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
    sign_out    TIMESTAMP       NULL     DEFAULT NULL,

    PRIMARY KEY (log_id),
    KEY idx_visitor_id (visitor_id),
    KEY idx_sign_in    (sign_in),

    CONSTRAINT fk_visit_visitor
        FOREIGN KEY (visitor_id)
        REFERENCES visitors (id)
        ON DELETE CASCADE
        ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 3. admin
--    Stores administrator credentials.
--    password is a bcrypt hash (never store plaintext).
--    Seeded with one default admin account below.
-- -------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin (
    admin_id    INT UNSIGNED    NOT NULL AUTO_INCREMENT,
    username    VARCHAR(60)     NOT NULL,
    password    VARCHAR(255)    NOT NULL,   -- bcrypt hash
    created_at  TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (admin_id),
    UNIQUE KEY uq_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -------------------------------------------------------
-- 4. Seed: default admin account
--    Username : admin
--    Password : Admin@123   (bcrypt hash below)
--    CHANGE THIS before going live.
-- -------------------------------------------------------
INSERT IGNORE INTO admin (username, password)
VALUES (
    'admin',
    '$2y$10$/igu8XrF4Kgtd32jP4HMZOVucxzr7rcOOZkDTcKjzF.OZQU7xRJL6'
);
