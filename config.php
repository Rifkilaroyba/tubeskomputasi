<?php
session_start();

/* =====================
   ERROR DEBUG (AZURE)
===================== */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* =====================
   DATABASE ENV
===================== */
$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');

/* =====================
   SSL CERT AZURE
===================== */
$sslCert = __DIR__ . '/ssl/BaltimoreCyberTrustRoot.crt.pem';

if (!$host || !$dbname || !$username) {
    die("ENV database belum di-set di Azure App Service");
}

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_SSL_CA => $sslCert,
            PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/* =====================
   AUTOLOAD CHECK
===================== */
$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    die("vendor/autoload.php tidak ditemukan");
}
require_once $autoload;
