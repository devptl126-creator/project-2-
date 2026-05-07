<?php
// ─────────────────────────────────────────────
//  db_connect.php  –  Single DB connection for ALL files
//  Masti Resort | College Project
// ─────────────────────────────────────────────

define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'masti_resort'); // ONE database used everywhere

function get_db(): mysqli
{
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

    if ($conn->connect_error) {
        die('DB connection failed: ' . $conn->connect_error);
    }

    // Auto-create database if not exists
    $conn->query("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "`
                  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

    $conn->select_db(DB_NAME);

    // Auto-create guests table
    $conn->query("
        CREATE TABLE IF NOT EXISTS `guests` (
            `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `name`       VARCHAR(120)  NOT NULL,
            `email`      VARCHAR(180)  NOT NULL UNIQUE,
            `phone`      VARCHAR(20)   NOT NULL,
            `password`   VARCHAR(255)  NOT NULL,
            `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB
    ");

    // Auto-create bookings table
    $conn->query("
        CREATE TABLE IF NOT EXISTS `bookings` (
            `id`         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            `guest_id`   INT UNSIGNED NOT NULL,
            `room_type`  ENUM('Deluxe','Premium','Villa') NOT NULL,
            `check_in`   DATE         NOT NULL,
            `check_out`  DATE         NOT NULL,
            `guests`     TINYINT      NOT NULL DEFAULT 1,
            `status`     ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
            `booked_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            CONSTRAINT `fk_guest`
                FOREIGN KEY (`guest_id`) REFERENCES `guests`(`id`)
                ON DELETE CASCADE
        ) ENGINE=InnoDB
    ");

    return $conn;
}
?>