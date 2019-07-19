<?php

	session_start();
	require '../../../config.php';

	if (!isset($_GET)) {
		header("location:user.php?msg=Direct URL Called");
	}
	else{
		$id = $_GET['id'];

		$qry = "SELECT `status` FROM tbl_user WHERE id=$id";
		$res = mysqli_query($con, $qry);
		$row = mysqli_fetch_assoc($res);
		$stat = $row['status'];

		if ($stat == 1) {
			$qry = "UPDATE tbl_user SET `status`=2 WHERE id=$id";
			$res = mysqli_query($con, $qry);
			if (isset($res)) {
				header("location:user.php");
			}
			else{
				header("location:user.php?msg=Something Went Wrong... Plz Try Again!!!");
			}
		}else{
			$qry = "UPDATE tbl_user SET `status`=1 WHERE id=$id";
			$res = mysqli_query($con, $qry);
			if (isset($res)) {
				header("location:user.php");
			}
			else{
				header("location:user.php?msg=Something Went Wrong... Plz Try Again!!!");
			}
		}

	}

?>