<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}

$appointmentID =$_POST['appointmentID'];

$sql = "SELECT *, DATE(createdate) FROM `tbl_prescription` WHERE `appointment_id`=".$appointmentID;

$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    $no=1;
    $data['status'] = 'ok';
    $tablebody = "";
    while ($row=mysqli_fetch_assoc($result)) {
        $tablebody .= "<tr id='rowid-".$row['id']."'>"; 
        $tablebody .= "<td>". $no ."</td>" ;
        $adate = strtotime($row['DATE(createdate)']);
        $createddt = date("d-m-Y", $adate);  
        $tablebody .= "<td>" .$createddt. "</td>";
        $tablebody .= "<td> <span class='editSpan prescription' id='editSpandata-".$row['id']."'>".$row['prescription']."</span><input class='editInput prescriptionchange form-control input-sm' type='text' id='prescriptioneditchange-".$row['id']."' name='prescriptioneditchange' value='' style='display: none;'></td>";
        $tablebody .= "<td><button type='button' class='btn btn-sm btn-default editBtn' id='editBtn-".$row['id']."' onclick='FunEditPrescription(".$row['id'].")' style='float: none;'><span class='mdi mdi-pencil'></span></button>
                <button type='button' class='btn btn-sm btn-success saveBtn' id='PresaveBtn-".$row['id']."' onclick='UpdatePresaveBtn(".$row['id'].")' style='float: none; display: none;'>Save</button>
                <button type='button' class='btn btn-sm btn-default deleteBtn' id='deleteButton-".$row['id']."' onclick='FunDeletePrescription(".$row['id'].")' style='float: none;'><span class='mdi mdi-delete'></span></button>
                <button type='button' class='btn btn-sm btn-danger confirmBtn' id='confirmdeleteButton-".$row['id']."' onclick='confirmDelPrescription(".$row['id'].")' style='float: none; display: none;'>Confirm</button> </td>"; 
        $tablebody .= "</tr>";
        $no++;
    }
   
    $data['result'] = $tablebody;

}
else{
    $data['status'] = 'err';
    $data['result'] = '';
}

echo json_encode($data);
?>