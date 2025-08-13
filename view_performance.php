<?php
require_once 'db.php';
require_once 'auth.php';
require_role('player');

$uid = (int)$_SESSION['user_id'];
$stmt = $mysqli->prepare("SELECT metric, value, measured_on FROM performance WHERE user_id=? ORDER BY measured_on ASC");
$stmt->bind_param('i', $uid);
$stmt->execute();
$result = $stmt->get_result();

$series = [];
while ($row = $result->fetch_assoc()) {
    $metric = $row['metric'];
    $series[$metric]['labels'][] = $row['measured_on'];
    $series[$metric]['values'][] = (float)$row['value'];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>My Performance</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: Arial; padding: 18px; background: #f7f9fb; }
        .card { background: #fff; padding: 16px; border-radius: 8px; border: 1px solid #eee; margin-bottom: 20px; }
        h2 { color: #003366; }
    </style>
</head>
<body>
<h2>My Performance Overview</h2>

<div class="card">
    <canvas id="perfChart" height="160"></canvas>
</div>

<p><a href="member_home.php">← Back to Dashboard</a></p>

<script>
const series = <?= json_encode($series) ?>;
const labels = [...new Set(Object.values(series).flatMap(s => s.labels))].sort();
const colors = ['#3366CC','#DC3912','#FF9900','#109618','#990099','#0099C6'];
const datasets = Object.keys(series).map((m,i)=>({
    label:m,
    data:labels.map(d=>{
        const idx=series[m].labels.indexOf(d);
        return idx>=0 ? series[m].values[idx] : null;
    }),
    borderWidth:2,
    borderColor:colors[i%colors.length],
    fill:false,
    tension:0.3
})
