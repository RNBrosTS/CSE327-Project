<?php
require_once 'db.php';
if (session_status()===PHP_SESSION_NONE) session_start();
$msg=''; $err='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name=trim($_POST['name']); $email=trim($_POST['email']);
    $pass=$_POST['password'];   $role=($_POST['role']??'player');
    if (!in_array($role,['player','coach','admin'])) $role='player';
    $hash=password_hash($pass, PASSWORD_BCRYPT);
    $stmt=$mysqli->prepare("INSERT INTO users (name,email,password,role) VALUES (?,?,?,?)");
    $stmt->bind_param('ssss',$name,$email,$hash,$role);
    if ($stmt->execute()) { $msg='Registration successful. You can login now.'; }
    else { $err = ($mysqli->errno==1062) ? 'Email already exists.' : 'Registration failed.'; }
}
?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>Register</title>
<style>
   
body
  {
    font-family: Arial;
    background: #05ad4e8b;
    margin:0;
    display:flex;
    align-items:center;
    justify-content:center;
    height:100vh;
  }

form
  {
    background: #efefefc6;
    padding:60px;
    border-radius:20px;
    box-shadow:0 2px 12px rgba(0,0,0,.08);
    width:360px;
  }

label
  {
    display:block;
    margin:10px 0 4px;
  }

input,select 
  {
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
  }

button
  {
    width:100%;
    padding:10px;
    margin-top:12px;
    background: #0066cc;
    color: #fff;
    border:0;
    border-radius:6px;
    margin : 20px;
  }
.ok
    {
      color:#27ae60;
    }
.er 
    {
      color:#c0392b;
    }


</style>
</head>
<body>
<form method="post">
  <h2>Create Account</h2>
  <?php if($msg): ?><div class="ok"><?= $msg ?></div><?php endif; ?>
  <?php if($err): ?><div class="er"><?= $err ?></div><?php endif; ?>
  <label>Name</label><input name="name" required>
  <label>Email</label><input name="email" type="email" required>
  <label>Password</label><input name="password" type="password" required>
  <label>Role</label>
  <select name="role">
    <option value="player">Player</option>
    <option value="coach">Coach</option>
    <option value="admin">Admin</option>
  </select>
  <button type="submit">Register</button>
  <a href="login.php">Back to Login</a>
</form>
</body></html>
