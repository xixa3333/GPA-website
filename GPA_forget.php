<?
include("db_connect.php");
session_start();
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
	<div class="item3">修改密碼</div>
	<div class="item2">
	<?
	//此網頁來當單純修改密碼或忘記密碼時使用，所以會有兩種帳號取得方式
	if ((isset($_SESSION["user"])||isset($_GET["account"])) && isset($_GET["password"]) && isset($_GET["confirm"])) {
	
		if($_GET['password']==$_GET["confirm"]){
			$sql_str = "SELECT * FROM `account`";
			$res = mysqli_query($conn, $sql_str);
			while ($row_array = mysqli_fetch_assoc($res)){
				foreach ($row_array as $key => $item){
					if($key=='user' && ((isset($_SESSION["user"]) && $_SESSION["user"]==$item) || (isset($_GET["account"]) && $_GET["account"]==$item))){
						$account=$item;
						$flag=1;//抓到此帳號，用來判斷是否為舊密碼
					}
					
					if(isset($account) && $key=='password' && $_GET['password']==$item && $flag==1){
						echo '<script>alert("密碼修改失敗，你輸入的是舊密碼");location.href = "GPA_forget.php";</script>';
						exit();
					}
				}
				$flag=0;
			}
			//代表有此帳號且不為舊密碼
			if(isset($account)){
				$Revise_Time = date("Y-m-d");
				$sql_str = "UPDATE `account` SET `password`='".$_GET['password']."',`Revise_Time`='$Revise_Time' WHERE `user`='$account';";
				mysqli_query($conn, $sql_str);
				echo '<script>alert("密碼修改成功");</script>';
				if (!isset($_SESSION["user"]) || $_SESSION["user"] == "")echo '<script>location.href = "GPA_login.php";</script>';
				else echo '<script>location.href = "GPA.php";</script>';
				exit();
			}
			else echo '<script>alert("查無此帳號");</script>';
		}
		else echo '<script>alert("密碼錯誤");</script>';
		mysqli_close($conn);
	}
	?>
	
	<form method="get">
	<p></p>
		<?if(!isset($_SESSION["user"]) || $_SESSION["user"] == ""){?>
		<input type="text" placeholder="帳號" name="account" required />
		<?}?>
		<br></br>
		<div class="box">
			<input type="password" id="psw" placeholder="修改密碼" name="password" required size="20"/>
			<img src="close.jpg" alt="" id="eye"></img>
		</div>
		<br/>
		<div class="box">
			<input type="password" id="psw2" placeholder="確認密碼" name="confirm" required size="20"/>
			<img src="close.jpg" alt="" id="eye2"></img>
		</div>
		<p/>
		<input type="submit" value="修改密碼"/>
		<br></br>
		<?if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){?>
			<input type="button" onclick="javascript:location.href='GPA_login.php'" value="回到主畫面"/>
		<?}
		if (isset($_SESSION["user"])){?>
			<input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面"/>
		<?}?>
		
		
	</form>
	</div>
	</div>
</img>
<script src="eye.js"></script>
</body>
</html>