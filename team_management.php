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

$sql_list_teams = "
    SELECT
        t.id,
        t.name,
        COALESCE( u.name , '—' ) AS coach_name
    FROM teams t
    LEFT JOIN users u ON u.id = t.coach_id
    ORDER BY t.id DESC
";

$sql_list_coaches = "
    SELECT id, name
    FROM users
    WHERE role = 'coach'
    ORDER BY name
";

$teams_result   = $mysqli->query( $sql_list_teams );
$coaches_result = $mysqli->query( $sql_list_coaches );
?>


