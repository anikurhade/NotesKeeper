<?php

require_once 'check_auth.php';

if(isset($_SESSION['user']))
{
    header("location: home.php");
    exit;
}

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
      <form class="register-form" action="send_mail.php" method="POST">
        <input type="email" placeholder="Email" name="email" required />
        <button type="submit">Get OTP</button>
        <p class="message">Already registered? <a href="index.php">Sign In</a></p>
      </form>
    </div>
  </div>
</div>
</body>
</html>


