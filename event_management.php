<?php
require_once 'db.php';
require_admin();

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $sql_insert_event = "
        INSERT INTO events (title, event_date, type, team_id)
        VALUES ( ?, ?, ?, ? )
    ";
    
    $team_id = !empty($_POST['team_id']) ? (int)$_POST['team_id'] : null;

    $stmt_insert = $mysqli->prepare( $sql_insert_event );
    $stmt->bind_param(
        'sssi', 
        $_POST['title'], 
        $_POST['event_date'], 
        $_POST['type'], 
        $team_id
    );
    $stmt->execute();
    
    header('Location: manage_events.php'); 
    exit;
}

if (isset($_GET['delete'])) {
    $event_id = (int) $_GET['delete'];

    $sql_delete_event = "DELETE FROM events WHERE id=?";

    $stmt_delete = $mysqli->prepare( $sql_delete_event );
    $stmt_delete->bind_param('i' , $event_id );
    $stmt_delete->execute();
    
    header('Location: event_management.php' ); 
    exit;
}


$sql_list_teams = "
    SELECT id, name
    FROM teams
    ORDER BY name
";
$sql_list_events = "
    SELECT
        e.id,
        e.title,
        e.event_date,
        e.type,
        COALESCE( t.name , '—' ) AS team_name
    FROM events e
    LEFT JOIN teams t ON t.id = e.team_id
    ORDER BY e.event_date DESC
";

$teams_result  = $mysqli->query( $sql_list_teams );
$events_result = $mysqli->query( $sql_list_events );
?>
