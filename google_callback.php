<?php
require_once __DIR__ . '/config.php';

if (!isset($_GET['code'])) {
    die("Google login failed");
}

/* TOKEN */
$token = $googleClient->fetchAccessTokenWithAuthCode($_GET['code']);
if (isset($token['error'])) {
    die("Google Auth Error");
}
$googleClient->setAccessToken($token);

/* USER INFO */
$oauth = new \Google\Service\Oauth2($googleClient);
$googleUser = $oauth->userinfo->get();

$email = $googleUser->email;
$name  = $googleUser->name;

/* CEK USER */
$q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
$user = mysqli_fetch_assoc($q);

if (!$user) {
    mysqli_query($conn, "
        INSERT INTO users (username, email, password, role)
        VALUES ('$name', '$email', 'GOOGLE', 'user')
    ");
    $q = mysqli_query($conn, "SELECT * FROM users WHERE email='$email'");
    $user = mysqli_fetch_assoc($q);
}

$_SESSION['user'] = $user;

header("Location: dashboard.php");
exit;
