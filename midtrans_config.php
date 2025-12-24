<?php
require_once __DIR__ . '/vendor/autoload.php';

\Midtrans\Config::$serverKey = 'Mid-server-h2Y50mM89__L9jcN-i7ISgeX';
\Midtrans\Config::$isProduction = false; // SANDBOX
\Midtrans\Config::$isSanitized = true;
\Midtrans\Config::$is3ds = false;

// CEGAH WARNING CONSTANT DOUBLE
if (!defined('MIDTRANS_CLIENT_KEY')) {
    define('MIDTRANS_CLIENT_KEY', 'Mid-client-YAHqo6aUhT2SBSO1');
}
