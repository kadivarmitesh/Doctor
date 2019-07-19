<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}

$sql = "DELETE FROM `tbl_prescription` WHERE `id`=".$_POST["id"];

if (mysqli_query($con, $sql)) {
    $returnData = array(
        'status' => 'ok',
        'msg' => 'User data has been deleted successfully.'
    );
}else{
    $returnData = array(
        'status' => 'error',
        'msg' => 'Some problem occurred, please try again.'
    );
}
echo json_encode($returnData);

?>