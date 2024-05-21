<!DOCTYPE html>
<html>
<head>
    <title>GPA計算網站</title>
    <meta charset="utf-8">
</head>
<body>
    <br>
    <h1 align="center">GPA與學期成績計算網站</h1><br>
    <hr>
	<?php
		include("db_connect.php");
		$fp = fopen("year.txt", "r");
		$data = fgets($fp);
		$data=explode(",",$data);
		fclose($fp);
		$year = $data[0];
		
		$tableName = "table_" . $year;
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
    <form action="GPA.php" method="get">
        <br>
		<input type="hidden" name="update" value="<?php echo 1 ?>"/>
		必選修：<select name="Required_elective" value=<? echo $Required_elective?> required><option>必修</option><option>選修</option></select> 
        課程分類：<select name="course" value=<? echo $sex?> required><option>專業</option><option>通識</option></select> 
        科目：<? echo $subjects;?><input type="hidden" name="subjects" value="<? echo $subjects;?>"/>
        成績：<input type="number" value=<? echo $score?> name="score" required style="width: 50px;" /> 
        學分：<input type="number" value=<? echo $credit?> name="credit" required style="width: 50px;" />
        <br>
        <input type="submit" value="提交"/>
        <input type="reset" value="重新輸入"/>
    </form>
    <input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
    </center>
</body>
</html>
