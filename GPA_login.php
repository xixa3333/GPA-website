<?php
	include("db_connect.php");
	session_start();
	$file_name = $_SERVER['PHP_SELF'];
	$login=0;
	
	// 登出或超時
	if (isset($_GET["logout"]) && $_GET["logout"] == "true") {
		session_unset();
		session_destroy();
		header("Location: $file_name");
		exit();
	}
	
	
	$date = date("Y-m-d 23:59:59");
	if (isset($_GET["account"]) && isset($_GET["password"])) {
		$sql_str = "SELECT * FROM `account`";
		$res = mysqli_query($conn, $sql_str);
		// 判斷是否登入成功
		while ($row_array = mysqli_fetch_assoc($res)){
			foreach ($row_array as $key => $item){
				if($key=='user')$account=$item;
				if($key=='password')$password=$item;
				if($key=='Revise_Time')$Revise_Time=$item;
			}
			if($_GET['account']==$account and $_GET['password']==$password){
				$login=1;
				break;
			}
		}
		if($login==1){
			// 計算修改密碼的天數
			$date2=date("Y-m-d");
			$dateTime1 = new DateTime($Revise_Time);
			$dateTime2 = new DateTime($date2);
			$interval = $dateTime1->diff($dateTime2);
			$daysDifference = $interval->days;
			
			$_SESSION["user"] = $_GET['account'];
			$_SESSION['expiretime'] = time() + 60*60;
			
			if($daysDifference>7)echo "<script>alert('你已經"."$daysDifference"."天沒更新密碼了，記得定期更新呦');</script>";
			echo "<script>location.href = 'GPA.php';</script>";
			exit();
		}
		else echo '<script>alert("帳號密碼錯誤，請重新輸入");</script>';
	}
	mysqli_close($conn);
?>
<html>
	<head>
		<title>GPA計算網站</title>
		<meta charset="utf-8">
		<link href="style.css" rel="stylesheet">
		<style>
			input[type="submit"],input[type="button"]{
				width:200px;
			}
			.item2 {
				background-color: rgb(255,255,255);
				width: 240px;
				padding: 5px;
			}
		</style>
	<head>
<body style="background-color: rgb(230,230,230);">
<img src="wallpaper.png" class="back">
	<div class="cen">
	<div class="item3">登入</div>
	<div class="item2">
	<form method="get">
	<br></br>
	
		<input type="text" placeholder="帳號" name="account" required />
		
		<br></br>
			<div class="box">
			<input type="password" id="psw" placeholder="密碼" name="password" required size="20"/>
			<img src="close.jpg" alt="" id="eye"></img>
			</div>
		<p/>
		<input type="submit" value="登入"/>
		<p/>
		<a href='GPA_forget.php' style="font-size:13px;">忘記密碼?</a>
		<hr>
		<p/>
		<input type="button" onclick="javascript:location.href='GPA_register.php'" value="註冊帳號">
	</form>
	</div>
	</div>
</img>
	<script src="eye.js"></script>
</body>
</html>