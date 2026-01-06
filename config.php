<?php
session_start();

/* =======================
   AUTO DETECT AZURE / LOCAL
======================= */
if (getenv('DB_HOST')) {
    // AZURE
    $host     = getenv('DB_HOST');
    $dbname   = getenv('DB_NAME');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASS');

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4;sslmode=require";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
} else {
    // LOCAL
    $host = 'localhost';
    $dbname = 'cloudcomputing';
    $username = 'root';
    $password = '';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
}

try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}

require_once __DIR__ . '/vendor/autoload.php';
