<?php
session_start();

/* ==========================
   ENABLE ERROR (WAJIB AZURE)
========================== */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ==========================
   DATABASE CONFIG
   (ENV SUPPORT)
========================== */
$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'cloudcomputing';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

/* ==========================
   COMPOSER AUTOLOAD
========================== */
$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    die("vendor/autoload.php tidak ditemukan. Jalankan composer install!");
}
require_once $autoload;

/* ==========================
   GOOGLE OAUTH (OPTIONAL)
========================== */
/*
$googleClient = new \Google\Client();
$googleClient->setClientId(getenv('GOOGLE_CLIENT_ID'));
$googleClient->setClientSecret(getenv('GOOGLE_CLIENT_SECRET'));
$googleClient->setRedirectUri(getenv('GOOGLE_REDIRECT_URI'));
$googleClient->addScope("email");
$googleClient->addScope("profile");
*/
