<?php
require_once 'db.php';
require_once 'auth.php';
require_login();
$redirect = 'login.php';
if (isset($_SESSION['role'])) {
    if ($_SESSION['role'] === 'player') {
        $redirect = 'member_home.php';
    } elseif ($_SESSION['role'] === 'coach') {
        $redirect = 'coach_home.php';
    } elseif ($_SESSION['role'] === 'admin') {
        $redirect = 'dashboard.php';
    }
}


// Fetch all events
$events = $mysqli->query("SELECT title, event_date, type, COALESCE(ticket_price, 0) AS ticket_price FROM events ORDER BY event_date ASC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upcoming Events</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 18px;
            background: #f9f9f9;
        }

        h2 {
            color: #003366;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #ffffff;
            margin-top: 16px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        th {
            background-color: #05ad4e8b;
            color: #fff;
        }

        td {
            color: #333;
        }

        a.btn {
            display: inline-block;
            margin-top: 18px;
            padding: 10px 16px;
            background-color: #05ad4e8b;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
        }

        a.btn:hover {
            background-color: #038a3f;
        }
    </style>
</head>
<body>

    <h2>Upcoming Events & Ticket Prices</h2>

    <table>
        <thead>
            <tr>
                <th>Event Title</th>
                <th>Date</th>
                <th>Type</th>
                <th>Ticket Price (৳)</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($e = $events->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($e['title']) ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($e['event_date'])) ?></td>
                    <td><?= htmlspecialchars($e['type']) ?></td>
                    <td><?= number_format($e['ticket_price'], 2) ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

<a class="btn" href="<?= $redirect ?>">← Back to Dashboard</a>

</body>
</html>
