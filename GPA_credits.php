<?
	session_start();
	if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){
		header("Location: GPA_login.php");
		exit();
	}
		include("db_connect.php");
		$totalname='total'.$_COOKIE['account']['user'];
		$sql_str = "SELECT * FROM `$totalname`";
		$res = mysqli_query($conn, $sql_str);
		
		$Required_majors=0;
		$common=0;
		$Elective_majors=0;
		$General_Education=0;
		$GPA_total2=0;
		$score_total2=0;
		$Original_credits_total=0;
		$credit_total2=0;
?>
<!DOCTYPE html>
<html>
<head>
    <title>GPA計算網站</title>
    <meta charset="utf-8">
	<link href="style.css" rel="stylesheet">
</head>
<body>
    <br>
    <h1 align="center">GPA與學期成績計算網站</h1><br>
    <hr>
	<center>
	<?
	if (@mysqli_num_rows($res) != 0) {
			while($row_array = mysqli_fetch_assoc($res)){
				foreach($row_array as $key => $item){
					if($key=='GPA_total')$GPA_total=$item;
					elseif($key=='score_total')$score_total=$item;
					elseif($key=='credit_total')$credit_total=$item;
					elseif($key=='Original_credits')$Original_credits=$item;
					
					#連接其他資料表
					elseif($key=='table_name'){
						
						$sql_str2 = "SELECT * FROM $item";
						$res2 = mysqli_query($conn, $sql_str2);
						while($row_array2 = mysqli_fetch_assoc($res2)){
							foreach($row_array2 as $key2 => $item2){
								if($key2=='Required_elective')$Required_elective=$item2;
								elseif($key2=='score')$score=$item2;
								elseif($key2=='course')$course=$item2;
								elseif($key2=='credit')$credit=$item2;
								elseif($key2=='GPA')$GPA=$item2;
							}
							if($Required_elective == '必修'){
								#必修專業
								if($course == '專業')$Required_majors+=(($score>=60)?$credit:0);
								#共同必修
								else $common+=(($score>=60)?$credit:0);
							}
							else{
								#專業選修
								if($course == '專業') $Elective_majors+=(($score>=60)?$credit:0);
								#校訂、博雅
								else $General_Education+=(($score>=60)?$credit:0);
							}
						}
					}
				}
				$GPA_total2+=($Original_credits*$GPA_total);
				$score_total2+=($Original_credits*$score_total);
				$Original_credits_total+=$Original_credits;
				$credit_total2+=$credit_total;
			}
			
			$GPA_total2 /= $Original_credits_total;
			$score_total2 /= $Original_credits_total;
			$GPA_total2 = number_format($GPA_total2, 2);
			$score_total2 = number_format($score_total2, 2);
	
	?>
<p>
<div class="container">
	<div class="item2">專業選修：<?php echo $Elective_majors ?>/47</div>
	<div class="item2">通識：<?php echo $General_Education ?>/16</div>
</div>
<div class="container">
	<div class="item2">專業必修：<?php echo $Required_majors ?>/53</div>
	<div class="item2">共同必修：<?php echo $common ?>/12</div>
</div>

<div class="container">
	<div class="item">總平均成績：<?php echo $score_total2 ?></div>
	<div class="item">總獲得學分：<?php echo $credit_total2 ?></div>
	<div class="item">總平均GPA：<?php echo $GPA_total2 ?></div>
</div>
	<?
	
	}
	else echo "沒有資料喔<br>";
	mysqli_close($conn);
	?>
	<p>
	<input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
    </center>
</body>
</html>
