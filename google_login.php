<?php
session_start();
require_once 'config.php';

header("Location: " . $googleClient->createAuthUrl());
exit;
