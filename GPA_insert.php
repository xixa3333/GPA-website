<?
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){
	header("Location: GPA_login.php");
	exit();
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
		</style>
	<head>
<body>
<center>
	<br>
	<h1 align="center">GPA與學期成績計算網站</h1><br>
	<hr>
	<p>
	<form align="center" action="GPA_enter.php" method="get">
		
		<input placeholder="請輸入你有幾科" type="number" name="number_of_subjects" required minlength="1" maxlength="3" size="20" />
		<p>
		<input type="submit" value="確認"/>
		<br></br>
		<input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
	</form>
	</center>
	
</body>
</html>