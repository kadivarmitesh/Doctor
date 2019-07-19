<?php
session_start();
require '../../../config.php';
if(isset($_SESSION['id']))
{
    header("Location: ../../index.php?msg=you have already login");
}
$eroormsg = "";
$user="";
if(isset($_POST['submit']))
{
	$email = $_POST['email'];
	$password = $_POST['password'];
	$pass = MD5($password);
  $type = "admin";
	
	$sql = "SELECT * FROM `tbl_user` WHERE `email`='".$email."' AND `type`='".$type."'";
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
        header("Location: ../../index.php?msg=Login successfull");
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
		$eroormsg = "Invalid Email Address";
	}

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Online Consult Doctor- Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="../../vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="../../vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="../../css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="../../images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <div class="brand-logo">
                <img src="../../images/loginlogo.jpg">
              </div>
              <h4>Hello! Admin</h4>
              <h6 class="font-weight-light">Sign in to continue Online consult doctor.</h6>
              <form  method="post" action="#"  class="pt-3">
                <?php if(isset($eroormsg)){ echo '<h6 id="eroormsg">'.$eroormsg.'</h6>'; } ?>						
                <div class="form-group">
                  <input type="email" class="form-control form-control-lg" autocomplete="off" name="email" placeholder="email" value="<?php if(isset($user)){ echo $user; } ?>">
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" autocomplete="off" name="password" placeholder="Password" maxlength="20">
                </div>
                <div class="mt-3">
                  <input class="btn btn-block btn-gradient-success btn-lg font-weight-medium auth-form-btn" type="submit" name="submit" value="SIGN IN">
                </div>
                <div class="my-2 d-flex justify-content-between align-items-center">
                  <div class="form-check">
                    <!-- <label class="form-check-label text-muted">
                      <input type="checkbox" class="form-check-input">
                      Keep me signed in
                    </label> -->
                  </div>
                  <!-- <a href="forgotpassword.php" class="auth-link text-black">Forgot password?</a> -->
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="../../vendors/js/vendor.bundle.base.js"></script>
  <script src="../../vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="../../js/off-canvas.js"></script>
  <script src="../../js/misc.js"></script>
  <!-- endinject -->
</body>

</html>
