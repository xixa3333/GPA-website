<?php
include("db_connect.php");
session_start();

if(isset($_GET['token'])){
		$sql_str = "SELECT * FROM `account` WHERE `token` = '".$_GET['token']."';";
		
		$res = mysqli_query($conn, $sql_str);
		if(mysqli_num_rows($res)==0 || !$res){
			echo '<script>location.href = "GPA_login.php";</script>';
			exit();//當取到錯誤的token時退出
		}
		$row_array = mysqli_fetch_assoc($res);
		$manage = $row_array['manage'];
		$Revise_password = $row_array['Revise_password'];
		
		if($manage<0){
			echo '<script>alert("帳號未驗證");location.href = "GPA_login.php";</script>';
			exit();
		}
		if($Revise_password==0){
			echo '<script>location.href = "GPA_login.php";</script>';
			exit();
		}
	}
	
	if(isset($_POST['token'])){
		$sql_str = "SELECT * FROM `account` WHERE `token` = '".$_POST['token']."';";
		$res = mysqli_query($conn, $sql_str);
		if(mysqli_num_rows($res)==0 || !$res){
			echo '<script>location.href = "GPA_login.php";</script>';
			exit();//當隱藏傳值取到錯誤的token時退出
		}
		$row_array = mysqli_fetch_assoc($res);
		$account = $row_array['user'];
		$password = $row_array['password'];
	}
	
	if(isset($_SESSION["user"])){//判斷從主頁面進來的
		$sql_str = "SELECT * FROM `account` WHERE `user` = '".$_SESSION["user"]."';";
		$res = mysqli_query($conn, $sql_str);
		$row_array = mysqli_fetch_assoc($res);
		$account = $_SESSION["user"];
		$password = $row_array['password'];
	}
	
	if (isset($account) && isset($_POST["password"]) && isset($_POST["confirm"])) {
		
		$password2=preg_replace('/\s/', '', trim($_POST["password"]));
		$confirm=preg_replace('/\s/', '', trim($_POST["confirm"]));
		
		if($password2!=$confirm){
			if((!isset($_SESSION["user"]) || $_SESSION["user"] == "") && isset($_POST['token']))
				echo '<script>alert("密碼輸入錯誤");location.href = "GPA_forget.php?token='.$_POST['token'].'";</script>';
			else echo '<script>alert("密碼輸入錯誤");location.href = "GPA_forget.php";</script>';
			exit();
		}
		
		if(password_verify($password2, $password)){
			if((!isset($_SESSION["user"]) || $_SESSION["user"] == "") && isset($_POST['token']))
				echo '<script>alert("密碼修改失敗，你輸入的是舊密碼");location.href = "GPA_forget.php?token='.$_POST['token'].'";</script>';
			else echo '<script>alert("密碼修改失敗，你輸入的是舊密碼");location.href = "GPA_forget.php";</script>';
			exit();
		}
		
		$password2=password_hash($password2, PASSWORD_DEFAULT);
		
		$Revise_Time = date("Y-m-d");
		$sql_str = "UPDATE `account` SET `password`='$password2',`Revise_Time`='$Revise_Time',`Revise_password`='0' WHERE `user`='$account';";
		mysqli_query($conn, $sql_str);
		echo '<script>alert("密碼修改成功");</script>';
		if (!isset($_SESSION["user"]) || $_SESSION["user"] == "")echo '<script>location.href = "GPA_login.php";</script>';
		else echo '<script>location.href = "GPA.php";</script>';
		exit();
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
	</head>
<body style="background-color: rgb(230,230,230);">
<img src="wallpaper.png" class="back">
	<div class="cen">
	<div class="item3">修改密碼</div>
	<div class="item2">
	
	<form method="POST">
	<p></p>
		<?php if((!isset($_SESSION["user"]) || $_SESSION["user"] == "") && isset($_GET['token'])){?>
		<input type="hidden" name="token" value="<?php echo $_GET['token']; ?>"/>
		<?php }?>
		
		<div class="box">
			<input type="password" id="psw" placeholder="修改密碼" name="password" required size="20"/>
			<img src="https://cdn-icons-png.flaticon.com/512/2767/2767146.png" alt="" id="eye"></img>
		</div>
		<br/>
		<div class="box">
			<input type="password" id="psw2" placeholder="確認密碼" name="confirm" required size="20"/>
			<img src="https://cdn-icons-png.flaticon.com/512/2767/2767146.png" alt="" id="eye2"></img>
		</div>
		<p/>
		<input type="submit" value="修改密碼"/>
		<br></br>
		<?php if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){?>
			<input type="button" onclick="javascript:location.href='GPA_login.php'" value="回到主畫面"/>
		<?php }
		if (isset($_SESSION["user"])){?>
			<input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面"/>
		<?php }?>
		
		
	</form>
	</div>
	</div>
</img>
<script src="eye.js"></script>
</body>
</html>