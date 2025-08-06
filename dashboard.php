<?php
require_once 'db.php';
require_admin();


$counts = ['members'=>0,'teams'=>0,'events'=>0,'attendance_today'=>0];
$res = $mysqli->query("SELECT COUNT(*) c FROM users");
$counts['members'] = (int)$res->fetch_assoc()['c'];
$res = $mysqli->query("SELECT COUNT(*) c FROM teams");
$counts['teams'] = (int)$res->fetch_assoc()['c'];
$res = $mysqli->query("SELECT COUNT(*) c FROM events WHERE event_date >= NOW() AND event_date <= DATE_ADD(NOW(), INTERVAL 30 DAY)");
$counts['events'] = (int)$res->fetch_assoc()['c'];
$res = $mysqli->query("SELECT COUNT(*) c FROM attendance WHERE DATE(noted_at)=CURDATE()");
$counts['attendance_today'] = (int)$res->fetch_assoc()['c'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Dashboard - Sports Club</title>
<style>
body {
    font-family: Arial;
    margin: 0;
    background: #f0f2f5;
}

header {
    background-color: #05ad4e8b;
    color: #1f1f1fff;
    padding: 14px 24px;
}

.grid {
    display: flex;
    flex-wrap: wrap;
    gap: 18px;
    padding: 20px;
    justify-content: center;
}


.card {
    background-color: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    padding: 22px;
    width: 260px;
    text-align: center;
}

.card h2 {
    margin: 0;
    font-size: 2.3em;
    color: #3061f4e7;
}

.card p {
    margin: 8px 0 0;
    color: #444444;
}

.nav {
    text-align: center;
    margin: 10px 0 30px;
}

.nav a {
    display: inline-block;
    margin: 6px;
    padding: 10px 16px;
    background-color: #05ad4e8b;
    color: #ffffff;
    text-decoration: none;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

.nav a:hover {
    background-color: #037032c1;
}
</style>
</head>

    
<body>
<header><h1>Admin Dashboard</h1></header>

<div class="grid">
  <div class="card"><h2><?= $counts['members'] ?></h2><p>Total Members</p></div>
  <div class="card"><h2><?= $counts['teams'] ?></h2><p>Total Teams</p></div>
  <div class="card"><h2><?= $counts['events'] ?></h2><p>Upcoming Events (30d)</p></div>
  <div class="card"><h2><?= $counts['attendance_today'] ?></h2><p>Attendance Logs Today</p></div>
</div>

<div class="nav">
  <a href="manage_team.php">Manage Teams</a>
  <a href="manage_event.php">Manage Events</a>
  <a href="ticket_payment.php">Ticket Price</a>
  <a href="track_attendance.php">Track Attendance</a>
  <a href="logout.php">Logout</a>
</div>
</body>
</html>

