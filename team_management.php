<?php
require_once 'db.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $sql_insert_team = "
        INSERT INTO teams (name, caoch_id)
        VALUES (?, ?)
    ";
    
    $coach_id = !empty($_POST['coach_id']) ? (int)$_POST['coach_id'] : null;

    $stmt_insert = $mysqli->prepare( $sql_insert_team );
    $stmt_insert->bind_param( 'si' , $_post['name'], $coach_id );
    $stmt->execute();
    
    header('Location: team_management.php'); exit;
}
if (isset($_GET['delete'])) {
    $team_id = (int) $_GET['delete'];

    $sql_delete_team = "DELETE FROM teams WHERE id = ?";

    $stmt_delete = $mysqli->prepare( $sql_delete_team );
    $stmt_delete->bind_param( 'i' , $team_id);
    $stmt_delete->execute();
    
    header('Location: team_management.php'); 
    exit;
}

