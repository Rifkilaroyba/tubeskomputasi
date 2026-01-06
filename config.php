<?php
session_start();

/*
|--------------------------------------------------------------------------
| DATABASE CONFIG (AZURE MYSQL)
|--------------------------------------------------------------------------
| Semua nilai diambil dari Application Settings Azure
| Jangan pakai localhost / root di cloud
*/

$host     = getenv('DB_HOST');   // mysql-xxxxx.mysql.database.azure.com
$dbname   = getenv('DB_NAME');   // cloudcomputing
$username = getenv('DB_USER');   // adminuser@mysql-xxxxx
$password = getenv('DB_PASS');   // password mysql

try {
    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4;sslmode=require";

    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ]);

} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}

/*
|--------------------------------------------------------------------------
| AUTOLOAD (COMPOSER)
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/vendor/autoload.php';
