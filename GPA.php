<?php
session_start();

if (isset($_GET["delete"]) && $_GET["delete"] == "true") {
	echo '<script>let YES=confirm("確定要刪除帳號嗎?"); if(YES==1)location.href = "GPA.php?enter=1";</script>';
}

if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){
	header("Location: GPA_login.php");
	exit();
}

if (isset($_SESSION['expiretime']) && time() >= $_SESSION['expiretime']) {
    session_unset();
    session_destroy();
    header("Location: GPA_login.php");
    exit();
}

// 計算GPA的函數
function calculateGPA($score, $credit) {
    $GPA = 0;
    for ($j = 0; $j < 4; $j++) {
        if ($score < (50 + $j * 10)) {
            $GPA = $j;
            break;
        }
    }
    if ($score <= 100 && $score >= 80) $GPA = 4;
    return $GPA;
}

// 更新總表資料的函數
function updateTotalTable($conn, $tableName, $GPA_total, $score_total, $credit_total, $Original_credits,$totalname) {
    $sql_str2 = "SELECT * FROM $totalname WHERE `table_name`='$tableName'";
    $res2 = mysqli_query($conn, $sql_str2);
    if (mysqli_num_rows($res2) > 0) {
        $sql_str2 = "UPDATE $totalname
                     SET `GPA_total`='$GPA_total', `score_total`='$score_total', `credit_total`='$credit_total', `Original_credits`='$Original_credits' 
                     WHERE `table_name`='$tableName';";
    } else {
        $sql_str2 = "INSERT INTO $totalname (`table_name`, `GPA_total`, `score_total`, `credit_total`, `Original_credits`) 
                     VALUES ('$tableName', '$GPA_total', '$score_total', '$credit_total', '$Original_credits')";
    }
    @mysqli_query($conn, $sql_str2);
}

$date = strtotime(date("Y-m-d 23:59:59"));
if (!isset($_COOKIE ["account"])) {
	setcookie("account[year]",'112up',$date);
	setcookie("account[sort]",'Required_elective',$date);
	setcookie("account[order]",'asc',$date);
	header ("Location:GPA.php");
	exit();
}

// 連接到資料庫
include("db_connect.php");

// 處理GET請求的year參數
if (isset($_GET['year'])) {
    setcookie("account[year]",$_GET['year'],$date);
	setcookie("account[sort]",$_GET['sort'],$date);
	setcookie("account[order]",$_GET['order'],$date);
	
    list($year, $sort, $order) = [$_GET['year'], $_GET['sort'], $_GET['order']];
} else {
    list($year, $sort, $order) = [$_COOKIE['account']['year'], $_COOKIE['account']['sort'], $_COOKIE['account']['order']];
}

$tableName = "table_" . $year.$_SESSION["user"];
$totalname='total'.$_SESSION["user"];

if (isset($_GET["enter"]) && $_GET["enter"] == 1) {
    $sql_str = "DELETE FROM `account` WHERE `user`='".$_SESSION["user"]."';";
    @mysqli_query($conn, $sql_str);
	$sql_str = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'C112151111' AND table_name LIKE '%".$_SESSION["user"]."'";
    $res = @mysqli_query($conn, $sql_str);
	while($row = mysqli_fetch_assoc($res)) {
        $table_name = $row["table_name"];
        $drop_sql = "DROP TABLE `$table_name`";
        
        // 执行 DROP TABLE 语句
        if (mysqli_query($conn, $drop_sql) === TRUE) {
            echo "表 $table_name 删除成功<br>";
        } else {
            echo "删除表 $table_name 时出错<br>";
        }
    }
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
        `GPA` INT(2),
        PRIMARY KEY (`suject`)
    )";
    @mysqli_query($conn, $createTableSQL) or die("創建資料表錯誤");
}

// 查詢表的資料
$sql_str = "SELECT * FROM `$tableName`";
$res = mysqli_query($conn, $sql_str);

// 處理新增、刪除和更新資料的GET請求
if (isset($_GET['number_of_subjects']) || isset($_GET['suject']) || isset($_GET['update'])) {
    for ($i = 1; $i <= (isset($_GET['number_of_subjects']) ? $_GET['number_of_subjects'] : 0); $i++) {
        list($Required_elective, $course, $suject, $score, $credit) = [
            $_GET['Required_elective'][$i],
            $_GET['course'][$i],
            $_GET['subjects'][$i],
            $_GET['score'][$i],
            $_GET['credit'][$i]
        ];

        $GPA = calculateGPA($score, $credit);

        $sql_str = "INSERT INTO `$tableName` (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) 
                    VALUES ('$Required_elective', '$course', '$suject', '$score', '$credit', '$GPA')";
        @mysqli_query($conn, $sql_str);
    }

    if (isset($_GET['suject'])) {
        $suject = $_GET['suject'];
        $sql_str = "DELETE FROM $tableName WHERE `suject`='$suject';";
        @mysqli_query($conn, $sql_str);
    }

    if (isset($_GET['update'])) {
        list($Required_elective, $course, $suject, $score, $credit) = [
            $_GET['Required_elective'],
            $_GET['course'],
            $_GET['subjects'],
            $_GET['score'],
            $_GET['credit']
        ];

        $GPA = calculateGPA($score, $credit);

        $sql_str = "UPDATE `$tableName` 
                    SET `Required_elective`='$Required_elective', `course`='$course', `score`='$score', `credit`='$credit', `GPA`='$GPA' 
                    WHERE `suject`='$suject';";
        @mysqli_query($conn, $sql_str);
    }

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
    if (@mysqli_num_rows($res2) == 0) {
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
} else if (!isset($_GET['number_of_subjects']) && mysqli_num_rows($res) != 0) {
    $sql_str = "SELECT * FROM `$totalname` WHERE `table_name`='$tableName'";
    $res = mysqli_query($conn, $sql_str);
    $row_array = mysqli_fetch_assoc($res);
    $GPA_total = $row_array['GPA_total'];
    $score_total = $row_array['score_total'];
    $credit_total = $row_array['credit_total'];
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <meta charset="UTF-8">
    <title>GPA計算網站</title>
</head>
<body>
<br>
<center>
<h1>GPA與學期成績計算網站</h1><br>
<form action="GPA_credits.php" method="GET">
    <input type="submit" value="計算總學分">
</form>
<hr>

<!-- 學年度和排序選擇表單 -->
<form action="" method="get">
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
    排序：
    <select name="sort" required onchange="this.form.submit()">
        <option value="Required_elective" <?= $sort == 'Required_elective' ? 'selected' : '' ?>>選必修</option>
        <option value="course" <?= $sort == 'course' ? 'selected' : '' ?>>課程分類</option>
        <option value="suject" <?= $sort == 'suject' ? 'selected' : '' ?>>科目</option>
        <option value="score" <?= $sort == 'score' ? 'selected' : '' ?>>成績</option>
        <option value="credit" <?= $sort == 'credit' ? 'selected' : '' ?>>學分</option>
        <option value="GPA" <?= $sort == 'GPA' ? 'selected' : '' ?>>GPA</option>
    </select>
    升降序：
    <select name="order" required onchange="this.form.submit()">
        <option value="asc" <?= $order == 'asc' ? 'selected' : '' ?>>升序</option>
        <option value="desc" <?= $order == 'desc' ? 'selected' : '' ?>>降序</option>
    </select>
    <br>
</form>

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
    <tr>
        <th>選必修</th>
        <th>課程分類</th>
        <th>科目</th>
        <th>成績</th>
        <th>學分</th>
        <th>GPA</th>
        <th>功能</th>
    </tr>

    <?php while ($row_array = mysqli_fetch_assoc($res)): ?>
        <tr>
            <?php foreach ($row_array as $key => $item): ?>
                <td><font <?= ($key == 'score' && $item < 60) ? 'color="red"' : '' ?>><?= $item; ?></font></td>
            <?php endforeach; ?>
            <td>
                <a href="GPA_update.php?suject=<?= $row_array['suject'] ?>">修改</a>
                <a href="GPA.php?suject=<?= $row_array['suject'] ?>">刪除</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<p align="center">學期成績：<?= $score_total ?> (計算公式：(各科成績 * 各科學分) 全相加後 / 總學分)</p>
<p align="center">學期總獲得學分：<?= $credit_total ?></p>
<p align="center">學期GPA：<?= $GPA_total ?></p>

<?php
} else {
    echo '<p align="center">你沒有資料喔</p>';
}
?>

<form align="center" action="GPA_insert.php" method="POST">
    <input type="submit" value="新增資料">
</form>
	<a href='GPA_register.php'>註冊帳號</a>
	<a href='GPA.php?delete=true'>此帳號刪除</a>
    <a href='GPA_login.php?logout=true'>登出</a><br>

<p align="center">高科成績GPA計算方式：</p>
<table align="center" border="1" style="text-align: center;">
    <colgroup>
        <col style="width: 200px;">
        <col style="width: 200px;">
    </colgroup>
    <tr>
        <th>成績</th>
        <th>GPA</th>
    </tr>
    
    <?php
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
</table>

</center>
</body>
</html>
