<?php
if(session_status() == PHP_SESSION_NONE)
  session_start();

if(isset($_SESSION["user"]))
  header("Location: home.php");

?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css" type="text/css">
    <title>Login</title>
  </head>
  <body>
    <div class="justify-content-center">
      <div class="container">
        <div class="form">
          <form class="login-form"  action="check_auth.php" method="post">
            <input type="text" placeholder="Email" name="email" required />
            <input type="password" placeholder="Password" name="pass" required />
            <p class="message" style="text-align: right; margin-top: 0px; margin-bottom: 15px;"><a href="get_otp.php?f=1">Forgot password</a></p>
            <button type="submit">login</button>
            <p class="message">Not registered? <a id="lnk" href="get_otp.php">Create an account</a></p>
          </form>
        </div>
      </div>
    </div>
  </body>
</html>
