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
