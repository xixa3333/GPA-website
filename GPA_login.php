<?php
	include("db_connect.php");
	session_start();
	$file_name = $_SERVER['PHP_SELF'];
	$login=0;
	$date = strtotime(date("Y-m-d 23:59:59"));
	
	// 登出或超時
	if (isset($_GET["logout"]) && $_GET["logout"] == "true") {
		session_unset();
		session_destroy();
		header("Location: $file_name");
		exit();
	}
	
	//已經登入
	if (!(!isset($_SESSION["user"]) || $_SESSION["user"] == "")){
		header("Location: GPA.php");
		exit();
	}
	
	if (isset($_POST["account"]) && isset($_POST["password"])) {//已輸入帳密
	
		$account=preg_replace('/\s/', '', trim($_POST["account"]));
		$account = mysqli_real_escape_string($conn, $_POST['account']);
		$password2=preg_replace('/\s/', '', trim($_POST["password"]));
		
		$sql_str = "SELECT * FROM `account` WHERE `user`='$account';";
		$res = @mysqli_query($conn, $sql_str);
		
		if(mysqli_num_rows($res)==0){
			echo '<script>alert("帳號輸入錯誤，請重新輸入");location.href = "GPA_login.php";</script>';
			exit();
		}
		
		$row_array = mysqli_fetch_assoc($res);
		
		$password = $row_array['password'];
		$Revise_Time = $row_array['Revise_Time'];
		$manage = $row_array['manage'];
		$error_passwords = $row_array['error_passwords'];
		$login_time = $row_array['login_time'];
		$runtime =  strtotime($login_time) - time();//抓取被鎖住的時間
		
		if($manage<0){//驗證未完成
			echo '<script>alert("請去驗證");location.href = "GPA_login.php";</script>';
			exit();
		}
		
		if(!($runtime<=0 || $error_passwords%3!=0)){//帳號被鎖住
			echo "<script>alert('密碼錯誤已達"."$error_passwords"."次，請"."$runtime"."秒後再嘗試登入');location.href = 'GPA_login.php';</script>";
			exit();
		}
		
		if($runtime<=-24*60*60)$error_passwords=0;//超過一天時重置密碼錯誤次數
		
		if(password_verify($password2, $password)){//判斷密碼
						
			$_SESSION["user"] = $account;
			$_SESSION['expiretime'] = time() + 60*60;
						
			$error_passwords=0;
			$login_time=date("Y-m-d H:i:s");
			$sql_str = "UPDATE `account` 
				SET `error_passwords`='$error_passwords', `login_time`='$login_time' ,`Revise_password`='0'
				WHERE `user`='$account';";
			@mysqli_query($conn, $sql_str);//重置密碼錯誤次數
						
			//如果是管理員則放第一個學生帳號為預設
			if($manage==1){
				$sql_str = "SELECT * FROM `account` WHERE `manage`='0' LIMIT 1;";
				$res = mysqli_query($conn, $sql_str);
				$row_array = mysqli_fetch_assoc($res);
				setcookie("account[user]",$row_array['user'],$date);
			}
			else setcookie("account[user]",$account,$date);
			
			setcookie("account[year]",'112up',$date);
			setcookie("account[sort]",'Required_elective',$date);
			setcookie("account[order]",'asc',$date);
			setcookie("account[GPA_sort]",'NKUST',$date);
						
			// 計算修改密碼的天數
			$date2=date("Y-m-d");
			$dateTime1 = new DateTime($Revise_Time);
			$dateTime2 = new DateTime($date2);
			$interval = $dateTime1->diff($dateTime2);
			$daysDifference = $interval->days;
			
			if($daysDifference>7)echo "<script>alert('你已經"."$daysDifference"."天沒更新密碼了，記得定期更新呦');</script>";
			echo "<script>location.href = 'GPA.php';</script>";
			exit();
		}
		
		#密碼輸入錯誤
		$error_passwords+=1;
		$login_time=date("Y-m-d H:i:s",(time()+3*60*ceil($error_passwords/3)));//錯誤次數越多等待時間越長
		$sql_str = "UPDATE `account` 
			SET `error_passwords`='$error_passwords', `login_time`='$login_time' 
				WHERE `user`='$account';";
		@mysqli_query($conn, $sql_str);
		echo "<script>alert('密碼輸入錯誤"."$error_passwords"."次，請重新輸入，每錯誤3次會鎖住');</script>";
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
	<form method="POST">
	<br></br>
	
		<input type="text" placeholder="帳號" name="account" required />
		
		<br></br>
			<div class="box">
			<input type="password" id="psw" placeholder="密碼" name="password" required size="20"/>
			<img src="https://cdn-icons-png.flaticon.com/512/2767/2767146.png" alt="" id="eye"></img>
			</div>
		<p/>
		<input type="submit" value="登入"/>
		<p/>
		<a href='GPA_account.php' style="font-size:13px;">忘記密碼?</a>
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