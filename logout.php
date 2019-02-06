<?php
	session_start();
	session_destroy();
	if ($_SESSION['log_client'] =="vendor") {
		header("location:vendors");
	} else {
		header("location:index.php");
	}
?>