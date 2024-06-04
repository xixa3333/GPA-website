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
	<style>
		.container {
            display: flex;
			justify-content: center;
        }
        .spacer {
            width: 60px;
        }
	</style>
</head>
<body>
    <br>
    <h1 align="center">GPA與學期成績計算網站</h1><br>
    <hr>
    <center>
    <form action="GPA.php" method="get">
        <input type="hidden" name="number_of_subjects" value="<?php echo $_GET['number_of_subjects']; ?>"/>
        <br>
        <?php
        // 輸入科目成績等
        for($i = 1; $i <= $_GET['number_of_subjects']; $i++) {
			echo '<div class="container">';
            echo '必選修：<select name="Required_elective['.$i.']" required><option>必修</option><option>選修</option></select>  ';
			echo '<div class="spacer"></div>';
            echo '課程分類：<select name="course['.$i.']" required><option>專業</option><option>通識</option></select>  ';
			echo '<div class="spacer"></div>';
            echo '科目：<input type="text" name="subjects['.$i.']" required style="width: 200px;" />  ';
			echo '<div class="spacer"></div>';
            echo '成績：<input type="number" name="score['.$i.']" required style="width: 50px;" />  ';
			echo '<div class="spacer"></div>';
            echo '學分：<input type="number" name="credit['.$i.']" required style="width: 50px;" />';
			echo '</div>';
            echo '<p>';
        }
        ?>
		<p>
        <input type="submit" value="提交"/>
        <input type="reset" value="重新輸入"/>
    </form>
    <input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
    </center>
</body>
</html>
