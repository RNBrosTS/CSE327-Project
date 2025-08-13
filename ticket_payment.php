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


$events = $mysqli->query("
    SELECT id, title, event_date, COALESCE(ticket_price, 0) AS ticket_price 
    FROM events 
    ORDER BY event_date DESC
");
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Manage Ticket Pricing</title>
  <style>
    body {
      font-family: Arial;
      margin: 0;
      background: #f9f9f9;
    }

    h2 {
      background: #05ad4e8b;
      color: #1f1f1f;
      margin-top: 0;
      padding: 25px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
      background: white;
    }

    th, td {
      padding: 10px;
      border: 1px solid #ddd;
    }

    form {
      display: inline;
    }

    input[type="number"] {
      width: 80px;
      padding: 6px;
    }

    button {
      background: #16d488ec;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
    }

    .success {
      color: green;
      padding: 15px;
    }

    a {
      display: inline-block;
      margin: 20px;
      text-decoration: none;
      color: #0077cc;
    }
  </style>
</head>
<body>

  <h2>Manage Ticket Pricing</h2>

  <?php if (isset($_GET['success'])): ?>
    <div class="success">✔ Ticket price updated!</div>
  <?php endif; ?>

  <table>
    <thead>
      <tr>
        <th>Event</th>
        <th>Date</th>
        <th>Current Price (৳)</th>
        <th>Change Price</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($event = $events->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($event['title']) ?></td>
          <td><?= date('Y-m-d H:i', strtotime($event['event_date'])) ?></td>
          <td><?= number_format($event['ticket_price'], 2) ?></td>
          <td>
            <form method="post">
              <input type="hidden" name="event_id" value="<?= $event['id'] ?>">
              <input type="number" name="ticket_price" min="0" step="0.01" value="<?= $event['ticket_price'] ?>">
              <button type="submit">Save</button>
            </form>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <a href="dashboard.php">← Back to Dashboard</a>

</body>
</html>
