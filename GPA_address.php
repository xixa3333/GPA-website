<?php
include("db_connect.php");
session_start();

//未登入的進不來
if ((!isset($_SESSION["user"]) || $_SESSION["user"] == "")){
	header("Location: GPA_login.php");
	exit();
}

include("Send_letter.php");

$sql_str = "SELECT * FROM `account` WHERE `user`='".$_SESSION["user"]."';";
$res = @mysqli_query($conn, $sql_str);

$row_array = mysqli_fetch_assoc($res);
$token = $row_array['token'];
$address = $row_array['address'];

if (isset($_POST['address'])) {
		
		if($address==$_POST['address']){
			echo '<script>alert("電子郵件重複，請重新輸入");location.href = "GPA_address.php";</script>';
			exit();
		}
		
		$sql_str = "SELECT * FROM `account` WHERE `address`='".$_POST['address']."';";
		$res = @mysqli_query($conn, $sql_str);
		if(mysqli_num_rows($res) != 0){
			echo '<script>alert("此電子郵件已被綁定，請重新輸入");location.href = "GPA_address.php";</script>';
			exit();
		}
		
		$sql_str = "UPDATE `account` SET `newaddress`='".$_POST['address']."' WHERE `token`='$token';";
		mysqli_query($conn, $sql_str);
		
		sendPasswordResetEmail($_POST['address'], "GPA與學期成績網站修改電子郵件", "歡迎使用GPA與學期成績網站，請驗證電子郵件:http://203.64.95.42/C112151111/GPA_verify.php?token=$token ", '<script>alert("電子郵件輸入錯誤，請重新輸入");location.href = "GPA_address.php";</script>');
		
		echo '<script>alert("請前往你新的郵箱驗證");location.href = "GPA.php";</script>';
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
	<div class="item3">修改電子郵件</div>
	<div class="item2">
	
	<form method="POST">
	<p></p>
		原電子郵件：
		<p/>
		<b><?php echo "$address";?></b>
		<p/>
		<input type="text" placeholder="請輸入你新的電子郵件" name="address" required />
		<p/>
		<input type="submit" value="送出"/>
		<br></br>
		<input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面"/>
	</form>
	</div>
	</div>
</img>
</body>
</html>