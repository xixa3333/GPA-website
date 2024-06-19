<?
include("db_connect.php");
session_start();

include("Send_letter.php");

$manage=0;
if (!(!isset($_SESSION["user"]) || $_SESSION["user"] == "")){//有登入時判斷成功
	$sql_str = "SELECT * FROM `account` WHERE `user` = '".$_SESSION["user"]."';";
	$res = mysqli_query($conn, $sql_str);
	$row_array = mysqli_fetch_assoc($res);
	$manage = $row_array['manage'];
	
	if($manage==0){//只有管理員能不被跳回去
		header("Location: GPA.php");
		exit();
	}
}

if (isset($_POST["account"]) && isset($_POST["password"]) && isset($_POST["confirm"])) {
	
	$account=preg_replace('/\s/', '', trim($_POST["account"]));
	$account = mysqli_real_escape_string($conn, $_POST['account']);
	$password=preg_replace('/\s/', '', trim($_POST["password"]));
	$confirm=preg_replace('/\s/', '', trim($_POST["confirm"]));
	
	if($manage==0)$manage2=-1;
	else $manage2=-2;
	
	$Revise_Time = date("Y-m-d");
	
	if($password!=$confirm){
		echo '<script>alert("密碼輸入錯誤");location.href = "GPA_forget.php";</script>';
		exit();
	}
	
	$sql_str = "SELECT * FROM `account` WHERE `user`='$account';";
	$res = @mysqli_query($conn, $sql_str);
	
	if(mysqli_num_rows($res)!=0){
		echo '<script>alert("帳號重複");location.href = "GPA_register.php";</script>';
		exit();
	}
		
	$token = bin2hex(random_bytes(16));
	
	$sql_str = "SELECT * FROM `account` WHERE `address`='".$_POST['address']."';";
	$res = @mysqli_query($conn, $sql_str);
	if(mysqli_num_rows($res) != 0){
		echo '<script>alert("此電子郵件已被綁定，請重新輸入");location.href = "GPA_register.php";</script>';
		exit();
	}
	
	sendPasswordResetEmail($_POST['address'], "GPA與學期成績網站驗證", "歡迎使用GPA與學期成績網站，請驗證帳號:http://203.64.95.42/C112151111/GPA_verify.php?token=$token ", '<script>alert("電子郵件輸入錯誤，請重新輸入");location.href = "GPA_register.php";</script>');
	
	$time=date("Y-m-d H:i:s");
	$password=password_hash($password, PASSWORD_DEFAULT);
	$sql_str = "INSERT INTO `account` (`user`, `password`,`Revise_Time`,`manage`,`error_passwords`,`login_time`,`address`,`token`,`Revise_password`) 
		VALUES ('$account', '$password','$Revise_Time','$manage2','0','$time','".$_POST["address"]."','$token','0')";
	$res = @mysqli_query($conn, $sql_str);
		
	if($manage==0)echo '<script>alert("註冊成功，請前往你的郵箱收驗證信");location.href = "GPA_login.php";</script>';
	else echo '<script>alert("註冊成功，請前往你的郵箱收驗證信");location.href = "GPA_login.php?logout=true";</script>';//註冊管理員帳號
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
	<head>
<body style="background-color: rgb(230,230,230);">
<img src="wallpaper.png" class="back">
	<div class="cen">
	<div class="item3">註冊帳號</div>
	<div class="item2">
	
	<form method="POST">
	<p></p>
		<input type="text" placeholder="帳號" name="account" required size="20" />
		<br></br>
		<input type="text" placeholder="電子郵件" name="address" required size="20" />
		<br></br>
		<div class="box">
			<input type="password" id="psw" placeholder="密碼" name="password" required size="20"/>
			<img src="https://cdn-icons-png.flaticon.com/512/2767/2767146.png" alt="" id="eye"></img>
		</div>
		<br/>
		<div class="box">
			<input type="password" id="psw2" placeholder="確認密碼" name="confirm" required size="20"/>
			<img src="https://cdn-icons-png.flaticon.com/512/2767/2767146.png" alt="" id="eye2"></img>
		</div>
		<p>
		<input type="submit" value="註冊"/>
		<br></br>
		<?if($manage==0){?>
		<input type="button" onclick="javascript:location.href='GPA_login.php'" value="回到主畫面">
		<?}if($manage==1){?>
		<input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
		<?}?>
	</form>
	</div>
	</div>
</img>
<script src="eye.js"></script>
</body>
</html>