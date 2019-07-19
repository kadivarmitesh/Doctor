<?php
session_start();
require 'config.php';
if(isset($_SESSION['id']))
{
    header("Location:Dashboard/index.php?msg=you have already login");
}

$successmsg = "";
$eroormsg = "";
if(isset($_POST['submit']))
{
	$email = $_POST['email'];
	$type = "user";
	
	$sql = "SELECT `email`,`password` FROM `tbl_user` WHERE `email`='".$email."'
	AND `type`='".$type."'";

	$res = mysqli_query($con,$sql);
	if(mysqli_num_rows($res)>0)
	{
		$row=mysqli_fetch_assoc($res);
		$email=$row['email'];
		$pass=$row['password'];
		$link="<a href='http://localhost/doctor/reset_pass.php?key=".$email."&reset=".$pass."'>Click To Reset password</a>";
	
		require("phpmailer/class.phpmailer.php");

		$mess   = '<p>Click to following link to reset your password</p></br>
					'.$link;

		$mail = new PHPMailer;

		$mail->IsSMTP();                                      
		$mail->Host = "mail.overseasits.com";                 
		
		$mail->Port = 587;                                    
		$mail->SMTPAuth = true;                               
		$mail->Username = "mitesh.kadivar@overseasits.com";   
		$mail->Password = "mitesh@123";                  
		

		$mail->From = 'mitesh.kadivar@overseasits.com';
		$mail->FromName = 'Online Consult Doctor - Reset Password';

		
		$mail->AddAddress($email);  
		$mail->IsHTML(true);                                  
		$mail->Subject = 'Reset password';
		$mail->Body = $mess;
		
		$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


		if(!$mail->Send()) {
			echo "Mailer Error: " . $mail->ErrorInfo;
		} else {
			$successmsg = "Message has been sent check your email";
		}
	}
	else
	{
		$eroormsg = "Please enter valid email address";
	}

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Online Consult Doctor</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="images/icons/favicon.ico"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/bootstrap/css/bootstrap.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="fonts/font-awesome-4.7.0/css/font-awesome.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animate/animate.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/css-hamburgers/hamburgers.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/animsition/css/animsition.min.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="vendor/select2/select2.min.css">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="vendor/daterangepicker/daterangepicker.css">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="css/util.css">
	<link rel="stylesheet" type="text/css" href="css/main.css">
<!--===============================================================================================-->
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form method="post" action="#" class="login100-form validate-form p-l-55 p-r-55 p-t-110">
					<span class="login100-form-title">
						Forgot Password
					</span>
					<?php if(isset($successmsg)){ echo '<h6 id="sucessmsg">'.$successmsg.'</h6>';} 
						if(isset($eroormsg)){ echo '<h6 id="eroormsg">'.$eroormsg.'</h6>';}	
					?>	
					<div class="wrap-input100 validate-input m-b-16" data-validate="Please enter email">
						<input class="input100" type="email" name="email" placeholder="Email">
						<span class="focus-input100"></span>
                    </div>

					<div class="container-login100-form-btn">
						<input class="login100-form-btn" type="submit" name="submit" value="Send Link">
					</div>

					<div class="flex-col-c p-t-25 p-b-40">
						<span class="txt1 p-b-9">
                            Remembered your password?
						</span>

						<a href="index.php" class="txt3">
							Sign In here
						</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	
	
<!--===============================================================================================-->
	<script src="vendor/jquery/jquery-3.2.1.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/animsition/js/animsition.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/bootstrap/js/popper.js"></script>
	<script src="vendor/bootstrap/js/bootstrap.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/select2/select2.min.js"></script>
<!--===============================================================================================-->
	<script src="vendor/daterangepicker/moment.min.js"></script>
	<script src="vendor/daterangepicker/daterangepicker.js"></script>
<!--===============================================================================================-->
	<script src="vendor/countdowntime/countdowntime.js"></script>
<!--===============================================================================================-->
	<script src="js/main.js"></script>

</body>
</html>