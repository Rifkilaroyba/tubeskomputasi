<?php
/**
 * ==========================================================
 * CONFIG.PHP - AZURE APP SERVICE READY (FINAL)
 * ==========================================================
 */

/* -------------------- SESSION (WAJIB UNTUK AZURE) -------------------- */
ini_set('session.save_path', sys_get_temp_dir());
ini_set('session.cookie_secure', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'None');

session_start();

/* -------------------- AUTO DETECT ENV -------------------- */
$isAzure = getenv('DB_HOST') !== false;

/* -------------------- DATABASE CONFIG -------------------- */
if ($isAzure) {
    // ===== AZURE MYSQL =====
    $host     = getenv('DB_HOST');
    $dbname   = getenv('DB_NAME');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $port     = getenv('DB_PORT') ?: 3306;

    $ssl_ca = __DIR__ . "/BaltimoreCyberTrustRoot.crt.pem";

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];
} else {
    // ===== LOCALHOST =====
    $dsn = "mysql:host=localhost;dbname=cloudcomputing;charset=utf8mb4";
    $username = "root";
    $password = "";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
}

/* -------------------- CONNECT DATABASE -------------------- */
try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Koneksi database gagal");
}

/* -------------------- COMPOSER AUTOLOAD (OPTIONAL) -------------------- */
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

/* -------------------- FORCE ROLE (DEMO / TUGAS) -------------------- */
if (isset($_SESSION['user_id'])) {
    $_SESSION['role'] = $_SESSION['role'] ?? 'admin';
}
