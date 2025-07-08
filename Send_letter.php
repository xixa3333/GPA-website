<?php
	require 'vendor/autoload.php';

	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	
	function sendPasswordResetEmail($email, $subject, $message, $data){
		$name = "帥哥";
		$mail = new PHPMailer();
		$mail->SMTPDebug = 2; // 設置為 2 以顯示詳細的偵錯輸出 (測試時用)
		$mail->IsSMTP();
		$mail->SMTPAuth = true;
		$mail->SMTPSecure = "tls"; // 使用TLS加密连接
		$mail->Host = "smtp.gmail.com";
		$mail->Port = 587; // Gmail的SMTP主机的TLS埠號
		$mail->CharSet = "utf-8";
		$mail->Username = "3333xixa3333@gmail.com"; // Gmail帳號
		$mail->Password = "pkvv xper mthe oywf"; // Gmail密碼
		$mail->From = "3333xixa3333@gmail.com";
		$mail->FromName = "帥哥";
		$mail->Subject = $subject;
		$mail->Body = $message;
		$mail->IsHTML(true);
		$mail->AddAddress($email);

		if (!$mail->Send()) {
			echo $data;
			exit();
		}
	}
?>