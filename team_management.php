<?php
require_once 'db.php';
require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name'])) {
    $sql_insert_team = "
        INSERT INTO teams (name, caoch_id)
        VALUES (?, ?)
    ";
    
    $coach_id = !empty($_POST['coach_id']) ? (int)$_POST['coach_id'] : null;

    $stmt_insert = $mysqli->prepare( $sql_insert_team );
    $stmt_insert->bind_param( 'si' , $_post['name'], $coach_id );
    $stmt->execute();
    
    header('Location: team_management.php'); exit;
}
if (isset($_GET['delete'])) {
    $team_id = (int) $_GET['delete'];

    $sql_delete_team = "DELETE FROM teams WHERE id = ?";

    $stmt_delete = $mysqli->prepare( $sql_delete_team );
    $stmt_delete->bind_param( 'i' , $team_id);
    $stmt_delete->execute();
    
    header('Location: team_management.php'); 
    exit;
}

$sql_list_teams = "
    SELECT
        t.id,
        t.name,
        COALESCE( u.name , '—' ) AS coach_name
    FROM teams t
    LEFT JOIN users u ON u.id = t.coach_id
    ORDER BY t.id DESC
";

$sql_list_coaches = "
    SELECT id, name
    FROM users
    WHERE role = 'coach'
    ORDER BY name
";

$teams_result   = $mysqli->query( $sql_list_teams );
$coaches_result = $mysqli->query( $sql_list_coaches );
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Team Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 18px;
            background: #fafafa;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            background: #ffffff;
        }

        th, td {
            border: 1px solid #dddddd;
            padding: 10px;
        }

        form {
            background: #ffffff;
            padding: 12px;
            margin: 0 0 16px;
            border: 1px solid #eeeeee;
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
            color: #ffffff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        a.btn {
            padding: 6px 10px;
            background: #e74c3c;
            color: #ffffff;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <h2>Team Management</h2>

    <form method="post">
        <label>Team Name</label>
        <input name="name" required>

        <label>Coach</label>
        <select name="coach_id">
            <option value="">— None —</option>
        </select>

        <button type="submit">Add Team</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Coach</th>
            <th>Actions</th>
        </tr>
       
    </table>

    <p><a href="dashboard.php">← Back to Dashboard</a></p>
</body>
</html>



