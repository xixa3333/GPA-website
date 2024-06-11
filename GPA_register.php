<?
include("db_connect.php");
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
	<?
	if (isset($_GET["account"]) && isset($_GET["password"]) && isset($_GET["confirm"])) {
		$Revise_Time = date("Y-m-d");
		if($_GET['password']==$_GET["confirm"]){
			$sql_str = "INSERT INTO `account` (`user`, `password`,`Revise_Time`) 
						VALUES ('".$_GET['account']."', '".$_GET['password']."','$Revise_Time')";
			$res = @mysqli_query($conn, $sql_str);
			if(!$res)echo '<script>alert("帳號重複");</script>';
			else{
				echo '<script>alert("註冊成功");location.href = "GPA_login.php";</script>';
				exit();	
			}
		}
		else echo '<script>alert("密碼錯誤");</script>';
		mysqli_close($conn);
	}
	?>
	
	<form method="get">
	<p></p>
		<input type="text" placeholder="帳號" name="account" required size="20" />
		<br></br>
		<div class="box">
			<input type="password" id="psw" placeholder="密碼" name="password" required size="20"/>
			<img src="close.jpg" alt="" id="eye"></img>
		</div>
		<br/>
		<div class="box">
			<input type="password" id="psw2" placeholder="確認密碼" name="confirm" required size="20"/>
			<img src="close.jpg" alt="" id="eye2"></img>
		</div>
		<p>
		<input type="submit" value="註冊"/>
		<br></br>
		<input type="button" onclick="javascript:location.href='GPA_login.php'" value="回到主畫面">
	</form>
	</div>
	</div>
</img>
<script src="eye.js"></script>
</body>
</html>