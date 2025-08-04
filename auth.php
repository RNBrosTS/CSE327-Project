<?php
// auth.php
if (session_status() === PHP_SESSION_NONE) session_start();

function require_login() {
    if (!isset($_SESSION['user_id'])) { header('Location: login.php'); exit; }
}
function require_role($role) {
    require_login();
    if (($_SESSION['role'] ?? '') !== $role) { http_response_code(403); echo "Forbidden"; exit; }
}
function is_admin() { return isset($_SESSION['role']) && $_SESSION['role']==='admin'; }
