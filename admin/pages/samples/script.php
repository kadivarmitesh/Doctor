<?php
require "../../../config.php";
$string = $_POST['data']; 
$str_arr = explode (",", $string);  

$i=1;
$updatedate = date('y-m-d h:i:s');
foreach($str_arr as $d)
{
    $d=str_replace("["," ",$d);
    $d=str_replace("]"," ",$d);
    $update = "UPDATE `disease_tbl` SET `orderby`='".$i."',`updatedate`='".$updatedate."' WHERE `id`=$d";
    if(mysqli_query($con, $update))
    {
        $successmsg = "Disease moved";
    }
    else{
        $eroormsg = "Something wrong Please try again";
    }
    $i+=1;
  
}

?>