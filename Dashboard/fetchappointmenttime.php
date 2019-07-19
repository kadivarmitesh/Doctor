<?php
session_start();
require '../config.php';
header("Content-type:application/json"); 
if(!isset($_SESSION['id']))
{
    header("Location:../index.php?msg=Please login first");
}
if (isset($_POST["post_id"]) && isset($_POST["post_id"]) != '') {       
    $appid = $_POST["post_id"];
    

    $sql = "SELECT * FROM `apptime_tbl` WHERE `status`= 1 AND `id` = $appid";
    $res = mysqli_query($con,$sql);
   
		$row=mysqli_fetch_assoc($res);
        $starttime = $row['start-time'];
        $endtime = $row['end-time'];
        echo $starttime." ".$endtime;
        //print(json_encode($row, JSON_PRETTY_PRINT));

}

    
	
?>