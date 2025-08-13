<?php
require_once 'db.php';

require_admin();

$member_count = 0;
$team_count = 0;
$event_count = 0;
$attendance_today = 0;


$result = $mysqli->query("SELECT COUNT(*) as total FROM users");
$member_count = $result->fetch_assoc()['total'] ?? 0;

$result = $mysqli->query("SELECT COUNT(*) as total FROM teams");
$team_count = $result->fetch_assoc()['total'] ?? 0;

$result = $mysqli->query("
    SELECT COUNT(*) as total 
    FROM events 
    WHERE event_date BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 DAY)
");
$event_count = $result->fetch_assoc()['total'] ?? 0;

$result = $mysqli->query("
    SELECT COUNT(*) as total 
    FROM attendance 
    WHERE DATE(noted_at) = CURDATE()
");
$attendance_today = $result->fetch_assoc()['total'] ?? 0;
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <style>
    body {
      margin: 0;
      font-family: Arial;
      background: #f1f1f1;
    }

    header {
      background: #05ad4e8b;
      padding: 15px 25px;
      color: #222;
    }

    .grid {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      padding: 30px;
    }

    .card {
      background: white;
      width: 250px;
      border-radius: 10px;
      padding: 20px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      text-align: center;
    }

    .card h2 {
      color: #2f70f7;
      font-size: 2.2em;
      margin: 0;
    }

    .card p {
      margin: 10px 0 0;
      font-size: 1em;
      color: #444;
    }

    .nav {
      text-align: center;
      margin-bottom: 30px;
    }

    .nav a {
      display: inline-block;
      background: #05ad4e8b;
      color: white;
      padding: 10px 16px;
      border-radius: 5px;
      text-decoration: none;
      margin: 5px;
      transition: background 0.3s ease;
    }

    .nav a:hover {
      background: #037032c1;
    }
  </style>
</head>
<body>

  <header>
    <h1>Admin Dashboard</h1>
  </header>

  <div class="grid">
    <div class="card">
      <h2><?= $member_count ?></h2>
      <p>Total Members</p>
    </div>
    <div class="card">
      <h2><?= $team_count ?></h2>
      <p>Total Teams</p>
    </div>
    <div class="card">
      <h2><?= $event_count ?></h2>
      <p>Upcoming Events (30 Days)</p>
    </div>
    <div class="card">
      <h2><?= $attendance_today ?></h2>
      <p>Attendance Today</p>
    </div>
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
