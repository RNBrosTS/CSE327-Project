<?php
require_once 'db.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $stmt = $mysqli->prepare("INSERT INTO teams (name, coach_id) VALUES (?, ?)");
    $coach = !empty($_POST['coach_id']) ? (int)$_POST['coach_id'] : null;
    $stmt->bind_param('si', $_POST['name'], $coach);
    $stmt->execute();
    header('Location: manage_teams.php'); exit;
}
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $mysqli->prepare("DELETE FROM teams WHERE id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    header('Location: manage_teams.php'); exit;
}
