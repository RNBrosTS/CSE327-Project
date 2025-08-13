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

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Event Management</title>
    <style>
        body {
            font-family: Arial;
            padding: 18px;
            background: #fafafa;
        }
        table {
            border-collapse: collapse;
            width: 100%;
            background: #fff;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }
        form {
            background: #fff;
            padding: 12px;
            margin: 0 0 16px;
            border: 1px solid #eee;
        }
        label {
            display: block;
            margin: 8px 0 4px;
        }
        input, select {
            padding: 8px;
            width: 100%;
            max-width: 420px;
        }
        button {
            padding: 9px 14px;
            margin-top: 8px;
            background: #0066cc;
            color: #fff;
            border: 0;
            border-radius: 4px;
        }
        a.btn {
            padding: 6px 10px;
            background: #e74c3c;
            color: #fff;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h2>Event Management</h2>

    <form method="post">
        <label>Title</label>
        <input name="title" required>

        <label>Date &amp; Time</label>
        <input type="datetime-local" name="event_date" required>

        <label>Type</label>
        <select name="type">
            <option value="match">Match</option>
            <option value="training">Training</option>
            <option value="meeting">Meeting</option>
        </select>

        <button type="submit">Add Event</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Date</th>
            <th>Type</th>
            <th>Team</th>
            <th>Actions</th>
        </tr>
        
    </table>

    <p><a href="dashboard.php">← Back to Dashboard</a></p>
</body>
</html>

