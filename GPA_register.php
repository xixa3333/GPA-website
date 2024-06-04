<?
include("db_connect.php");
?>
<html>
	<head>
		<title>GPA計算網站</title>
		<meta charset="utf-8">
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
	}
	?>
	<form method="get">
	<p>
		帳號：<input type="text" name="account" required size="20" />
		<br>
		密碼：<input type="password" name="password" required size="20" />
		<br>
		確認密碼：<input type="password" name="confirm" required size="15" />
		<br><br>
		<input type="submit" value="註冊"/>
		<input type="reset" value="重新輸入"/>
	</form>
	<input type="button" onclick="javascript:location.href='GPA_login.php'" value="回到主畫面">
	</center>
</body>
</html>