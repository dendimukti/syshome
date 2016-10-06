<?php
	
	error_reporting(0);
	include "conn.php";
	session_start();
	$session = session_id();
	$logged=false;
	if(!empty($_SESSION['ID'])){
		$logged=true;
	}


?>
