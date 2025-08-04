<?php
require_once 'db.php';
if (session_status()===PHP_SESSION_NONE) session_start();
$error = '';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $mysqli->prepare("SELECT id,name,password,role FROM users WHERE email=?");
    $stmt->bind_param('s',$email);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($u = $res->fetch_assoc()) {
        if (password_verify($password, $u['password'])) {
            $_SESSION['user_id'] = $u['id'];
            $_SESSION['name']    = $u['name'];
            $_SESSION['role']    = $u['role'];
            switch ($u['role']) {
    case 'admin':
        header('Location: dashboard.php');
        break;
    case 'player':
        header('Location: member_home.php');
        break;
    case 'coach':
        header('Location: Coach_home.php');
        break;
    default:
        header('Location: login.php');
}
exit;

            exit;
        }
    }
    $error = 'Invalid email or password.';
}
?>
<!DOCTYPE html><html><head>
<meta charset="utf-8"><title>Login</title>
<style>
body{
        font-family: Arial;
    background:  #05ad4e8b;
    margin:0;
    display:flex;
    align-items: center;
    justify-content: center;
    height: 100vh;
}
form{
    background:  #efefefc6 ;
    padding: 60px;
    border-radius:20px;
    box-shadow:10px 10px 12px rgba(0,0,0,.08);
    width:320px;
}
h2{
    margin:0 50px 70px;
    width: 100%;
}
label{
    display:block;
    margin:10px 0 12px;
}
input{
    width:100%;
    padding:10px;
    border:1px solid #ddd;
    border-radius:6px;
}
button{
    width:70%;
    padding:10px;
    margin-top:30px;
    margin-left: 50px;
    background: #3087ddec;
    color: #fff;
    border:0;
    border-radius:6px;
}
.error
    {
        color:  #c0392b;
        margin-top: 8px;
    }
a
{
    display:inline-block;
    margin-top: 20px;
}

</style>
</head><body>
<form method="post">
  <h2>Sports Club Login</h2>
  <label>Email</label><input name="email" type="email" required>
  <label>Password</label><input name="password" type="password" required>
  <?php if($error): ?><div class="error"><?= htmlspecialchars($error) ?></div><?php endif; ?>
  <button type="submit">Login</button>
  <a href="register.php">Create an account</a>
</form>
</body></html>
