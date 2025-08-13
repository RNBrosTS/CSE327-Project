<?php
require_once 'db.php'; //db authentication
require_admin();//check if admin

// get the events from db
$events = $mysqli->query("SELECT id,title,event_date FROM events ORDER BY event_date DESC LIMIT 100");

// for posting attendance
$msg = '';
if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['event_id'])) {
    $event_id = (int)$_POST['event_id'];
    if (!empty($_POST['user_id']) && is_array($_POST['user_id'])) {
        $stmt = $mysqli->prepare("INSERT INTO attendance (user_id,event_id,status) VALUES (?,?,?)
                                  ON DUPLICATE KEY UPDATE status=VALUES(status)");
        foreach ($_POST['user_id'] as $uid) {
            $status = $_POST['status'][$uid] ?? 'absent';
            $uid = (int)$uid;
            $stmt->bind_param('iis', $uid, $event_id, $status);
            $stmt->execute();
        }
        $msg = 'Attendance saved.';
    }
}

// show events
$selected_event = null;
$players = $mysqli->query("SELECT id,name FROM users WHERE role IN ('player','coach') ORDER BY name");
if (isset($_GET['event'])) {
    $id = (int)$_GET['event'];
    $q = $mysqli->prepare("SELECT id,title,event_date FROM events WHERE id=?");
    $q->bind_param('i',$id); $q->execute(); $selected_event = $q->get_result()->fetch_assoc();

    // show attendance
    $existing = [];
    $res = $mysqli->prepare("SELECT user_id,status FROM attendance WHERE event_id=?");
    $res->bind_param('i',$id); $res->execute(); $r=$res->get_result();
    while($row=$r->fetch_assoc()) $existing[$row['user_id']]=$row['status'];
}
?>
<!DOCTYPE html>
<html lang="en"><head>
<meta charset="UTF-8"><title>Track Attendance</title>
<style>
body{font-family:Arial;padding:18px;background:#fafafa}
select,button{padding:8px}
table{border-collapse:collapse;width:100%;background:#fff;margin-top:12px}
th,td{border:1px solid #ddd;padding:8px}
.ok{color:green}
</style>
</head><body>
<h2>Track Attendance</h2>

<form method="get">
  <label>Select Event</label>
  <select name="event" required>
    <option value="">— choose —</option>
    <?php while($e=$events->fetch_assoc()): ?>
      <option value="<?= $e['id'] ?>" <?= (isset($_GET['event']) && (int)$_GET['event']===$e['id'])?'selected':'' ?>>
        <?= htmlspecialchars($e['title']).' — '.date('Y-m-d H:i',strtotime($e['event_date'])) ?>
      </option>
    <?php endwhile; ?>
  </select>
  <button type="submit">Load</button>
</form>

<?php if($selected_event): ?>
<p><strong>Event:</strong> <?= htmlspecialchars($selected_event['title']) ?> (<?= date('Y-m-d H:i',strtotime($selected_event['event_date'])) ?>)</p>
<?php if($msg): ?><p class="ok"><?= $msg ?></p><?php endif; ?>

<form method="post">
  <input type="hidden" name="event_id" value="<?= $selected_event['id'] ?>">
  <table>
    <tr><th>Player</th><th>Present</th><th>Absent</th><th>Late</th></tr>
    <?php while($p=$players->fetch_assoc()): 
      $uid=$p['id']; $prev=$existing[$uid] ?? 'absent'; ?>
    <tr>
      <td>
        <input type="hidden" name="user_id[]" value="<?= $uid ?>">
        <?= htmlspecialchars($p['name']) ?>
      </td>
      <td><input type="radio" name="status[<?= $uid ?>]" value="present" <?= $prev==='present'?'checked':'' ?>></td>
      <td><input type="radio" name="status[<?= $uid ?>]" value="absent" <?= $prev==='absent'?'checked':'' ?>></td>
      <td><input type="radio" name="status[<?= $uid ?>]" value="late" <?= $prev==='late'?'checked':'' ?>></td>
    </tr>
    <?php endwhile; ?>
  </table>
  <button type="submit">Save Attendance</button>
</form>
<?php endif; ?>

<p><a href="dashboard.php">← Back to Dashboard</a></p>
</body></html>
