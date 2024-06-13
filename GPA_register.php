<?
include("db_connect.php");
session_start();
$manage=0;
if (!(!isset($_SESSION["user"]) || $_SESSION["user"] == "")){
	$sql_str = "SELECT * FROM `account`";
	$res = mysqli_query($conn, $sql_str);
	
	while ($row_array = mysqli_fetch_assoc($res)){
		foreach ($row_array as $key => $item){
			if($key=='user')$account=$item;
			if($key=='manage')$manage=$item;
		}
		if($_SESSION["user"]==$account)break;
	}
	if($manage==0){
		header("Location: GPA.php");
		exit();
	}
}
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
			$sql_str = "INSERT INTO `account` (`user`, `password`,`Revise_Time`,`manage`) 
						VALUES ('".$_GET['account']."', '".$_GET['password']."','$Revise_Time','$manage')";
			$res = @mysqli_query($conn, $sql_str);
			if(!$res)echo '<script>alert("帳號重複");</script>';
			else{
				if($manage==0)echo '<script>alert("註冊成功");location.href = "GPA_login.php";</script>';
				else echo '<script>alert("註冊成功");location.href = "GPA_login.php?logout=true";</script>';//註冊管理員帳號
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