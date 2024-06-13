<?
session_start();
if (isset($_SESSION['expiretime']) && time() >= $_SESSION['expiretime']) {
    session_unset();
    session_destroy();
    header("Location: GPA_login.php");
    exit();
}
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
    <form action="GPA.php" method="get">
        <input type="hidden" name="number_of_subjects" value="<?php echo $_GET['number_of_subjects']; ?>"/>
        <p></p>
        <?php
        // 輸入科目成績等
        for($i = 1; $i <= @$_GET['number_of_subjects']; $i++) {
			echo '<div class="container">';
            echo '必選修：<select name="Required_elective['.$i.']" required style="width: 100px;"><option>必修</option><option>選修</option></select>  ';
			echo '<div class="spacer"></div>';
            echo '課程分類：<select name="course['.$i.']" required style="width: 100px;"><option>專業</option><option>通識</option></select>  ';
			echo '<div class="spacer"></div>';
            echo '科目：<input type="text" name="subjects['.$i.']" required style="width: 200px;" />  ';
			echo '<div class="spacer"></div>';
            echo '成績：<input type="number" name="score['.$i.']" required style="width: 70px;" />  ';
			echo '<div class="spacer"></div>';
            echo '學分：<input type="number" name="credit['.$i.']" required style="width: 70px;" />';
			echo '</div>';
            echo '<br></br>';
        }
        ?>
		<p></p>
		<div class="container">
        <input type="submit" value="提交"/>
		<div class="spacer"></div>
		<input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
		</div>
    </form>
    </center>
</body>
</html>
