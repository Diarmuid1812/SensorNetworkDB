CREATE TABLE users (
                       `id` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
                       `username` VARCHAR(50) NOT NULL UNIQUE,
                       `email`    varchar (30) NOT NULL UNIQUE,
                       `password` VARCHAR(255) NOT NULL,
                       `admin`    BOOLEAN NOT NULL,
                       `passw_changed` BOOLEAN DEFAULT FALSE,
                       `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);