<?php
session_start();
require 'config.php';
if(isset($_SESSION['id']))
{
    header("Location:Dashboard/index.php?msg=you have already login");
}
$eroormsg = "";
$user="";
if(isset($_POST['submit']))
{
	$email = $_POST['email'];
	$password = $_POST['pass'];
	$pass = MD5($password);
	$type = "user";
	
	$sql = "SELECT * FROM `tbl_user` WHERE `email`='".$email."' AND `type`='".$type."' AND `status`=1";
	$res = mysqli_query($con,$sql);
	if(mysqli_num_rows($res)>0)
	{
		$row=mysqli_fetch_assoc($res);
		if($row['email']==$email)
		{
			$user = $email;
			if($row['password']==$pass)
     		{
				$_SESSION['id'] = $row['id'];
				header("Location:Dashboard/index.php?msg=Login successfull");
				exit();
			}
			else
			{
			  $eroormsg = "Invalid Password";
			}
		}
		else
		{
		  $eroormsg = "Invalid Email Address";
		}
		
	}
	else
	{
		$eroormsg = "User not registered";
	}

}

$msg="";
if(isset($_GET['msg']))
{
	$msg = $_GET['msg'];
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
						Sign In
					</span>
					<?php  if(isset($_GET['msg'])){ echo '<h6 id="sucessmsg">'.$msg.'</h6>';} 
					if(isset($eroormsg)){ echo '<h6 id="eroormsg">'.$eroormsg.'</h6>'; } ?>						
					<div class="wrap-input100 validate-input m-b-16" data-validate="Enter correct address">
						<input class="input100" type="email" name="email" placeholder="Email" autocomplete="off" value="<?php if(isset($user)){ echo $user; } ?>">
						<span class="focus-input100"></span>
                    </div>

					<div class="wrap-input100 validate-input" data-validate = "Enter correct password">
						<input class="input100" type="password" name="pass" placeholder="Password" autocomplete="off" maxlength="20">
						<span class="focus-input100"></span>
					</div>

					<div class="text-right p-t-13 p-b-23">
						<span class="txt1">
							Forgot
						</span>

						<a href="forgot.php" class="txt2">
							Password?
						</a>
					</div>

					<div class="container-login100-form-btn">
						<input class="login100-form-btn" type="submit" name="submit" value="Login">
					</div>

					<div class="flex-col-c p-t-25 p-b-40">
						<span class="txt1 p-b-9">
							Don’t have an account?
						</span>

						<a href="register.php" class="txt3">
							Sign up now
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