<?php
session_start();
include("db_connect.php");
include("GPA_calculate.php");

//判斷無登入帳號
if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){
	header("Location: GPA_login.php");
	exit();
}

//有登入帳號則判斷是否為管理員
else{
	$sql_str = "SELECT * FROM `account` WHERE `user`='".$_SESSION["user"]."';";
	$res = @mysqli_query($conn, $sql_str);
	$row_array = mysqli_fetch_assoc($res);
	$manage = $row_array['manage'];
}

//判斷此網頁已閒置1小時
if (isset($_SESSION['expiretime']) && time() >= $_SESSION['expiretime']) {
    session_unset();
    session_destroy();
    header("Location: GPA_login.php");
    exit();
}

//如果沒有cookie，則預設三個排序的cookie
$date = strtotime(date("Y-m-d 23:59:59"));
if (!isset($_COOKIE ["account"])) {
	setcookie("account[year]",'112up',$date);
	setcookie("account[sort]",'Required_elective',$date);
	setcookie("account[order]",'asc',$date);
	setcookie("account[GPA_sort]",'NKUST',$date);
	if($manage==1){
		$sql_str = "SELECT * FROM `account` WHERE `manage`='0' LIMIT 1;";
		$res = mysqli_query($conn, $sql_str);
		$row_array = mysqli_fetch_assoc($res);
		setcookie("account[user]",$row_array['user'],$date);
	}
	else setcookie("account[user]",$_SESSION['account'],$date);
	
	header ("Location:GPA.php");
	exit();
}

// 更新total的函數
function updateTotalTable($conn, $tableName, $GPA_total, $score_total, $credit_total, $Original_credits,$GPA_sort,$totalname) {
    $sql_str2 = "SELECT * FROM $totalname WHERE `table_name`='$tableName'";
    $res2 = mysqli_query($conn, $sql_str2);
    if (mysqli_num_rows($res2) > 0) {//如果有此表則更新
        $sql_str2 = "UPDATE $totalname
            SET `GPA_total`='$GPA_total', `score_total`='$score_total', `credit_total`='$credit_total', `Original_credits`='$Original_credits' ,`GPA_sort`='$GPA_sort'
				WHERE `table_name`='$tableName';";
    }
	else {//如果無此表則插入
        $sql_str2 = "INSERT INTO $totalname (`table_name`, `GPA_total`, `score_total`, `credit_total`, `Original_credits`,`GPA_sort`) 
            VALUES ('$tableName', '$GPA_total', '$score_total', '$credit_total', '$Original_credits','$GPA_sort')";
    }
    @mysqli_query($conn, $sql_str2);
}

// 處理排序選擇的值
if (isset($_POST['year'])) {
    setcookie("account[year]",$_POST['year'],$date);
	setcookie("account[sort]",$_POST['sort'],$date);
	setcookie("account[order]",$_POST['order'],$date);
	setcookie("account[GPA_sort]",$_POST['GPA_sort'],$date);
    list($year, $sort, $order,$GPA_sort) = [$_POST['year'], $_POST['sort'], $_POST['order'],$_POST['GPA_sort']];
	if(isset($_POST['user'])){
		setcookie("account[user]",$_POST['user'],$date);
		$user=$_POST['user'];
	}
	else $user=$_COOKIE['account']['user'];
} 
else {
    list($year, $sort, $order,$GPA_sort) = [$_COOKIE['account']['year'], $_COOKIE['account']['sort'], $_COOKIE['account']['order'], $_COOKIE['account']['GPA_sort']];
	$user=$_COOKIE['account']['user'];
}

//命名
$tableName = "table_" . $year.$user;
$totalname='total'.$user;
$manage_value=0;//管理員帳號數目

if ($manage==1) {//計算管理員帳號數
	$sql_str = "SELECT * FROM `account` WHERE `manage` = '1';";
	$res = mysqli_query($conn, $sql_str);
	$manage_value=mysqli_num_rows($res);
}

//刪除帳號
if (isset($_GET["enter"])) {
	if($_GET["enter"] == 1 && $manage_value==1 && $manage==1){
		echo '<script>alert("管理員帳號只剩一個，無法刪除");</script>';
		echo '<script>location.href =  "GPA.php";</script>';
		exit();
	}
	if($_GET["enter"] == 1)$user=$_SESSION["user"];//刪除本身的帳號
    $sql_str = "DELETE FROM `account` WHERE `user`='".$user."';";//清除帳號資料
    @mysqli_query($conn, $sql_str);
	
	//清除與帳號相關資料表
	$sql_str = "SELECT table_name FROM information_schema.tables WHERE table_schema = 'C112151111' AND table_name LIKE '%".$user."'";
    $res = @mysqli_query($conn, $sql_str);
	while($row = mysqli_fetch_assoc($res)) {
        $table_name = $row["table_name"];
        $drop_sql = "DROP TABLE `$table_name`";
        @mysqli_query($conn, $drop_sql);
    }
	//清除帳號登入資料
    if($_GET["enter"] == 1){
		session_unset();
		session_destroy();
		header("Location: GPA_login.php");
	}
	//管理員刪除學生帳號後的處理
	else {
		$sql_str = "SELECT * FROM `account`";
		$res = mysqli_query($conn, $sql_str);
		while ($row_array = mysqli_fetch_assoc($res)){
			foreach ($row_array as $key => $item){
				if($key=='user')$account2=$item;
				if($key=='manage')$manage2=$item;
			}
			if($manage2==0)break;
		}
		setcookie("account[user]","$account2",$date);
		header("Location: GPA.php");
	}
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

// 處理新增、刪除和更新資料的POST請求
if (isset($_POST['number_of_subjects']) || isset($_GET['suject']) || isset($_POST['update'])||isset($_POST['GPA_sort'])) {
    for ($i = 1; $i <= (isset($_POST['number_of_subjects']) ? $_POST['number_of_subjects'] : 0); $i++) {//新增科目資料
        list($Required_elective, $course, $suject, $score, $credit) = [
            $_POST['Required_elective'][$i],
            $_POST['course'][$i],
            $_POST['subjects'][$i],
            $_POST['score'][$i],
            $_POST['credit'][$i]
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

    if (isset($_POST['update'])) {//更新資料
        list($Required_elective, $course, $suject, $score, $credit) = [
            $_POST['Required_elective'],
            $_POST['course'],
            $_POST['subjects'],
            $_POST['score'],
            $_POST['credit']
        ];

        $GPA = calculateGPA($score, $GPA_sort);

        $sql_str = "UPDATE `$tableName` 
            SET `Required_elective`='$Required_elective', `course`='$course', `score`='$score', `credit`='$credit', `GPA`='$GPA' 
				WHERE `suject`='$suject';";
        @mysqli_query($conn, $sql_str);
    }
	
	if(isset($_POST['GPA_sort'])){//更新GPA資料
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
		if($Original_credits==0)$Original_credits=1;
		@$GPA_total /= $Original_credits;
		@$score_total /= $Original_credits;
		$GPA_total = number_format($GPA_total, 2);
		$score_total = number_format($score_total, 2);
		
		// 檢查和更新total表的資料
		$sql_str2 = "SHOW TABLES LIKE '$totalname'";
		$res2 = mysqli_query($conn, $sql_str2);
		if (@mysqli_num_rows($res2) == 0) {//沒有表時創立新表
			$createTableSQL = "CREATE TABLE `$totalname` (
				`GPA_total` FLOAT,
				`score_total` FLOAT,
				`credit_total` INT(5),
				`Original_credits` INT(5),
				`GPA_sort` VARCHAR(12),
				`table_name` VARCHAR(50),
				PRIMARY KEY (`table_name`)
			)";

			@mysqli_query($conn, $createTableSQL) or die("創建資料表錯誤");

			$sql_str2 = "INSERT INTO `$totalname` (`table_name`, `GPA_total`, `score_total`, `credit_total`, `Original_credits`,`GPA_sort`) 
						 VALUES ('$tableName', '$GPA_total', '$score_total', '$credit_total', '$Original_credits','$GPA_sort')";

			@mysqli_query($conn, $sql_str2);
		} else {
			updateTotalTable($conn, $tableName, $GPA_total, $score_total, $credit_total, $Original_credits,$GPA_sort,$totalname);
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
		.center-text {
            text-align: center;
        }
		#my_back {
			display: none;
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			background-color: rgba(0, 0, 0, 0.5);
			z-index: 2;
		}
		#my_pic {
			display: none;
			position: fixed;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			background-color: white;
			padding: 20px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
			z-index: 3;
			border-radius:5px;
		}
		#my_pic2 {
			display: none;
			position: fixed;
			top: 7%;
			left: 90%;
			
			background-color: white;
			padding: 20px;
			box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
			z-index: 1;
			border-radius:5px;
		}
	</style>
	
</head>
<body>
<center>

<div class="container" style="justify-content: space-between;align-items: stretch;">
<b style="font-size:17px;">歡迎：<?echo $_SESSION['user'];?></b>
<div></div>
<h1 class="center-text">GPA與學期成績計算網站</h1>
<div class="spacer2"></div>
<img src="https://cdn-icons-png.flaticon.com/512/3502/3502458.png" alt="" id="three_line" style="width:35px;height:35px;"></img>
<div id="my_pic2"></div>
</div>

<hr>
<p>



<!-- 學年度和排序選擇表單 -->
<form action="" method="POST">
<div class="container"">
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
	<?if($manage==1){//判斷為管理員帳號?>
	<div class="spacer"></div>
    學生帳號：
    <select name="user" required onchange="this.form.submit()">
		<?
		$sql_str = "SELECT * FROM `account` WHERE `manage` = '0';";
		$res = mysqli_query($conn, $sql_str);
		//抓取學生帳號
		while ($row_array = mysqli_fetch_assoc($res)){
			foreach ($row_array as $key => $item){
				if($key=='user')$account=$item;
			}
			echo '<option value="' . $account . '" ' . ($user == $account ? 'selected' : '') . '>' . $account . '</option>';
		}
		?>
    </select>
	<?}?>
</div>
</form>

<p>



<?php
// 資料顯示
$sql_str = "SELECT * FROM $tableName ORDER BY $sort $order";
$res = mysqli_query($conn, $sql_str);
if (@mysqli_num_rows($res) != 0) {
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
    <tr style="background-color:rgb(120,120,240);">
        <th>選必修</th>
        <th>課程分類</th>
        <th>科目</th>
        <th>成績</th>
        <th>學分</th>
        <th>GPA</th>
        <th>功能</th>
    </tr>

    <?php while ($row_array = mysqli_fetch_assoc($res)): ?>
        <tr style="background-color:rgb(200,200,250);">
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
	mysqli_close($conn);
}
?>

<p>

<div class="container">
<form action="GPA_credits.php" method="POST">
    <input type="submit" value="計算總學分">
</form>
<div class="spacer"></div>
<input type="button" onclick="openinputInNewWindow()" value="新增資料">
<?if($manage==1){?>
<div class="spacer"></div>
<input type="button" onclick="deleteAccount(2)" value="刪除此帳號">
<?}?>
</div>
<div id="my_back"></div>
<div id="my_pic"></div>

<script>
	function deleteAccount(num) {
        if (confirm("確定要刪除帳號嗎?")) {
            location.href = `GPA.php?enter=${num}`;
        }
    }
	
	function openlineInNewWindow(three_line,flag) {//用來顯示旁邊整行
		three_line.onclick=function(){
			if(flag==0){
				let manageValue = <?php echo json_encode($manage); ?>;
				let GPA_sort = <?php echo json_encode($GPA_sort); ?>;
				let str1='<a href="#" onclick="openTableInNewWindowgrade(); return false;">換算公式</a><hr>';
				let str2 = `<a href="#" onclick='openTableInNewWindow(${JSON.stringify(GPA_sort)}); return false;'>GPA換算</a><hr>`;
				let str3 = "<a href='GPA_forget.php'>修改密碼</a><hr>";
				let str4 = "<a href='GPA_address.php'>修改電子郵件</a><hr>";
				let str5="<a href='GPA_login.php?logout=true'>登出</a><hr>";
				
				let str6='';
				if(manageValue==1)str6 = "<a href='GPA_register.php'>註冊管理員帳號</a><hr>";
				
				let str7 = '<a href="#" onclick="deleteAccount(1); return false;">刪除登入帳號</a>';
				document.getElementById('my_pic2').innerHTML = str1 + str2 + str3 + str4 + str5 + str6 + str7;
				document.getElementById('my_pic2').style.display = "block";
				flag=1;
			}
			else{
				document.getElementById('my_pic2').style.display = "none";
				flag=0;
			}
		}
    }
	
	let flag = 0;
	let three_line = document.getElementById('three_line');
	openlineInNewWindow(three_line,flag);
</script>

<script src="Pop-up_window.js"></script>

</center>
</body>
</html>