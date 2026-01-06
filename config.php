<?php
session_start();

/*
|--------------------------------------------------------------------------
| AUTO DETECT ENVIRONMENT
|--------------------------------------------------------------------------
| Kalau ada DB_HOST dari Azure → pakai Azure
| Kalau tidak → pakai Localhost
*/
$isAzure = getenv('DB_HOST') !== false;

/*
|--------------------------------------------------------------------------
| DATABASE CONFIG
|--------------------------------------------------------------------------
*/
if ($isAzure) {
    // ===== AZURE MYSQL =====
    $host     = getenv('DB_HOST');
    $dbname   = getenv('DB_NAME');
    $username = getenv('DB_USER');
    $password = getenv('DB_PASS');
    $port     = getenv('DB_PORT') ?: 3306;

    $ssl_ca = __DIR__ . "/BaltimoreCyberTrustRoot.crt.pem";

    $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4;sslmode=verify_ca";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::MYSQL_ATTR_SSL_CA => $ssl_ca,
        PDO::MYSQL_ATTR_SSL_VERIFY_SERVER_CERT => false
    ];

} else {
    // ===== LOCALHOST (XAMPP) =====
    $host     = 'localhost';
    $dbname   = 'cloudcomputing';
    $username = 'root';
    $password = '';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";

    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];
}

/*
|--------------------------------------------------------------------------
| CONNECT DATABASE
|--------------------------------------------------------------------------
*/
try {
    $conn = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Koneksi Gagal: " . $e->getMessage());
}

/*
|--------------------------------------------------------------------------
| COMPOSER AUTOLOAD
|--------------------------------------------------------------------------
*/
require_once __DIR__ . '/vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| GOOGLE CLIENT (OPTIONAL)
|--------------------------------------------------------------------------
*/
// $googleClient = new \Google\Client();
// $googleClient->setClientId("ISI_CLIENT_ID");
// $googleClient->setClientSecret("ISI_CLIENT_SECRET");
// $googleClient->setRedirectUri("http://localhost/cece/google_callback.php");
// $googleClient->addScope("email");
// $googleClient->addScope("profile");
