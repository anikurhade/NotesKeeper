<!DOCTYPE html>
<html>
<head>
	<script src='https://cdn.jsdelivr.net/npm/sweetalert2@10'></script>
	<title>Send mail</title>
</head>
<body>
<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$email = $_POST['email'];
$mail = new PHPMailer();
$otp=0;

try {
	$mail->SMTPDebug = 0;
	$mail->isSMTP();
	$mail->Host	 = 'smtp.gmail.com;';
	$mail->SMTPAuth = true;
	$mail->Username = 'akridhmusic@gmail.com';
	$mail->Password = 'Ani@1806030';
	$mail->SMTPSecure = 'tls';
	$mail->Port	 = 587;

	$mail->setFrom('akridhmusic@gmail.com', 'Note Keeper');
	$mail->addAddress($email);
	$otp=rand(1000,9999);
	$mail->isHTML(true);
	$mail->Subject = 'Subject';
	$mail->Body = '<b>OTP: </b> '.$otp;
    if($mail->send()){
        $_SESSION['email']=$email;
        $_SESSION['otp']=$otp;
		$str = "
	        <script type='text/javascript'>
		    Swal.fire(
            'OTP has been sent',
            'Please check $email',
            'success'
            ).then(function() {
            window.location.href = 'check_otp.php';
            })
	        </script>
			";

        echo $str;
    }


} catch (Exception $e) {

	echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

?>
</body>
</html>
