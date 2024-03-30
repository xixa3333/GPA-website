<html>
	<head>
		<title>GPA計算網站</title>
		<meta charset="utf-8">
	<head>
<body>
	<br>
	<h1>GPA與學期成績計算網站</h1><br>
	<hr>
	<form method="get">
	<?php
		#輸入科目
		if(!isset($_GET['number_of_subjects']) and !isset($_GET['subjects'])){
			echo '請輸入你有幾科：';
			echo '<input type="number" name="number_of_subjects" required minlength="1" maxlength="3" size="20" />';
			echo '<br>';
			echo '<input type="submit" value="提交"/>';
			echo '<input type="reset" value="重新輸入"/>';
		}
		
		#輸入科目成績等
		if(isset($_GET['number_of_subjects']) and !isset($_GET['subjects'])){
			echo '<input type="hidden" name="number_of_subjects" value='.$_GET['number_of_subjects'].'/>';
			echo '<br>';
			for($i=1;$i<=$_GET['number_of_subjects'];$i++){
				echo '科目'.$i.'：<input type="text" name="subjects['.$i.']" required />  ';
				echo '成績：<input type="number" name="score['.$i.']" required  />  ';
				echo '學分：<input type="number" name="credit['.$i.']" required />';
				echo '<br>';
			}
			echo '<input type="submit" value="提交"/>';
			echo '<input type="reset" value="重新輸入"/>';
		}
		
		#輸出最後的GPA與學期成績等
		if(isset($_GET['score'])){
			#總計數值歸零
			$GPA_total=0;
			$score_total=0;
			$credit_total=0;
			
			#科目表格
			echo '<table border="1" style="text-align: center;">';
			echo '<colgroup>';
			echo '<col style="width: 200px;">';
			echo '<col style="width: 200px;">';
			echo '<col style="width: 200px;">';
			echo '<col style="width: 200px;">';
			echo '</colgroup>';
			echo '<tr><th>科目</th><th>成績</th><th>學分</th><th>GPA</th></tr>';
			for($i=1;$i<=$_GET['number_of_subjects'];$i++){
				
				#判斷GPA
				for($j=0;$j<5;$j++){
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
			echo '</table>';
			
			#計算總計
			$GPA_total/=$credit_total;
			$score_total/=$credit_total;
			$GPA_total=number_format($GPA_total, 2);
			$score_total=number_format($score_total, 2);
			
			#顯示總計
			echo "<p>學期成績：$score_total(計算公式：(各科成績*各科學分)全相加後/總學分)</p>";
			echo "<p>學期總學分：$credit_total</p>";
			echo "<p>學期GPA：$GPA_total</p>";
			echo "<p>高科學期GPA計算方式：</p>";
			echo '<table border="1" style="text-align: center;">';
			echo '<colgroup>';
			echo '<col style="width: 200px;">';
			echo '<col style="width: 200px;">';
			echo '</colgroup>';
			echo '<tr><th>成績</th><th>GPA</th></tr>';
			for($j=0;$j<4;$j++){
				echo '<tr>';
				echo '<td align="center">小於'. (50+$j*10) .'</td>';
				echo '<td align="center">'."$j".'</td>';
				echo '</tr>';
			}
			echo '<tr>';
			echo '<td align="center">大於等於80</td>';
			echo '<td align="center">4</td>';
			echo '</tr>';
			echo '</table>';
		}
	?>
	</form>
</body>
</html>