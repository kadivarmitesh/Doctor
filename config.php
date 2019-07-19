<?php

$host = 'localhost';
$user = 'root';
$pass = '';

$con = mysqli_connect($host, $user, $pass) or die("MySQL Error");
mysqli_select_db($con,"doctor_consult");

?>