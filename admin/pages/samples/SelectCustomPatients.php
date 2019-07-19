<?php
session_start();
require '../../../config.php';
if(!isset($_SESSION['id']))
{
    header("Location: login.php?msg=Please login first");
}

$fromdate=$_POST['fromdate'];
$dateto=$_POST['dateto'];
$PatientName=$_POST['PatientName'];
$SearchingDisease=$_POST['SearchingDisease'];

    $fromdate = strtotime($_POST['fromdate']);
    if($fromdate!="")
    {
        $fromdate = date("Y-m-d", $fromdate);
    }

    $dateto = strtotime($_POST['dateto']);
    if($dateto!="")
    {
        $dateto = date("Y-m-d", $dateto);
    }

    $sql = "CALL `sp_Admindashboard`('".strval($fromdate)."','".strval($dateto)."','".strval($PatientName)."','".$SearchingDisease."')";

$result = mysqli_query($con, $sql);
if (mysqli_num_rows($result) > 0) {
    $no=1;
    $data['status'] = 'ok';
    $tablebody = "";
    while ($row=mysqli_fetch_assoc($result)) {
        $tablebody .= "<tr>"; 
        $tablebody .= "<td>". $no ."</td>" ;            
        $tablebody .= "<td>". $row['firstname']." ".$row['lastname'] ."</td>";
        $tablebody .= "<td>". $row['mobileno']."</td>";
        $tablebody .= "<td>" .$row['disease']. "</td>";
        $adate = strtotime($row['appointment_date']);
        $appointmentdate = date("d-m-Y", $adate);
        $tablebody .= "<td>" .$appointmentdate. "</td>";

        $bday = new DateTime($row['dob']); 
        $today = new Datetime(date('y-m-d'));
        $diff = $today->diff($bday);
        $age = $diff->y;

        $starttime  = date("g:i A", strtotime($row['appointment_starttime']));
        $sttime = str_replace(" ","=",$starttime);
        $endtime  = date("g:i A", strtotime($row['appointment_endtime']));
        $edtime = str_replace(" ","=",$endtime);

        $tablebody .= "<td>" .$age. " years </td>";
        $tablebody .= "<td>" .$row['status']. "</td>";
        $disease = str_replace(" ","=",$row['disease']);
        $description = str_replace(" ","=",$row['description']);

        $onClick =  "AppointmentDetails(".$row['appointment_id'].",'".$row['firstname']."','".$row['lastname']."',".$age.",'".$row['mobileno']."','".$row['email']."','".$disease."','".$appointmentdate."','".$row['status']."','".$sttime."','".$edtime."','".$description."')";
        
        $tablebody .= "<td><button type='button' class='btn btn-desc-icon btn-gradient-success btn-rounded' onclick=".$onClick." data-toggle='tooltip' class='tip-bottom' title='View Details'><i class='mdi mdi-eye'></i></button></td>";
        $tablebody .= "<td><a href='#myModaldelete' onclick='DeleteRow(".$row['appointment_id'].")'  class='trigger-btn' data-toggle='modal' id='delete-Time' data-toggle='tooltip' class='tip-bottom' title='Delete Appointment' style='font-size: 20px;'><i class='mdi mdi-delete'></i></a></br>";
        if($row['status'] != "Cancelled"){
            $tablebody .= "<a href='#Modalcancelapp' onclick='CancelRow(".$row['appointment_id'].")'  class='btn btn-gradient-success btn-sm' data-toggle='modal' data-toggle='tooltip' class='tip-bottom' title='Cancel Appointment' >Cancel</a></td>";
        }
        $tablebody .= "<td><button type='button' class='btn btn-gradient-info btn-sm' data-toggle='modal' data-target='#FollowupModal' class='tip-bottom' title='follow up' onclick='FollowupAppointment(".$row['appointment_id'].")'>Follow Up</button></td>";
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