<?php
session_start();
require '../config.php';
if(!isset($_SESSION['id']))
{
    header("Location:../index.php?msg=Please login first");
}

if(isset($_GET['id']))
{
    $sql = "UPDATE `tbl_appointment` SET `isPatient_cancelled`= 1, `status`='Cancelled' WHERE `appointment_id`=".$_GET['id'];
     
    if(mysqli_query($con, $sql))
    {
        echo 'Deleted successfully.';
    }
    else{
        echo "Something wrong Please try again";
    }
}


?>