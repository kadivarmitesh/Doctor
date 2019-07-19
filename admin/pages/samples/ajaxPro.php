<?php 

require "../../../config.php";
$position = $_POST['position'];
$updatedate = date('y-m-d h:i:s');

$i=1;
foreach($position as $k=>$v){

    $update = "UPDATE `tbl_disease` SET `orderby`='".$i."',`updatedate`='".$updatedate."' WHERE `id`=$v";
    if(mysqli_query($con, $update))
    {
        $successmsg = "Disease moved";
    }
    else{
        $eroormsg = "Something wrong Please try again";
    }

	$i++;
}


?>