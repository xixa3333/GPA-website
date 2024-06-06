<?php
	include("db_connect.php");
	session_start();
	$file_name = $_SERVER['PHP_SELF'];
	$login=0;
	
	if (isset($_GET["logout"]) && $_GET["logout"] == "true") {
		session_unset();
		session_destroy();
		// 並把網頁重新導回到首頁 (為了顯示登入表單)
		header("Location: $file_name");
		exit();
	}
	
	$date = date("Y-m-d 23:59:59");
	if (isset($_GET["account"]) && isset($_GET["password"])) {
		$sql_str = "SELECT * FROM `account`";
		$res = mysqli_query($conn, $sql_str);
		while ($row_array = mysqli_fetch_assoc($res)){
			foreach ($row_array as $key => $item){
				if($key=='user')$account=$item;
				if($key=='password')$password=$item;
			}
			if($_GET['account']==$account and $_GET['password']==$password)$login=1;
		}
		if($login==1){
			$_SESSION["user"] = $_GET['account'];
			$_SESSION['expiretime'] = time() + 60*60;
			header("Location: GPA.php");
			exit();
		}
	}
	//input[type="text"]
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
		</style>
	<head>
<body>

	<center>
	<br/>
	<h1>GPA與學期成績計算網站登入</h1><br/>
	<hr>
	<?if(isset($_GET["account"]) && isset($_GET["password"])&&$login==0)echo "<font color='red'>帳號密碼錯誤，請重新輸入。</font>";?>
	<br style="line-height:120%;"/>
	<form method="get">
	
		<input type="text" placeholder="帳號" name="account" required size="20" />
		<br></br>
		<input type="password" placeholder="密碼" name="password" required size="20" />
		<p/>
		<input type="submit" value="登入"/>
		<br></br>
		<input type="button" onclick="javascript:location.href='GPA_register.php'" value="註冊帳號">
	</form>
	</center>
</body>
</html>