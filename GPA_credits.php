<?php
session_start();
include("db_connect.php");
include("GPA_calculate.php");

// 判斷無登入帳號
if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){
    header("Location: GPA_login.php");
    exit();
}

//四種學分與成績總平均
$totalname = 'total' . $_COOKIE['account']['user'];
$Required_majors = 0;
$common = 0;
$Elective_majors = 0;
$General_Education = 0;
$GPA_total2 = 0;
$score_total2 = 0;
$Original_credits_total = 0;
$credit_total2 = 0;

//用來做up跟down位置交換
function change_updown($picture){
	for ($i = 0; $i < count($picture);) {
		// 將 $i 和 $i+1 位置的數據進行交換
		if ($i + 1 < count($picture)) {
			preg_match('/(\d+)(up|down)/', $picture[$i + 1][0], $matches_a);
			preg_match('/(\d+)(up|down)/', $picture[$i][0], $matches_b);
			if($matches_a[1]==$matches_b[1]){
				$temp = $picture[$i];
				$picture[$i] = $picture[$i + 1];
				$picture[$i + 1] = $temp;
				$i += 2;
				continue;
			}
		}
		$i += 1;
	}
	return $picture;
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
    <p>
    <h1 align="center">GPA與學期成績計算網站</h1><br>
    <hr>
    <center>
    <?php
    $sql_str = "SELECT * FROM `$totalname`";
    $res = mysqli_query($conn, $sql_str);
    $data_value = @mysqli_num_rows($res);
    if (@$data_value != 0) {
		
		//如果資料有一組以上時建立圖表所需要的資料
		if ($data_value > 1) {
			$score_picture = [];
			$credit_picture = [];
			$GPA_picture = [];
		}
		
        while($row_array = mysqli_fetch_assoc($res)){
            foreach($row_array as $key => $item){
                if ($key == 'GPA_total') $GPA_total = $item;
                elseif ($key == 'score_total') $score_total = $item;
                elseif ($key == 'credit_total') $credit_total = $item;
                elseif ($key == 'Original_credits') $Original_credits = $item;
				elseif ($key == 'GPA_sort') $GPA_sort = $item;
                elseif ($key == 'table_name'){
					if($GPA_sort!=$_COOKIE['account']['GPA_sort'])$GPA_total = 0;//GPA計算方式不同要重新計算GPA
					
					if ($data_value > 1) {//如果資料有一組以上時建立圖表所需要的資料
						$table_name = $item;
						$table_name = str_replace($_SESSION["user"], '', $table_name);//建立圖表橫向的名字
					}
					
                    $sql_str2 = "SELECT * FROM $item";
                    $res2 = mysqli_query($conn, $sql_str2);
                    while($row_array2 = mysqli_fetch_assoc($res2)){
                        foreach($row_array2 as $key2 => $item2){
                            if ($key2 == 'Required_elective') $Required_elective = $item2;
                            elseif ($key2 == 'score') $score = $item2;
                            elseif ($key2 == 'course') $course = $item2;
                            elseif ($key2 == 'credit') $credit = $item2;
							elseif ($key2 == 'suject') $suject = $item2;
                        }
						
						if($GPA_sort!=$_COOKIE['account']['GPA_sort']){
							$GPA = calculateGPA($score, $_COOKIE['account']['GPA_sort']);
							$sql_str3 = "UPDATE `$item` SET `GPA`='$GPA' WHERE `suject`='$suject';";
							mysqli_query($conn, $sql_str3);
							$GPA_total += ($GPA * $credit);
						}
						
                        if ($Required_elective == '必修'){
                            if ($course == '專業') $Required_majors += (($score >= 60) ? $credit : 0);
                            else $common += (($score >= 60) ? $credit : 0);
                        } else {
                            if ($course == '專業') $Elective_majors += (($score >= 60) ? $credit : 0);
                            else $General_Education += (($score >= 60) ? $credit : 0);
                        }
                    }
					
					if($GPA_sort!=$_COOKIE['account']['GPA_sort']){
						@$GPA_total /= $Original_credits;
						$GPA_total = number_format($GPA_total, 2);
						$sql_str2 = "UPDATE $totalname
							SET `GPA_total`='$GPA_total' ,`GPA_sort`='".$_COOKIE['account']['GPA_sort']."'
								WHERE `table_name`='$item';";
						@mysqli_query($conn, $sql_str2);
					}
                }
            }
			
			if($score_total==0 && $Original_credits==1)$Original_credits=0;//將虛學分換回來
			
            if ($data_value > 1) {
                $score_picture[] = [$table_name, $score_total];
                $credit_picture[] = [$table_name, $credit_total];
                $GPA_picture[] = [$table_name, $GPA_total];
            }
			
			//計算全部成績總平均
            $GPA_total2 += ($Original_credits * $GPA_total);
            $score_total2 += ($Original_credits * $score_total);
            $Original_credits_total += $Original_credits;
            $credit_total2 += $credit_total;
        }
		
		if ($data_value > 1) {
			$score_picture=change_updown($score_picture);
			$credit_picture=change_updown($credit_picture);
			$GPA_picture=change_updown($GPA_picture);
		}
		
		if($Original_credits_total==0)$Original_credits_total=1;//轉換為虛學分以免變nan
		//計算全部成績總平均
        $GPA_total2 /= $Original_credits_total;
        $score_total2 /= $Original_credits_total;
        $GPA_total2 = number_format($GPA_total2, 2);
        $score_total2 = number_format($score_total2, 2);
        ?>
		<p>
        <div class="container">
            <div class="item2">專業選修：<?php echo $Elective_majors; ?>/47</div>
            <div class="item2">通識：<?php echo $General_Education; ?>/16</div>
        </div>
        <div class="container">
            <div class="item2">專業必修：<?php echo $Required_majors; ?>/53</div>
            <div class="item2">共同必修：<?php echo $common; ?>/12</div>
        </div>

        <div class="container">
            <div class="item">總平均成績：<?php echo $score_total2; ?></div>
            <div class="item">總獲得學分：<?php echo $credit_total2; ?></div>
            <div class="item">總平均GPA：<?php echo $GPA_total2; ?></div>
        </div>
		
		<p/>
		
        <?php if ($data_value > 1) { ?>
		<div class="container">
            <div id="container" style="width:500px;height:370px;"></div>
            <div id="container2" style="width:500px;height:370px;"></div>
            <div id="container3" style="width:500px;height:370px;"></div>
		</div>
            <script src="https://cdn.anychart.com/releases/8.11.0/js/anychart-base.min.js"></script>
            <script>
                // 定義一個繪製圖表的函數，接受數據和容器ID作為參數
                function drawChart(data, containerId,firstSeries_name,lineColor) {
                    anychart.onDocumentReady(function () {
                        // 创建数据集
                        var dataSet = anychart.data.set(data);

                        // 映射所有数据到系列
                        var firstSeriesData = dataSet.mapAs({x: 0, value: 1});

                        // 创建折线图
                        var chart = anychart.line();

                        // 创建系列及命名
                        var firstSeries = chart.line(firstSeriesData);
                        firstSeries.name(firstSeries_name);
						
						//修改顏色
						firstSeries.stroke(lineColor);
						
                        // 添加图标
                        chart.legend().enabled(true);

                        // 设置在哪里展现折线图
                        chart.container(containerId);

                        // 绘制折线图
                        chart.draw();
                    });
                }

                // 初始化圖表數據
                var scoreData = <?php echo json_encode($score_picture); ?>;
                var creditData = <?php echo json_encode($credit_picture); ?>;
                var GPAData = <?php echo json_encode($GPA_picture); ?>;
				var GPAname = <?php echo json_encode($_COOKIE['account']['GPA_sort']); ?>;
				
				if(GPAname=='NKUST')GPAname='學期高科GPA4.0';
				else if(GPAname=='TW0')GPAname='學期台灣GPA4.0';
				else GPAname='學期台灣GPA4.3';
				
                // 繪製圖表
                drawChart(scoreData, 'container','成績','rgb(150, 150, 255)');
                drawChart(creditData, 'container2','學分','rgb(255, 150, 150)');
                drawChart(GPAData, 'container3',GPAname,'rgb(150,255,150)');
            </script>
        <?php } ?>
    <?php
    } else {
        echo "沒有資料喔<br>";
    }
    mysqli_close($conn);
    ?>
    <p>
    <input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
    </center>
</body>
</html>