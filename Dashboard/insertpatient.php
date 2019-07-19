<?php
session_start();
require '../config.php';
if(!isset($_SESSION['id']))
{
    header("Location:../index.php?msg=Please login first");
}
    
    $followupid=$_POST['followupid'];
    $Appointmentdate=$_POST['Appointmentdate'];
    $Appointmenttime=$_POST['Appointmenttime'];
    $firstname=$_POST['firstname'];
	$lastname=$_POST['lastname'];
	$email=$_POST['email'];
	$birthdate=$_POST['birthdate'];
    $disease=$_POST['disease'];
    $mobileno=$_POST['mobileno'];
    $discription=$_POST['discription'];
    $status = "Pending";
    
    $createddate = date('y-m-d h:i:s');

    $adate = strtotime($Appointmentdate);
    
    $appointmentdate = date("Y-m-d", $adate);

    $userid = $_SESSION['id'];
    $timestamp = strtotime($birthdate);
    $dob = date("Y-m-d", $timestamp);
    
    $FinalDate=explode("to",$Appointmenttime);
    $StartTime=$FinalDate[0];
    $EndTime=$FinalDate[1];

    $sttime  = date("H:i", strtotime($StartTime));
    $edtime  = date("H:i", strtotime($EndTime));
    
   
    if($followupid != null)
    {
        $sql = "CALL sp_bookappointment($userid,
        '".strval($firstname)."',
        '".strval($lastname)."',
        '".strval($email)."',
        '".strval($dob)."',
        '".strval($mobileno)."',
        '".strval($appointmentdate)."',
        '".strval($sttime)."',
        '".strval($edtime)."',
        ".strval($disease).",
        '".strval($discription)."',
        $followupid)";
    }
    else
    {
        $sql = "CALL sp_bookappointment($userid,
        '".strval($firstname)."',
        '".strval($lastname)."',
        '".strval($email)."',
        '".strval($dob)."',
        '".strval($mobileno)."',
        '".strval($appointmentdate)."',
        '".strval($sttime)."',
        '".strval($edtime)."',
        ".strval($disease).",
        '".strval($discription)."',
        NULL)";
    }
    
    if(mysqli_query($con, $sql))
    {
        require("../phpmailer/class.phpmailer.php");

        $mess   = '<p>Hi '.$firstname.' '.$lastname.', <br><br>
                Your Appointment is booked for '.$Appointmentdate.' and '.$Appointmenttime.' <br><br>
                Please stay on call at above specific date and time. <br><br>
                Thanks,<br>
                AyurnatureCare 
                </p>';

        $mail = new PHPMailer;

        $mail->IsSMTP();                                      
        $mail->Host = "mail.overseasits.com";                 
        
        $mail->Port = 587;                                    
        $mail->SMTPAuth = true;                               
        $mail->Username = "mitesh.kadivar@overseasits.com";   
        $mail->Password = "mitesh@123";                  
        

        $mail->From = 'mitesh.kadivar@overseasits.com';
        $mail->FromName = 'Online Consult Doctor - Appointment Booked';

        
        $mail->AddAddress($email);  
        $mail->IsHTML(true);                                  
        $mail->Subject = 'Thank You for Appointment Booked';
        $mail->Body = $mess;
        
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';


        if(!$mail->Send()) {
            echo "Mailer Error: " . $mail->ErrorInfo;
        } else {
            $successmsg = "Message has been sent check your email";
        }    
        echo json_encode(array("statusCode"=>200));
    }
    else{
        echo json_encode(array("statusCode"=>201));
    }

?>