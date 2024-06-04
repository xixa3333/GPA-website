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
	<head>
<body>
	<br>
	<h1 align="center">GPA與學期成績計算網站</h1><br>
	<hr>
	<p>
	<form align="center" action="GPA_enter.php" method="get">
		
		請輸入你有幾科：
		<input type="number" name="number_of_subjects" required minlength="1" maxlength="3" size="20" />
		<br>
		<p>
		<input type="submit" value="提交"/>
		<input type="reset" value="重新輸入"/>
	</form>
	<center>
	<input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
</body>
</html>