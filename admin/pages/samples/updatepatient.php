<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}
    $patientid=$_POST['patientid'];
    $firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$email=$_POST['email'];
	$birthdate=$_POST['birthdate'];
    $disease=$_POST['disease'];
    $mobileno=$_POST['mobileno'];
	$appdate=$_POST['appdate'];
	$apptime=$_POST['apptime'];
    $description=$_POST['description'];
    $status=$_POST['status'];
    
    $updatedate = date('y-m-d h:i:s');

    $adate = strtotime($appdate);
    $timestamp = strtotime($birthdate);
    
    $appointmentdate = date("Y-m-d", $adate);

    $adminid = $_SESSION['id'];
   
    $dob = date("Y-m-d", $timestamp);
    
    $sql = "UPDATE `patinet_tbl` SET `firstname`='".$firstname."',`lastname`='".$lastname."',`diseaseid`=$disease,`dob`='".$dob."',`mobileno`='".$mobileno."',`email`='".$email."',`description`='".$description."',`appointmentdate`='".$appointmentdate."',`appointmenttime`=$apptime,`updatedate`='".$updatedate."',`adminid`=$adminid,`status`='".$status."' WHERE `id`=$patientid";

    if(mysqli_query($con, $sql))
    {
        echo json_encode(array("statusCode"=>200));
    }
    else{
        echo json_encode(array("statusCode"=>201));
    }

?>