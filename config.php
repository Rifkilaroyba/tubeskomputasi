<?php
session_start();

$host = 'localhost';
$dbname = 'cloudcomputing';
$username = 'root';
$password = '';

try {
    $conn = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}

require_once __DIR__ . '/vendor/autoload.php';

/* GOOGLE CLIENT (FULL CLASS NAME) */
// $googleClient = new \Google\Client();
// $googleClient->setClientId("ISI_CLIENT_ID");
// $googleClient->setClientSecret("ISI_CLIENT_SECRET");
// $googleClient->setRedirectUri("http://localhost/cece/google_callback.php");
// $googleClient->addScope("email");
// $googleClient->addScope("profile");