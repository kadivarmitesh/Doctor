<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}

    $updatedate = date('y-m-d h:i:s');

    $sql = "UPDATE `tbl_prescription` SET `prescription`='".$_POST["prescriptionDetails"]."',`updatedate`='".$updatedate."' WHERE `id`=".$_POST["id"];
  
    if(mysqli_query($con, $sql))
    {
        $qry = "SELECT *, DATE(createdate) as predate FROM `tbl_prescription` WHERE `id`=".$_POST["id"];
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
       
        echo json_encode($data);
    }


?>