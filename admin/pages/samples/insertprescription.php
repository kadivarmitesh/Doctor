<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}
    $appointment_id=$_POST['appointment_id'];
	$prescription=$_POST['prescription'];
    $createddate = date('y-m-d h:i:s');

    $sql = "INSERT INTO `tbl_prescription`(`appointment_id`, `prescription`, `createdate`) VALUES ($appointment_id,'".$prescription."','".$createddate."')";
    if(mysqli_query($con, $sql))
    {
        $id= mysqli_insert_id($con);
        $qry = "SELECT *, DATE(createdate) as predate FROM `tbl_prescription` WHERE `id`=".$id;
        $result = mysqli_query($con, $qry);
       
        if (mysqli_num_rows($result) > 0) {
            $userData=mysqli_fetch_assoc($result);
           
            $data['status'] = 'ok';
            $data['result'] = $userData;
        }
        else{
            $data['status'] = 'err';
            $data['result'] = '';
        }
        //returns data as JSON format
        echo json_encode($data);
    }
    
  
?>