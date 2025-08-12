<?php
require_once 'db.php';
require_once 'auth.php';
require_role('coach');


// Save metric
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $metric = trim($_POST['metric'] ?? '');
    $value  = (float)($_POST['value'] ?? 0);
    $date   = $_POST['measured_on'] ?? date('Y-m-d');
    $uid    = (int)$_SESSION['user_id'];

    if ($metric && $value>0) {
        $stmt=$mysqli->prepare("INSERT INTO performance (user_id,metric,value,measured_on) VALUES (?,?,?,?)");
        $stmt->bind_param('isds',$uid,$metric,$value,$date);
        $stmt->execute();
    }
    header('Location: performance.php'); exit;
}

// Load metrics for logged user
$uid = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
$stmt=$mysqli->prepare("SELECT metric,value,measured_on FROM performance WHERE user_id=? ORDER BY measured_on ASC, id ASC");
$stmt->bind_param('i',$uid); $stmt->execute(); $res=$stmt->get_result();

$series = []; // metric => [labels[], values[]]
while($r=$res->fetch_assoc()){
    $m=$r['metric']; $series[$m]['labels'][]=$r['measured_on']; $series[$m]['values'][]=(float)$r['value'];
}
?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>Performance Tracking</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<style>
body{font-family:Arial;padding:18px;background:#f7f9fb}
form{background:#fff;padding:12px;border:1px solid #eee;border-radius:8px;margin-bottom:14px;max-width:560px}
label{display:block;margin:8px 0 4px} input,select{padding:8px;width:100%} button{padding:9px 14px;margin-top:8px;background:#0066cc;color:#fff;border:0;border-radius:4px}
.card{background:#fff;padding:16px;border-radius:8px;border:1px solid #eee}
.grid{display:grid;grid-template-columns:1fr;gap:18px}
.table-wrap{overflow:auto;background:#fff;border:1px solid #eee;border-radius:8px}
table{border-collapse:collapse;width:100%}th,td{border:1px solid #eee;padding:8px}
</style>
</head><body>
<h2>Performance Tracking</h2>

<?php
// Load all players for selection
$players = $mysqli->query("SELECT id, name FROM users WHERE role='player' ORDER BY name");
?>
<form method="post">
    <label>Select Player</label>
    <select name="user_id" required>
        <option value="">— Select Player —</option>
        <?php while($p = $players->fetch_assoc()): ?>
            <option value="<?= $p['id'] ?>"><?= htmlspecialchars($p['name']) ?></option>
        <?php endwhile; ?>
    </select>

    <label>Metric (e.g., speed, goals)</label>
    <input name="metric" required>

    <label>Value</label>
    <input name="value" type="number" step="0.01" required>

    <label>Date</label>
    <input type="date" name="measured_on" value="<?= date('Y-m-d') ?>" required>

    <button type="submit">Save Performance</button>
</form>


<div class="grid">
  <div class="card">
    <canvas id="perfChart" height="140"></canvas>
  </div>
  <div class="table-wrap">
    <table id="perfTable">
      <thead><tr><th>Date</th><th>Metric</th><th>Value</th></tr></thead>
      <tbody>
      <?php
      // reload for table
      $stmt->execute(); $res=$stmt->get_result();
      while($r=$res->fetch_assoc()): ?>
        <tr><td><?= htmlspecialchars($r['measured_on']) ?></td>
            <td><?= htmlspecialchars($r['metric']) ?></td>
            <td><?= htmlspecialchars($r['value']) ?></td></tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>

<script>
// Build datasets from PHP
const series = <?= json_encode($series) ?>;
const labels = [...new Set(Object.values(series).flatMap(s => s.labels))].sort();
const colors = ['#3366CC','#DC3912','#FF9900','#109618','#990099','#0099C6','#DD4477','#66AA00'];

const datasets = Object.keys(series).map((m,i)=>({
  label:m,
  data: labels.map(d => {
    const idx = series[m].labels.indexOf(d);
    return idx>=0 ? series[m].values[idx] : null;
  }),
  borderWidth:2,
  fill:false,
  tension:0.2,
  borderColor: colors[i % colors.length],
  pointRadius:3
}));

new Chart(document.getElementById('perfChart'),{
  type:'line',
  data:{ labels, datasets },
  options:{ responsive:true, maintainAspectRatio:false, scales:{ y:{ beginAtZero:true } } }
});
</script>

<p><a href="Coach_home.php">← Back to Home</a></p>
</body></html>
