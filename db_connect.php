<?php
	header("Content-Type: text/html; charset=utf-8");

	$db_host = "203.64.95.42";
	$db_access='C112151111';
	$db_password ='1111xixa1111';
	$database='C112151111';
	
	
	$conn = @mysqli_connect($db_host, $db_access, $db_password) or die('連線錯誤');
	
	// 設定字元集與連線校對
	mysqli_query($conn, "SET NAMES 'utf-8'");
	mysqli_query($conn, "SET CHARACTERS SET 'utf-8'");
	
	@mysqli_select_db($conn, $database) or die ("資料庫選擇失敗");
?>