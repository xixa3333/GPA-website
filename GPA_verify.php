<?php
	include("db_connect.php");
	
	$sql_str = "SELECT * FROM `account` WHERE `token`='".$_GET['token']."';";
	$res = @mysqli_query($conn, $sql_str);
	
	if(mysqli_num_rows($res)==0 || !$res){
		echo '<script>alert("驗證失敗");location.href = "GPA_login.php";</script>';
		exit();
	}
	
	$row_array = mysqli_fetch_assoc($res);
	$manage = $row_array['manage'];
	$newaddress = $row_array['newaddress'];
	$address = $row_array['address'];
	
	if(isset($newaddress)){//更改電子郵件
		$sql_str = "UPDATE `account` SET `address`='$newaddress',`newaddress`='NULL' WHERE `token`='".$_GET['token']."';";
		mysqli_query($conn, $sql_str);
		echo '<script>alert("驗證成功");location.href = "GPA.php";</script>';
		exit();
	}
	
	//判斷是否驗證過或為管理員或學生
	if($manage==-1)$manage=0;
	else if($manage==-2)$manage=1;
	else {
		echo '<script>alert("已經驗證過了");location.href = "GPA_login.php";</script>';
		exit();
	}
	
	$sql_str = "UPDATE `account` SET `manage`='$manage' WHERE `token`='".$_GET['token']."';";
	mysqli_query($conn, $sql_str);
	
	echo '<script>alert("驗證成功");location.href = "GPA_login.php";</script>';
	mysqli_close($conn);
?>