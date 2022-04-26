<?php 

if(session_status() == PHP_SESSION_NONE)
  session_start();
$_SESSION['otp'] = null;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>Signup</title>
</head>
<body>
<div class="login-page">
  <div class="container">
    <div class="form">
      <form class="register-form" action="save.php" method="POST">
        <input type="password" placeholder="Password" name="pass" />
        <input type="password" placeholder="Confirm password" name="cpass"/>
        <button type="submit">Sign Up</button>
        <p class="message">Already registered? <a href="index.php">Sign In</a></p>
      </form>
    </div>
  </div>
</div>
</body>
</html>


