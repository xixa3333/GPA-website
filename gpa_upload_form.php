<?php
session_start();
// db_connect.php 雖然在此階段不直接操作資料庫，但通常應用程式會在一開始引入
include("db_connect.php"); 

// 確保只有登入的使用者可以操作此腳本
if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){
    header("Location: GPA_login.php");
    exit();
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
			top: 50%;
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
        <header>
        <h1 align="center">GPA與學期成績計算網站</h1><br>
        </header>
        <br/>
        
        <div class="container">
            <form action="process_pdf_upload.php" method="POST" enctype="multipart/form-data">
                <p></p>
                <label for="pdfFile">選擇成績單 PDF 檔案：</label>
                <input type="file" name="pdfFile" id="pdfFile" accept=".pdf" required>
                <p></p>
                <?php
                // 顯示來自 process_pdf_upload.php 的訊息
                if (isset($_GET['status'])) {
                    if ($_GET['status'] == 'success') {
                        echo '<div class="message success">PDF 檔案已成功處理並匯入資料庫。</div>';
                    } elseif ($_GET['status'] == 'error' && isset($_GET['msg'])) {
                        echo '<div class="message error">錯誤：' . htmlspecialchars($_GET['msg']) . '</div>';
                    }
                }
                ?>
                <p></p>
                <div class="container">
                <input type="button" onclick="javascript:location.href='GPA.php'" value="回到主畫面">
                <div class="spacer"></div>
                <input type="submit" value="上傳並處理"/>
                </div>
            </form>

        </div>
    </center>
</body>
</html>