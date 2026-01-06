<?php
session_start();

/* =======================
   DATABASE CONFIG
======================= */

$host     = getenv('DB_HOST');      // contoh: mysql-tubeskomputasi.mysql.database.azure.com
$dbname   = getenv('DB_NAME');      // cloudcomputing
$username = getenv('DB_USER');      // adminuser@mysql-tubeskomputasi
$password = getenv('DB_PASS');      // password mysql

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4;sslmode=require",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
    );
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}

require_once __DIR__ . '/vendor/autoload.php';
