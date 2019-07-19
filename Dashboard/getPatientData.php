<?php
session_start();
require '../config.php';
header("Content-type:application/json"); 
if(!isset($_SESSION['id']))
{
    header("Location:../index.php?msg=Please login first");
}

if (isset($_POST["pid"]) && isset($_POST["pid"]) != '') {       
    $appointmentid = $_POST["pid"];

    $data = array();
    //get user data from the database
    $sql = "CALL sp_FetchFollowupuser($appointmentid)";
    $res = mysqli_query($con,$sql);
    
    if(mysqli_num_rows($res)>0)
    {
        $userData=mysqli_fetch_assoc($res);
       
        $data['status'] = 'ok';
        $data['result'] = $userData;
    }else{
        $data['status'] = 'err';
        $data['result'] = '';
    }
    
    //returns data as JSON format
    echo json_encode($data);
}
?>