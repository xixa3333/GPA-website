<html>
	<head>
		<title>GPA計算網站</title>
		<meta charset="utf-8">
	<head>
<body>
	<br>
	<h1>GPA與學期成績計算網站</h1><br>
	<hr>
	<table border="1" style="text-align: center;">
	<colgroup>
	<col style="width: 200px;">
	<col style="width: 200px;">
	<col style="width: 200px;">
	<col style="width: 200px;">
	</colgroup>
	<tr><th>科目</th><th>成績</th><th>學分</th><th>GPA</th></tr>
			
	<?php
		#輸出最後的GPA與學期成績等
		#總計數值歸零
		$GPA_total=0;
		$score_total=0;
		$credit_total=0;
		
		for($i=1;$i<=$_GET['number_of_subjects'];$i++){
			#判斷GPA
			for($j=1;$j<5;$j++){
				if($_GET['score'][$i]<(50+$j*10)){
					$GPA[$i]=$j;
					break;
				}
			}
			if($_GET['score'][$i]<=100 and $_GET['score'][$i]>=80) $GPA[$i]=4;
				
			#計算總計
			$GPA_total+=($GPA[$i]*$_GET['credit'][$i]);
			$score_total+=($_GET['score'][$i]*$_GET['credit'][$i]);
			$credit_total+=$_GET['credit'][$i];
				
			#以表格顯示資料
			echo '<tr>';
			echo '<td align="center">'.$_GET['subjects'][$i].'</td>';
			echo '<td align="center">'.$_GET['score'][$i].'</td>';
			echo '<td align="center">'.$_GET['credit'][$i].'</td>';
			echo '<td align="center">'.$GPA[$i].'</td>';
			echo '</tr>';
		}
		
		#計算總計
		$GPA_total/=$credit_total;
		$score_total/=$credit_total;
		$GPA_total=number_format($GPA_total, 2);
		$score_total=number_format($score_total, 2);
	?>
	
	</table>
	
	<p>學期成績：<?php echo $score_total ?>(計算公式：(各科成績*各科學分)全相加後/總學分)</p>
	<p>學期總學分：<?php echo $credit_total ?></p>
	<p>學期GPA：<?php echo $GPA_total ?></p>
	
	<p>高科學期GPA計算方式：</p>
	<table border="1" style="text-align: center;">
	<colgroup>
	<col style="width: 200px;">
	<col style="width: 200px;">
	</colgroup>
	<tr><th>成績</th><th>GPA</th></tr>
			
	<?php
		for($j=0;$j<4;$j++){
			echo '<tr>';
			echo '<td align="center">小於'. (50+$j*10) .'</td>';
			echo '<td align="center">'."$j".'</td>';
			echo '</tr>';
		}
	?>
	<tr>
	<td align="center">大於等於80</td>
	<td align="center">4</td>
	</tr>
	</table>
</body>
</html>