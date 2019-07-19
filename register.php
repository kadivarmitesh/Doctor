<?php
session_start();
require 'config.php';
if(isset($_SESSION['id']))
{
    header("Location:Dashboard/index.php?msg=you have already Registration");
}
$eroormsg = "";
if(isset($_POST['submit']))
{
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['pass'];
	$phone = $_POST['phone'];

	$status = 1;
	$type = "user";
	$dat = date('y-m-d h:i:s');

	$sql_e = "SELECT * FROM tbl_user WHERE email='$email'";
	$res_e = mysqli_query($con, $sql_e);

	if(mysqli_num_rows($res_e) > 0){
  	  $eroormsg = "User already Exist";
  	}
	else
	{
		$sql = "INSERT INTO `tbl_user`(`username`, `email`, `password`, `mobileno`, `status`, `createddate`, `type`) VALUES ('$username','$email',MD5('".$password."'),'$phone',$status,'$dat','$type')";
		
		if(mysqli_query($con, $sql)){
			$id= mysqli_insert_id($con);
			$_SESSION['id']=$id;
			header("Location:Dashboard/index.php?msg=Registration successfull");
			exit();
		} else{
			$eroormsg = "Something is wrong Please try again";
		}

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
				<form method="post" action="#" class="login100-form validate-form p-l-55 p-r-55 p-t-110" >
					<span class="login100-form-title">
						Sign Up
					</span>
					<?php if(isset($eroormsg)){ echo '<h6 id="eroormsg">'.$eroormsg.'</h6>'; } ?>
					<div class="wrap-input100 validate-input m-b-16" data-validate="Please enter username" >
						<input class="input100" type="text" name="username" placeholder="Username" maxlength="20" onKeyPress="return ValidateAlpha(event);"  ondrop="return false;" onpaste="return false;">
						<span class="focus-input100"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-16" data-validate="Please enter Email">
						<input class="input100" type="email" name="email" placeholder="Email">
						<span class="focus-input100"></span>
					</div>    

					<div class="wrap-input100 validate-input m-b-16" data-validate = "Please enter password">
						<input class="input100" type="password" name="pass" placeholder="Password" maxlength="20">
						<span class="focus-input100"></span>
					</div>

                    <div class="wrap-input100 validate-input m-b-16" data-validate="Please enter Mobile No">
						<input class="input100" type="text" name="phone" pattern="^\d{10}$" title="10 digit mobile number" placeholder="Enter 10 digit phone number" maxlength="10"  onkeypress="return isNumberKey(event)" ondrop="return false;" onpaste="return false;"  minlength="10" >
						<span class="focus-input100"></span>
					</div>  

					<div class="container-login100-form-btn">
						<input class="login100-form-btn" type="submit" name="submit" value="Registration">
					</div>

					<div class="flex-col-c p-t-25 p-b-40">
						<span class="txt1 p-b-9">
							You have an account?
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

	<script type="text/javascript">
	function ValidateAlpha(evt)
	{
		var keyCode = (evt.which) ? evt.which : evt.keyCode
		if ((keyCode < 65 || keyCode > 90) && (keyCode < 97 || keyCode > 123) && keyCode != 32)
		
		return false;
		return true;
	}
	function isNumberKey(evt)
	{  
		var charCode = (evt.which) ? evt.which : evt.keyCode
		if (charCode < 48 || charCode > 57)
			return false;
			return true;
    }
	</script>

</body>
</html>

