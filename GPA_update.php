<?
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){
	header("Location: GPA_login.php");
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>GPA計算網站</title>
    <meta charset="utf-8">
	<style>
		.container {
            display: flex;
			justify-content: center;
        }
        .spacer {
            width: 50px;
        }
	</style>
</head>
<body>
    <br>
    <h1 align="center">GPA與學期成績計算網站</h1><br>
    <hr>
	<?php
		include("db_connect.php");
		$year=$_COOKIE['account']['year'];
		
		$tableName = "table_" . $year.$_SESSION["user"];
		
		$subjects=$_GET['suject'];
		$sql_str = "SELECT * FROM $tableName where `suject`='$subjects'";
		$res = mysqli_query($conn, $sql_str);
		$row_array = mysqli_fetch_assoc($res);

		$Required_elective = $row_array['Required_elective'];
		$course = $row_array['course'];
		$subjects = $row_array['suject'];
		$score = $row_array['score'];
		$credit = $row_array['credit'];
		mysqli_close($conn);
	?>
    <center>
	<br>
    <form action="GPA.php" method="get">
		<input type="hidden" name="update" value="<?php echo 1 ?>"/>
	<div class="container">
		必選修：<select name="Required_elective" required>
		<option <? if($Required_elective == '必修') echo 'selected'; ?> >必修</option>
		<option <? if($Required_elective == '選修') echo 'selected'; ?> >選修</option>
		</select> 
		<div class="spacer"></div>
        課程分類：<select name="course" required>
		<option <? if($course == '專業') echo 'selected'; ?> >專業</option>
		<option <? if($course == '通識') echo 'selected'; ?> >通識</option>
		</select> 
		<div class="spacer"></div>
		
        科目：<? echo $subjects;?><input type="hidden" name="subjects" value="<? echo $subjects;?>"/>
		<div class="spacer"></div>
        成績：<input type="number" value=<? echo $score?> name="score" required style="width: 50px;" /> 
		<div class="spacer"></div>
        學分：<input type="number" value=<? echo $credit?> name="credit" required style="width: 50px;" />
	</div>
        <br>
        <input type="submit" value="提交"/>
        <input type="reset" value="重新輸入"/>
    </form>
	<p>
    <input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
    </center>
</body>
</html>
