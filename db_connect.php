<?php
	header("Content-Type: text/html; charset=utf-8");

	$db_host = "127.0.0.1";
	$db_access="root";
	$db_password = "";
	$database="C112151111";
	$conn = @mysqli_connect($db_host, $db_access, $db_password) or die ("連線錯誤");
	
	mysqli_select_db($conn,$database) or die ("資料庫選擇失敗");
	
	// 設定字元集與連線校對
	mysqli_query($conn, "SET NAMES 'utf8'");
?>