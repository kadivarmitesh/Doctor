<?php
session_start();
require '../config.php';
if(!isset($_SESSION['id']))
{
    header("Location:../index.php?msg=Please login first");
}
if(isset($_GET['appointmentdt']))
{
    $data = array();
    $appointmentdt = $_GET['appointmentdt'];
    
    $adate = strtotime($appointmentdt);
    
    $appointmentdate = date("Y-m-d", $adate);
    
    $sql = "CALL sp_FetchAppoTime('$appointmentdate')";
    
    $res = mysqli_query($con,$sql);
    
    if(mysqli_num_rows($res)>0)
    {
        while ($userData=mysqli_fetch_assoc($res)) {
            $output[]=$userData;
        }
       
    }
    
    //returns data as JSON format
    echo json_encode($output);


}

    

?>