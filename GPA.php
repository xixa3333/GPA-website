<html>
	<head>
		<title>GPA計算網站</title>
		<meta charset="utf-8">
	<head>
<body>
	<br>
	<h1>GPA與學期成績計算網站</h1><br>
	<hr>
	<form action="GPA_enter.php" method="get">
		請輸入你有幾科：
		<input type="number" name="number_of_subjects" required minlength="1" maxlength="3" size="20" />
		<br>
		<input type="submit" value="提交"/>
		<input type="reset" value="重新輸入"/>
	</form>
</body>
</html>