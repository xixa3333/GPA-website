<?php
session_start();
//判斷是否刪除帳號
if (isset($_GET["delete"]) && $_GET["delete"] == "true") {
	echo '<script>let YES=confirm("確定要刪除帳號嗎?"); if(YES==1)location.href = "GPA.php?enter=1";</script>';
}
//判斷無登入帳號
if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){
	header("Location: GPA_login.php");
	exit();
}
//判斷此網頁已閒置1小時
if (isset($_SESSION['expiretime']) && time() >= $_SESSION['expiretime']) {
    session_unset();
    session_destroy();
    header("Location: GPA_login.php");
    exit();
}

// 計算GPA的函數
function calculateGPA($score, $GPA_sort) {
	$GPA = 0;
    if($GPA_sort=='NKUST'){
		for ($j = 0; $j < 4; $j++) {
			if ($score < (50 + $j * 10)) {
				$GPA = $j;
				break;
			}
		}
		if ($score <= 100 && $score >= 80) $GPA = 4;
	}
	elseif($GPA_sort=='TW0'){
		if ($score <= 59) $GPA = 0;
		elseif ($score <= 62) $GPA = 0.7;
		elseif ($score <= 66) $GPA = 1.0;
		elseif ($score <= 69) $GPA = 1.3;
		elseif ($score <= 72) $GPA = 1.7;
		elseif ($score <= 76) $GPA = 2.0;
		elseif ($score <= 79) $GPA = 2.3;
		elseif ($score <= 82) $GPA = 2.7;
		elseif ($score <= 86) $GPA = 3.0;
		elseif ($score <= 89) $GPA =3.3;
		elseif ($score <= 92) $GPA = 3.7;
		elseif ($score <= 100) $GPA = 4.0;
	}
	elseif($GPA_sort=='TW3'){
		if ($score <= 59) $GPA = 0;
		elseif ($score <= 62) $GPA = 1.7;
		elseif ($score <= 66) $GPA = 2.0;
		elseif ($score <= 69) $GPA = 2.3;
		elseif ($score <= 72) $GPA = 2.7;
		elseif ($score <= 76) $GPA = 3.0;
		elseif ($score <= 79) $GPA =3.3;
		elseif ($score <= 84) $GPA = 3.7;
		elseif ($score <= 89) $GPA = 4.0;
		elseif ($score <= 100) $GPA = 4.3;
	}
    return $GPA;
}

// 更新total的函數
function updateTotalTable($conn, $tableName, $GPA_total, $score_total, $credit_total, $Original_credits,$totalname) {
    $sql_str2 = "SELECT * FROM $totalname WHERE `table_name`='$tableName'";
    $res2 = mysqli_query($conn, $sql_str2);
    if (mysqli_num_rows($res2) > 0) {//如果有此表則更新
        $sql_str2 = "UPDATE $totalname
                     SET `GPA_total`='$GPA_total', `score_total`='$score_total', `credit_total`='$credit_total', `Original_credits`='$Original_credits' 
                     WHERE `table_name`='$tableName';";
    } else {//如果無此表則插入
        $sql_str2 = "INSERT INTO $totalname (`table_name`, `GPA_total`, `score_total`, `credit_total`, `Original_credits`) 
                     VALUES ('$tableName', '$GPA_total', '$score_total', '$credit_total', '$Original_credits')";
    }
    @mysqli_query($conn, $sql_str2);
}
//如果沒有cookie，則預設三個排序的cookie
$date = strtotime(date("Y-m-d 23:59:59"));
if (!isset($_COOKIE ["account"])) {
	setcookie("account[year]",'112up',$date);
	setcookie("account[sort]",'Required_elective',$date);
	setcookie("account[order]",'asc',$date);
	setcookie("account[GPA_sort]",'NKUST',$date);
	header ("Location:GPA.php?GPA_sort=NKUST");
	exit();
}

// 連接到資料庫
include("db_connect.php");

// 處理排序選擇的值
if (isset($_GET['year'])) {
    setcookie("account[year]",$_GET['year'],$date);
	setcookie("account[sort]",$_GET['sort'],$date);
	setcookie("account[order]",$_GET['order'],$date);
	setcookie("account[GPA_sort]",$_GET['GPA_sort'],$date);
    list($year, $sort, $order,$GPA_sort) = [$_GET['year'], $_GET['sort'], $_GET['order'],$_GET['GPA_sort']];
} else {
    list($year, $sort, $order,$GPA_sort) = [$_COOKIE['account']['year'], $_COOKIE['account']['sort'], $_COOKIE['account']['order'], $_COOKIE['account']['GPA_sort']];
}

//命名
$tableName = "table_" . $year.$_SESSION["user"];
$totalname='total'.$_SESSION["user"];

//確定刪除帳號
if (isset($_GET["enter"]) && $_GET["enter"] == 1) {
    $sql_str = "DELETE FROM `account` WHERE `user`='".$_SESSION["user"]."';";//清除帳號資料
    @mysqli_query($conn, $sql_str);
	
	//清除與帳號相關資料表
	$sql_str = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'C112151111' AND table_name LIKE '%".$_SESSION["user"]."'";
    $res = @mysqli_query($conn, $sql_str);
	while($row = mysqli_fetch_assoc($res)) {
        $table_name = $row["table_name"];
        $drop_sql = "DROP TABLE `$table_name`";
        @mysqli_query($conn, $drop_sql);
    }
	//清除帳號登入資料
	session_unset();
    session_destroy();
    header("Location: GPA_login.php");
    exit();
}

// 檢查表是否存在
$sql_str = "SHOW TABLES LIKE '$tableName'";
$res = mysqli_query($conn, $sql_str);

// 如果表不存在，創建新表
if (mysqli_num_rows($res) == 0) {
    $createTableSQL = "CREATE TABLE `$tableName` (
        `Required_elective` ENUM('必修', '選修') NOT NULL DEFAULT '必修',
        `course` ENUM('專業', '通識') NOT NULL DEFAULT '專業',
        `suject` VARCHAR(30) NOT NULL,
        `score` INT(4),
        `credit` INT(2),
        `GPA` FLOAT,
        PRIMARY KEY (`suject`)
    )";
    @mysqli_query($conn, $createTableSQL) or die("創建資料表錯誤");
}

// 查詢表的資料
$sql_str = "SELECT * FROM `$tableName`";
$res = mysqli_query($conn, $sql_str);

// 處理新增、刪除和更新資料的GET請求
if (isset($_GET['number_of_subjects']) || isset($_GET['suject']) || isset($_GET['update'])||isset($_GET['GPA_sort'])) {
    for ($i = 1; $i <= (isset($_GET['number_of_subjects']) ? $_GET['number_of_subjects'] : 0); $i++) {//新增科目資料
        list($Required_elective, $course, $suject, $score, $credit) = [
            $_GET['Required_elective'][$i],
            $_GET['course'][$i],
            $_GET['subjects'][$i],
            $_GET['score'][$i],
            $_GET['credit'][$i]
        ];

        $GPA = calculateGPA($score, $GPA_sort);

        $sql_str = "INSERT INTO `$tableName` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) 
                    VALUES ('$Required_elective', '$course', '$suject', '$score', '$credit', '$GPA')";
        @mysqli_query($conn, $sql_str);
    }

    if (isset($_GET['suject'])) {//刪除資料
        $suject = $_GET['suject'];
        $sql_str = "DELETE FROM $tableName WHERE `suject`='$suject';";
        @mysqli_query($conn, $sql_str);
		$sql_str = "SELECT * FROM $tableName";
		$res = mysqli_query($conn, $sql_str);
		if (mysqli_num_rows($res) == 0) {
			$sql_str = "DELETE FROM $totalname WHERE `table_name`='$tableName';";
			mysqli_query($conn, $sql_str);
		}
    }

    if (isset($_GET['update'])) {//更新資料
        list($Required_elective, $course, $suject, $score, $credit) = [
            $_GET['Required_elective'],
            $_GET['course'],
            $_GET['subjects'],
            $_GET['score'],
            $_GET['credit']
        ];

        $GPA = calculateGPA($score, $GPA_sort);

        $sql_str = "UPDATE `$tableName` 
                    SET `Required_elective`='$Required_elective', `course`='$course', `score`='$score', `credit`='$credit', `GPA`='$GPA' 
                    WHERE `suject`='$suject';";
        @mysqli_query($conn, $sql_str);
    }
	
	if(isset($_GET['GPA_sort'])){//更新GPA資料
		$sql_str = "SELECT * FROM `$tableName`";
		$res = mysqli_query($conn, $sql_str);
		while ($row_array = mysqli_fetch_assoc($res)) {
			foreach ($row_array as $key => $item) {
				if ($key == 'score') $score = $item;
				elseif ($key == 'suject') $suject = $item;
			}
			$GPA = calculateGPA($score, $GPA_sort);
			$sql_str2 = "UPDATE `$tableName` SET `GPA`='$GPA' WHERE `suject`='$suject';";
			mysqli_query($conn, $sql_str2);
		}
        @mysqli_query($conn, $sql_str);
	}
	
	//判斷是否此學期資料為0筆
	$sql_str = "SELECT * FROM $tableName";
	$res = mysqli_query($conn, $sql_str);
    if(@mysqli_num_rows($res) != 0){
		// 計算總GPA、成績和學分
		$GPA_total = 0;
		$score_total = 0;
		$credit_total = 0;
		$Original_credits = 0;
		$sql_str = "SELECT * FROM `$tableName`";
		$res = mysqli_query($conn, $sql_str);
		while ($row_array = mysqli_fetch_assoc($res)) {
			foreach ($row_array as $key => $item) {
				if ($key == 'score') $score = $item;
				elseif ($key == 'GPA') $GPA = $item;
				elseif ($key == 'credit') $credit = $item;
			}
			$Original_credits += $credit;
			$GPA_total += ($GPA * $credit);
			$score_total += ($score * $credit);
			if ($score < 60) $credit = 0;
			$credit_total += $credit;
		}
		@$GPA_total /= $Original_credits;
		@$score_total /= $Original_credits;
		$GPA_total = number_format($GPA_total, 2);
		$score_total = number_format($score_total, 2);
		
		// 檢查和更新total表的資料
		$sql_str2 = "SHOW TABLES LIKE '$totalname'";
		$res2 = mysqli_query($conn, $sql_str2);
		if (@mysqli_num_rows($res2) == 0) {//沒有表時創立新表
			$createTableSQL = "CREATE TABLE `$totalname` (
				`table_name` VARCHAR(50),
				`GPA_total` FLOAT,
				`score_total` FLOAT,
				`credit_total` INT(5),
				`Original_credits` INT(5),
				PRIMARY KEY (`table_name`)
			)";

			@mysqli_query($conn, $createTableSQL) or die("創建資料表錯誤");

			$sql_str2 = "INSERT INTO `$totalname` (`table_name`, `GPA_total`, `score_total`, `credit_total`, `Original_credits`) 
						 VALUES ('$tableName', '$GPA_total', '$score_total', '$credit_total', '$Original_credits')";

			@mysqli_query($conn, $sql_str2);
		} else {
			updateTotalTable($conn, $tableName, $GPA_total, $score_total, $credit_total, $Original_credits,$totalname);
		}	
	}
}
else if (mysqli_num_rows($res) != 0) {//在此學期有資料並且不是在更新刪除新增資料時
	//將加總好的資料從資料表抓出來
	
    $sql_str = "SELECT * FROM `$totalname` WHERE `table_name`='$tableName'";
    $res = mysqli_query($conn, $sql_str);
    $row_array = mysqli_fetch_assoc($res);
    $GPA_total = $row_array['GPA_total'];
    $score_total = $row_array['score_total'];
    $credit_total = $row_array['credit_total'];
	
	$GPA_total = number_format($GPA_total, 2);
	$score_total = number_format($score_total, 2);
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>GPA計算網站</title>
	<link href="style.css" rel="stylesheet">
	<style>
		select{
			width:100px;
		}
		.item{
			text-align: right;
			background-color: rgb(170,170,255);
			padding: 7px;
			border-radius:10px;
			-webkit-filter: drop-shadow(0px 0px 10px rgb(100,100,100));
		}
		.center-text {
            flex-grow: 2;
            text-align: center;
        }
	</style>
	
</head>
<body>
<center>

<div class="container" style="justify-content: space-between;align-items: stretch;">
<div class="spacer2"></div>
<h1 class="center-text">GPA與學期成績計算網站</h1>
<a href='GPA_login.php?logout=true'>登出</a>
｜
<a href='GPA_forget.php'>修改密碼</a>
｜
<a href='GPA.php?delete=true'>刪除此帳號</a>
</div>
<div class="container">
<form action="GPA_credits.php" method="GET">
    <input type="submit" value="計算總學分">
</form>
<div class="spacer"></div>
<form action="GPA_insert.php" method="POST">
    <input type="submit" value="新增資料">
</form>
</div>

<p>
<hr>
<p>



<!-- 學年度和排序選擇表單 -->
<form action="" method="get">
<div class="container" style="justify-content: center;">
    學年度：
    <select name="year" required onchange="this.form.submit()">
        <option value="112up" <?= $year == '112up' ? 'selected' : '' ?>>112上學期</option>
		<option value="112down" <?= $year == '112down' ? 'selected' : '' ?>>112下學期</option>
        <option value="113up" <?= $year == '113up' ? 'selected' : '' ?>>113上學期</option>
		<option value="113down" <?= $year == '113down' ? 'selected' : '' ?>>113下學期</option>
        <option value="114up" <?= $year == '114up' ? 'selected' : '' ?>>114上學期</option>
		<option value="114down" <?= $year == '114down' ? 'selected' : '' ?>>114下學期</option>
        <option value="115up" <?= $year == '115up' ? 'selected' : '' ?>>115上學期</option>
		<option value="115down" <?= $year == '115down' ? 'selected' : '' ?>>115下學期</option>
    </select>
	<div class="spacer"></div>
    排序：
    <select name="sort" required onchange="this.form.submit()">
        <option value="Required_elective" <?= $sort == 'Required_elective' ? 'selected' : '' ?>>選必修</option>
        <option value="course" <?= $sort == 'course' ? 'selected' : '' ?>>課程分類</option>
        <option value="suject" <?= $sort == 'suject' ? 'selected' : '' ?>>科目</option>
        <option value="score" <?= $sort == 'score' ? 'selected' : '' ?>>成績</option>
        <option value="credit" <?= $sort == 'credit' ? 'selected' : '' ?>>學分</option>
        <option value="GPA" <?= $sort == 'GPA' ? 'selected' : '' ?>>GPA</option>
    </select>
	<div class="spacer"></div>
    升降序：
    <select name="order" required onchange="this.form.submit()">
        <option value="asc" <?= $order == 'asc' ? 'selected' : '' ?>>升序</option>
        <option value="desc" <?= $order == 'desc' ? 'selected' : '' ?>>降序</option>
    </select>
	<div class="spacer"></div>
    GPA計算方式：
    <select name="GPA_sort" required onchange="this.form.submit()">
        <option value="NKUST" <?= $GPA_sort == 'NKUST' ? 'selected' : '' ?>>高科GPA4.0</option>
        <option value="TW0" <?= $GPA_sort == 'TW0' ? 'selected' : '' ?>>台灣GPA4.0</option>
		<option value="TW3" <?= $GPA_sort == 'TW3' ? 'selected' : '' ?>>台灣GPA4.3</option>
    </select>
</div>
</form>

<p>



<?php
// 資料顯示
$sql_str = "SELECT * FROM $tableName ORDER BY $sort $order";
$res = mysqli_query($conn, $sql_str);
if (mysqli_num_rows($res) != 0) {
?>

<table align="center" border="1" style="text-align: center;">
    <colgroup>
        <col style="width: 200px;">
        <col style="width: 200px;">
        <col style="width: 200px;">
        <col style="width: 200px;">
        <col style="width: 200px;">
        <col style="width: 200px;">
        <col style="width: 200px;">
    </colgroup>
    <tr style="background-color:rgb(100,100,240);">
        <th>選必修</th>
        <th>課程分類</th>
        <th>科目</th>
        <th>成績</th>
        <th>學分</th>
        <th>GPA</th>
        <th>功能</th>
    </tr>

    <?php while ($row_array = mysqli_fetch_assoc($res)): ?>
        <tr style="background-color:rgb(170,170,250);">
            <?php foreach ($row_array as $key => $item): ?>
                <td><font <?= ($key == 'score' && $item < 60) ? 'color="red"' : '' ?>><?= ($key=='GPA')?number_format($item, 1):$item; ?></font></td>
            <?php endforeach; ?>
            <td>
                <a href="GPA_update.php?suject=<?= $row_array['suject'] ?>">修改</a>
                <a href="GPA.php?suject=<?= $row_array['suject'] ?>">刪除</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>
<p>



<div class="container" style="justify-content: center;">
學期成績：<?= $score_total ?>
<div class="spacer"></div>
學期總獲得學分：<?= $credit_total ?>
<div class="spacer"></div>
學期GPA：<?= $GPA_total ?>
</div>

<?php
} else {
    echo '<p align="center">你沒有資料喔</p>';
}
?>



<p>
<b>學期成績計算公式：(各科成績 * 各科學分) 全相加後 / 總學分</b>
<div class="container">
<div class="item">
<div  align="center">
<?
if($GPA_sort=='NKUST')echo "高科GPA4.0";
elseif($GPA_sort=='TW0')echo "台灣GPA4.0";
elseif($GPA_sort=='TW3')echo "台灣GPA4.3";
?>
計算方式：
</div>
<table align="center" border="1">
    <colgroup>
        <col style="width: 200px;">
        <col style="width: 200px;">
    </colgroup>
    <tr>
        <th align="center">成績</th>
        <th align="center">GPA</th>
    </tr>
    
    <?php
	if($GPA_sort=='NKUST'){
		for ($j = 0; $j < 4; $j++) {
			echo '<tr>';
			echo '<td align="center">小於' . (50 + $j * 10) . '</td>';
			echo '<td align="center">' . $j . '</td>';
			echo '</tr>';
		}
		?>
		<tr>
			<td align="center">大於等於80</td>
			<td align="center">4</td>
		</tr>
	<?}
	if($GPA_sort=='TW0'){
	?>
		<tr><td align="center">60以下</td><td align="center">0</td></tr>
		<tr><td align="center">60-62</td><td align="center">0.7</td></tr>
		<tr><td align="center">63-66</td><td align="center">1.0</td></tr>
		<tr><td align="center">67-69</td><td align="center">1.3</td></tr>
		<tr><td align="center">70-72</td><td align="center">1.7</td></tr>
		<tr><td align="center">73-76</td><td align="center">2.0</td></tr>
		<tr><td align="center">77-79</td><td align="center">2.3</td></tr>
		<tr><td align="center">80-82</td><td align="center">2.7</td></tr>
		<tr><td align="center">83-86</td><td align="center">3.0</td></tr>
		<tr><td align="center">87-89</td><td align="center">3.3</td></tr>
		<tr><td align="center">90-92</td><td align="center">3.7</td></tr>
		<tr><td align="center">93-100</td><td align="center">4.0</td></tr>
	<?}
	if($GPA_sort=='TW3'){
	?>
	<tr><td align="center">60以下</td><td align="center">0</td></tr>
	<tr><td align="center">60-62</td><td align="center">1.7</td></tr>
	<tr><td align="center">63-66</td><td align="center">2.0</td></tr>
	<tr><td align="center">67-69</td><td align="center">2.3</td></tr>
	<tr><td align="center">70-72</td><td align="center">2.7</td></tr>
	<tr><td align="center">73-76</td><td align="center">3.0</td></tr>
	<tr><td align="center">77-79</td><td align="center">3.3</td></tr>
	<tr><td align="center">80-84</td><td align="center">3.7</td></tr>
	<tr><td align="center">85-89</td><td align="center">4.0</td></tr>
	<tr><td align="center">90-100</td><td align="center">4.3</td></tr>
	<?}	?>
	
</table>
</div>
</div>

<p>
<?
if($GPA_sort=='NKUST')echo '<a href="https://acad.nkust.edu.tw/var/file/4/1004/img/382/L-7-1re(1).pdf">GPA資料來源</a>';
elseif($GPA_sort=='TW0')echo '<a href="https://www.tkbgo.com.tw/zone/english/news/toNewsDetail.jsp?news_id=4872#target3-2">GPA資料來源</a>';
elseif($GPA_sort=='TW3')echo '<a href="https://www.tkbgo.com.tw/zone/english/news/toNewsDetail.jsp?news_id=4872#target3-2">GPA資料來源</a>';
mysqli_close($conn);
?>

</center>
</body>
</html>