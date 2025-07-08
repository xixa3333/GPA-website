<?php
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
?>