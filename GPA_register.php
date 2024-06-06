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
		</style>
	<head>
<body>
	<center>
	<br>
	<h1>GPA與學期成績計算網站註冊帳號</h1><br>
	<hr>
	
	<?
	if (isset($_GET["account"]) && isset($_GET["password"]) && isset($_GET["confirm"])) {
	
		if($_GET['password']==$_GET["confirm"]){
			$sql_str = "INSERT INTO `account` (`user`, `password`) 
						VALUES ('".$_GET['account']."', '".$_GET['password']."')";
			$res = @mysqli_query($conn, $sql_str);
			if(!$res)echo "<font color='red'>帳號重複</font>";
			else{
				if (!isset($_SESSION["user"]) || $_SESSION["user"] == "")header("Location: GPA_login.php");
				else header("Location: GPA.php");
				exit();	
			}
		}
		else echo "<font color='red'>密碼錯誤</font>";
		mysqli_close($conn);
	}
	?>
	<div>
	<form method="get">
	<p>
		<input type="text" placeholder="帳號" name="account" required size="20" />
		<br></br>
		<input type="password" placeholder="密碼" name="password" required size="20" />
		<br></br>
		<input type="password" placeholder="確認密碼" name="confirm" required size="20" />
		<p>
		<input type="submit" value="註冊"/>
		<br></br>
		<input type="button" onclick="javascript:location.href='GPA_login.php'" value="回到主畫面">
	</form>
	</div>
	</center>
</body>
</html>