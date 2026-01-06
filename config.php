<?php
session_start();

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$port = getenv('DB_PORT');

$ssl_ca = __DIR__ . "/BaltimoreCyberTrustRoot.crt.pem";

try {
    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4;sslmode=verify_ca";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];

    $conn = new PDO($dsn, $username, $password, $options);
    echo "✅ CONNECT OK";

} catch (PDOException $e) {
    die("❌ Koneksi Gagal: " . $e->getMessage());
}
