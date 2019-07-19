<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}


$PatientName=$_POST['PatientName'];
//SELECT * FROM tbl_appointment,tbl_patient where tbl_appointment.patient_id=tbl_patient.id and tbl_patient.firstname like '%dipak%' GROUP by disease_id order by appointment_date desc
$sql = "CALL `sp_Admindashboard`( '',
        '',
        '".strval($PatientName)."',
        '')";
$result = mysqli_query($con, $sql);            
if (mysqli_num_rows($result) > 0) {
    $no=1;
    $data['status'] = 'ok';
    $tablebody = "<table class='table table-hover table-bordered'><thead class='thead-light'><tr><th>#</th><th>Patient name</th><th>Last Appointment date</th><th>Disease</th></tr></thead><tbody>";
    while ($row=mysqli_fetch_assoc($result)) {
        $tablebody .= "<tr>"; 
        $bdate = strtotime($row['dob']);
        $birthdate = date("d-m-Y", $bdate);
        $onClick =  "FollowupFetchAppointmentDetails(".$row['appointment_id'].",'".$row['firstname']."','".$row['lastname']."','".$row['email']."','".$birthdate."',".$row['diseaseid'].",'".$row['mobileno']."')";
        $tablebody .= "<td><div><label><input type='radio' id='pateintid-".$row['appointment_id']."' name='optradio'  style='opacity:1' value='".$row['appointment_id']."' onclick=".$onClick."></label></div></td>";
        $tablebody .= "<td>". $row['firstname']." ".$row['lastname'] ."</td>";
       
            $adate = strtotime($row['appointment_date']);
            $appointmentdate = date("d-m-Y", $adate);
        
        $tablebody .= "<td>". $appointmentdate ."</td>";
        $tablebody .= "<td>" .$row['disease']. "</td>";  
        $tablebody .= "</tr>";    

    }
    $tablebody .= "</tbody></table>"; 
    $data['result'] = $tablebody;
}
else{
    $data['status'] = 'err';
    $data['result'] = '';
}

echo json_encode($data);

?>