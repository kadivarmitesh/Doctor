<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}
$appointment_id=$_POST['appointment_id'];
$symptoms=$_POST['symptoms'];
$createddate = date('y-m-d h:i:s');
 	$sql = "INSERT INTO `tbl_symtoms`(`appointment_id`, `symtoms`, `createdate`) VALUES ($appointment_id,'".$symptoms."','".$createddate."')";
    if(mysqli_query($con, $sql))
    {
        echo json_encode(array("statusCode"=>200));
    }
    else{
        echo json_encode(array("statusCode"=>201));
    }
?>