<?php
session_start();

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$port = getenv('DB_PORT');

$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::MYSQL_ATTR_SSL_CA => __DIR__ . '/BaltimoreCyberTrustRoot.crt.pem',
    PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => true
];

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        $options
    );
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}
