<?php

	$fp=fopen("account.txt", "r");
	$data=fgets($fp);
	$data=explode(",",$data);
	fclose($fp);
	
	header("Content-Type: text/html; charset=utf-8");

	$db_host = "203.64.95.42";
	$db_access=@$data[0];
	$db_password =@$data[1];
	$database=@$data[0];
	
	
	$conn = @mysqli_connect($db_host, $db_access, $db_password);
	
	if(!$conn){
		 echo '<p align="center">帳號密碼錯誤</p>';
		 echo '<form align="center" action="GPA_login.php"><input type="submit" value="重新登入"/></form>';
		 die();
	}
	
	// 設定字元集與連線校對
	mysqli_query($conn, "SET NAMES 'utf-8'");
	mysqli_query($conn, "SET CHARACTERS SET 'utf-8'");
	
	@mysqli_select_db($conn, $database) or die ("資料庫選擇失敗");
?>