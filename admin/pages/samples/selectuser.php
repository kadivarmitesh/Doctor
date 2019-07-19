<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}

header("Content-type:application/json");  

//$sql = "SELECT *, user_tbl.email AS useremail, user_tbl.mobileno AS usermo, user_tbl.id AS User_ID, patinet_tbl.id AS Patient_ID FROM `user_tbl` LEFT JOIN patinet_tbl ON user_tbl.id= patinet_tbl.userid LEFT JOIN disease_tbl ON patinet_tbl.diseaseid = disease_tbl.id";
$sql = "SELECT *, tbl_patient.firstname,tbl_patient.lastname,tbl_patient.email AS patient_email, tbl_patient.mobileno AS patient_mo,tbl_patient.dob FROM `tbl_user` LEFT JOIN tbl_patient ON tbl_user.id=tbl_patient.userid WHERE tbl_user.type = 'user'";
$res = mysqli_query($con,$sql);

while ($row=mysqli_fetch_assoc($res)){
    $output[]=$row;
}
$data = json_encode($output, JSON_PRETTY_PRINT);
$data = str_replace('[','{ "data": [',$data);
$data = str_replace(']',' ]}',$data);
print($data);

?>