SET foreign_key_checks = 0;

DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    encrypted_password VARCHAR(255) NOT NULL,
    avatar_name VARCHAR(65),
    is_admin BOOLEAN NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS areas;

CREATE TABLE areas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    street VARCHAR(255) NOT NULL,
    city VARCHAR(255) NOT NULL,
    state VARCHAR(255) NOT NULL,
    zipcode VARCHAR(20) NOT NULL,
    number VARCHAR(10) NOT NULL,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    status INT NOT NULL DEFAULT 0
);

DROP TABLE IF EXISTS area_approvals;

CREATE TABLE area_approvals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL REFERENCES users(id) ON DELETE RESTRICT,
    area_id INT NOT NULL REFERENCES areas(id) ON DELETE RESTRICT,
    status_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

DROP TABLE IF EXISTS area_images;

CREATE TABLE area_images (
    id INT AUTO_INCREMENT PRIMARY KEY,
    area_id INT NOT NULL REFERENCES areas(id) ON DELETE CASCADE,
    image_name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

SET foreign_key_checks = 1;