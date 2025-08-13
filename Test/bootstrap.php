<?php
declare(strict_types=1);

error_reporting(E_ALL);
ini_set('display_errors', '1');

$host = getenv('TEST_DB_HOST') ?: '127.0.0.1';
$user = getenv('TEST_DB_USER') ?: 'root';
$pass = getenv('TEST_DB_PASS') ?: '';
$db   = getenv('TEST_DB_NAME') ?: 'scms_test';

$mysqli = new mysqli($host, $user, $pass, $db);
if ($mysqli->connect_errno) {
    fwrite(STDERR, "Test DB connection failed: {$mysqli->connect_error}\n");
    exit(1);
}
