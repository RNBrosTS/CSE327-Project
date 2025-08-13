<?php

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';            
$DB_NAME = 'sports_club';

$mysqli = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($mysqli->connect_errno) {
    http_response_code(500);
    die('Database connection failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

function require_admin() {
    session_start();
    if (!isset($_SESSION['user_id']) || ($_SESSION['role'] ?? '') !== 'admin') {
        header('Location: login.php');
        exit();
    }
}

