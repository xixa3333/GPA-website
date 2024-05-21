<html>
	<head>
		<title>GPA計算網站</title>
		<meta charset="utf-8">
	<head>
<body>
	<br>
	<h1 align="center">GPA與學期成績計算網站登入</h1><br>
	<hr>
	
	<?php
	$fp = @fopen("account.txt", "w") or die ("檔案無法開啟");
	@fwrite($fp, 'w,s') or die ("儲存失敗");
	fclose($fp);
	
    $fp = @fopen("year.txt", "w") or die ("檔案無法開啟");
    @fwrite($fp, "112,Required_elective,asc") or die ("儲存失敗");
    fclose($fp);
	?>
	
	<form action="GPA.php" method="get" align="center">
		帳號：<input type="text" name="account" required size="20" />
		<br>
		密碼：<input type="text" name="password" required size="20" />
		<br><br>
		<input type="submit" value="登入"/>
		<input type="reset" value="重新輸入"/>
	</form>
</body>
</html>