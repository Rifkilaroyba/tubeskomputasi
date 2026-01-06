<?php
session_start();

$host = "mysql-tubeskomputasi.mysql.database.azure.com";
$dbname = "cloudcomputing";
$username = "adminmysql@mysql-tubeskomputasi";
$password = "PASSWORD_MYSQL_AZURE";
$port = 3306;

try {
    $conn = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::MYSQL_ATTR_SSL_CA => false
        ]
    );
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}
