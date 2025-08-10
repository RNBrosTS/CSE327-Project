<?php
require_once 'db.php';
require_admin();
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $stmt = $mysqli->prepare("INSERT INTO events (title,event_date,type,team_id) VALUES (?,?,?,?)");
    $team = !empty($_POST['team_id']) ? (int)$_POST['team_id'] : null;
    $stmt->bind_param('sssi', $_POST['title'], $_POST['event_date'], $_POST['type'], $team);
    $stmt->execute();
    header('Location: manage_events.php'); exit;
}

if (isset($_GET['delete'])) {
    $id=(int)$_GET['delete'];
    $stmt=$mysqli->prepare("DELETE FROM events WHERE id=?");
    $stmt->bind_param('i',$id);
    $stmt->execute();
    header('Location: manage_events.php'); exit;
}
