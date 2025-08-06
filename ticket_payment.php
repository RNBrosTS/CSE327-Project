<?php
require_once 'db.php';
require_once 'auth.php';
require_role('admin');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $event_id = (int)$_POST['event_id'];
    $price = (float)$_POST['ticket_price'];
    $stmt = $mysqli->prepare("UPDATE events SET ticket_price = ? WHERE id = ?");
    $stmt->bind_param('di', $price, $event_id);
    $stmt->execute();
    header("Location: ticket_payment.php?success=1");
    exit;
}


$mysqli->query("ALTER TABLE events ADD COLUMN IF NOT EXISTS ticket_price DECIMAL(10,2) DEFAULT 0");
$events = $mysqli->query("SELECT id, title, event_date, COALESCE(ticket_price,0) as ticket_price FROM events ORDER BY event_date DESC");


?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Manage Ticketing</title>
    <style>
        body {
            font-family: Arial;
            margin: 0;
            background: #fafafa;
        }

        h2 {
            color: #1f1f1fff;
            background-color : #05ad4e8b;
            margin-top: 0;
            padding: 30px 26px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #ffffff;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
        }

        form {
            display: inline;
        }

        input[type="number"] {
            padding: 6px;
            width: 80px;
        }

        button {
            padding: 7px 12px;
            background-color: #16d488ec;
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .success {
            color: green;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Manage Ticket Pricing</h2>
    <?php if (isset($_GET['success'])): ?>
        <p class="success">Ticket price updated successfully.</p>
    <?php endif; ?>

    <table>
        <thead>
            <tr>
                <th>Event</th>
                <th>Date</th>
                <th>Current Ticket Price (৳)</th>
                <th>Update Price</th>
            </tr>
        </thead>
        <tbody>
            <?php while($e = $events->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($e['title']) ?></td>
                    <td><?= date('Y-m-d H:i', strtotime($e['event_date'])) ?></td>
                    <td><?= number_format($e['ticket_price'], 2) ?></td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="event_id" value="<?= $e['id'] ?>">
                            <input type="number" name="ticket_price" min="0" step="0.01" value="<?= $e['ticket_price'] ?>">
                            <button type="submit">Save</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <p><a href="dashboard.php">← Back to Dashboard</a></p>
</body>
</html>

