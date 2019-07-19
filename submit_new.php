<?php
require 'config.php';
if(isset($_POST['submit_password']) && $_POST['email'])
{
  $email=$_POST['email'];
  $pass=$_POST['pass'];
  $confirmpass=$_POST['confirmpass'];

    if($pass==$confirmpass)
    {
        $password = MD5($pass);
        $sql = "UPDATE `tbl_user` SET `password`='".$password."' WHERE `email`='".$email."'";
       
        $res = mysqli_query($con,$sql);
       
        if($res == 1)
        {
            header("Location: index.php?msg=Password Updated sucessfully");
            exit();
        }  
        
    }
    else
    {
        header("Location: reset_pass.php?msg=Password and confirm password are not same");
    }
}
?>