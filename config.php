<?php
session_start();

$host = 'mysql-tubeskomputasi.mysql.database.azure.com';
$dbname = 'cloudcomputing';
$username = 'adminuser@mysql-tubeskomputasi';
$password = 'PASSWORD_MYSQL_AZURE';

try {
    $conn = new PDO(
        "mysql:host=$host;port=3306;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]
    );
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}
