<?php
if(session_status() == PHP_SESSION_NONE)
  session_start();

if(isset($_GET['archive'])) {
    $_SESSION['archive'] = $_GET['archive'];
    if(isset($_SESSION['bin']))
      unset($_SESSION['bin']);
}

if(isset($_GET['bin'])) {
    $_SESSION['bin'] = $_GET['bin'];
    if(isset($_SESSION['archive']))
      unset($_SESSION['archive']);
}

if(isset($_GET['destroy'])) {
    if(isset($_SESSION['bin']))
      unset($_SESSION['bin']);

    if(isset($_SESSION['archive']))
      unset($_SESSION['archive']);
}

//header("location: home.php");
echo "<script>location.replace('home.php');</script>";
exit;
?>