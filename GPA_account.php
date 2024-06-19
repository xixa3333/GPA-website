<?
include("db_connect.php");
session_start();

if (!(!isset($_SESSION["user"]) || $_SESSION["user"] == "")){
	header("Location: GPA.php");
	exit();
}

include("Send_letter.php");

if (isset($_POST['account'])) {
	
	$account=preg_replace('/\s/', '', trim($_POST["account"]));
	$account = mysqli_real_escape_string($conn, $_POST['account']);
	
	$sql_str = "SELECT * FROM `account` WHERE `user`='$account';";
	$res = @mysqli_query($conn, $sql_str);
	
	if(mysqli_num_rows($res)==0){
		echo '<script>alert("帳號輸入錯誤，請重新輸入");location.href = "GPA_account.php";</script>';
		exit();
	}
	
	$row_array = mysqli_fetch_assoc($res);
	$token = $row_array['token'];
	$manage = $row_array['manage'];
	$address = $row_array['address'];
	
	if($manage<0){
		echo '<script>alert("帳號未驗證");location.href = "GPA_login.php";</script>';
		exit();
	}
	
	$sql_str = "UPDATE `account` SET `Revise_password`='1' WHERE `user`='$account';";
	mysqli_query($conn, $sql_str);
	
	sendPasswordResetEmail($address, "GPA與學期成績網站修改密碼", "歡迎使用GPA與學期成績網站，請至以下網址修改密碼:http://203.64.95.42/C112151111/GPA_forget.php?token=$token ", '<script>alert("電子郵件輸入錯誤，請重新輸入");location.href = "GPA_account.php";</script>');
	
	echo '<script>alert("驗證成功，請前往你的郵箱修改密碼");location.href = "GPA_login.php";</script>';
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
	<div class="item3">帳號驗證</div>
	<div class="item2">
	
	<form method="POST">
	<p></p>
		<input type="text" placeholder="請輸入你的帳號" name="account" required />
		<p/>
		<input type="submit" value="送出"/>
		<br></br>
		<input type="button" onclick="javascript:location.href='GPA_login.php'" value="回到主畫面"/>
	</form>
	</div>
	</div>
</img>
</body>
</html>