<html>
	<head>
		<title>GPA計算網站</title>
		<meta charset="utf-8">
	<head>
<body>
	<br>
	<h1>GPA與學期成績計算網站</h1><br>
	<hr>
	<form action="GPA_finish.php" method="get">
		<input type="hidden" name="number_of_subjects" value=<?php echo $_GET['number_of_subjects'] ?>/>
		<br>
	<?php
		#輸入科目成績等
		for($i=1;$i<=$_GET['number_of_subjects'];$i++){
			echo '科目'.$i.'：<input type="text" name="subjects['.$i.']" required />  ';
			echo '成績：<input type="number" name="score['.$i.']" required  />  ';
			echo '學分：<input type="number" name="credit['.$i.']" required />';
			echo '<br>';
		}
	?>
		<input type="submit" value="提交"/>
		<input type="reset" value="重新輸入"/>
	</form>
</body>
</html>