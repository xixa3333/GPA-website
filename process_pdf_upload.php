<?php
session_start();
include("db_connect.php"); // 確保這個檔案連接了資料庫
include("GPA_calculate.php"); // 確保這個檔案包含了 calculateGPA 函數

// 確保只有登入的使用者可以操作此腳本
if (!isset($_SESSION["user"]) || $_SESSION["user"] == ""){
    header("Location: GPA_login.php");
    exit();
}

// 偵錯用：啟用錯誤顯示 (在開發環境中開啟)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 直接從 Session 獲取當前登入的用戶帳號
$accountUser = $_SESSION['user']; 
// 對用戶帳號進行清理，確保其在用於表名等資料庫操作時是安全的
$safe_account_user = preg_replace('/[^a-zA-Z0-9_]/', '', $accountUser); 

// 這是用於重新計算並儲存 total 數據的輔助函數
// 請確保此函數在文件中可用 (直接寫在這裡或引入其他檔案)
function recalculateAndStoreTotalData_GPA($conn, $tableName, $totalname, $gpa_method) {
    // 重新計算總GPA、成績和學分
    $GPA_total = 0;
    $score_total = 0;
    $credit_total = 0;
    $Original_credits = 0;

    $sql_select_subjects = "SELECT `score`, `credit`, `GPA` FROM `" . mysqli_real_escape_string($conn, $tableName) . "`";
    $res_subjects = mysqli_query($conn, $sql_select_subjects);

    if (!$res_subjects) {
        error_log("重新計算總計失敗，查詢科目數據錯誤: " . mysqli_error($conn) . " 表: " . $tableName);
        return false;
    }

    while ($row_subject = mysqli_fetch_assoc($res_subjects)) {
        $score = (string)$row_subject['score']; // 直接獲取原始 score
        $gpa_val = (float)$row_subject['GPA'];
        $credit_val = (int)$row_subject['credit'];

        if (is_numeric($score)) {
            $score_numeric = (float)$score;
            $Original_credits += $credit_val;
            $score_total += ($score_numeric * $credit_val);
            
            if ($score_numeric < 60) {
                // 不計入獲得學分
            } else {
                $credit_total += $credit_val;
                $GPA_total += ($gpa_val * $credit_val);
            }
        } else if ($score == '合格') {
             $credit_total += $credit_val;
        }
    }
    
    if ($Original_credits == 0 && $credit_total == 0) {
        $GPA_total = 0;
        $score_total = 0;
        $sql_delete_total = "DELETE FROM `" . mysqli_real_escape_string($conn, $totalname) . "` WHERE `table_name` = '" . mysqli_real_escape_string($conn, $tableName) . "'";
        mysqli_query($conn, $sql_delete_total);
        return true;
    }
    
    $GPA_calculated_final = ($credit_total > 0) ? ($GPA_total / $credit_total) : 0;
    $score_calculated_final = ($Original_credits > 0) ? ($score_total / $Original_credits) : 0;

    $GPA_calculated_final = number_format($GPA_calculated_final, 2);
    $score_calculated_final = number_format($score_calculated_final, 2);

    $escaped_total_name = mysqli_real_escape_string($conn, $totalname);
    $escaped_table_name_for_total = mysqli_real_escape_string($conn, $tableName);

    $sql_check_total_table = "SHOW TABLES LIKE '" . $escaped_total_name . "'";
    $res_check_total = mysqli_query($conn, $sql_check_total_table);

    if (!$res_check_total) {
        error_log("檢查 total 資料表失敗: " . mysqli_error($conn) . " SQL: " . $sql_check_total_table);
        return false;
    }

    if (mysqli_num_rows($res_check_total) == 0) {
        $createTableSQL = "CREATE TABLE `" . $escaped_total_name . "` (
            `GPA_total` FLOAT,
            `score_total` FLOAT,
            `credit_total` INT(5),
            `Original_credits` INT(5),
            `GPA_sort` VARCHAR(12),
            `table_name` VARCHAR(50),
            PRIMARY KEY (`table_name`)
        )";
        if (!mysqli_query($conn, $createTableSQL)) {
            error_log("創建 total 資料表錯誤: " . mysqli_error($conn) . " SQL: " . $createTableSQL);
            return false;
        }
    }

    $sql_check_record = "SELECT * FROM `" . $escaped_total_name . "` WHERE `table_name` = ?";
    $stmt_check_record = mysqli_prepare($conn, $sql_check_record);
    if (!$stmt_check_record) {
        error_log("準備檢查 total 記錄語句失敗: " . mysqli_error($conn));
        return false;
    }
    mysqli_stmt_bind_param($stmt_check_record, "s", $escaped_table_name_for_total);
    mysqli_stmt_execute($stmt_check_record);
    $res_check_record = mysqli_stmt_get_result($stmt_check_record);
    mysqli_stmt_close($stmt_check_record);

    if (mysqli_num_rows($res_check_record) > 0) {
        $sql_update = "UPDATE `" . $escaped_total_name . "`
                       SET `GPA_total`=?, `score_total`=?, `credit_total`=?, `Original_credits`=?, `GPA_sort`=?
                       WHERE `table_name`=?";
        $stmt_update = mysqli_prepare($conn, $sql_update);
        if (!$stmt_update) {
            error_log("準備更新 total 記錄語句失敗: " . mysqli_error($conn));
            return false;
        }
        mysqli_stmt_bind_param($stmt_update, "ddiiss", 
            $GPA_calculated_final, $score_calculated_final, $credit_total, $Original_credits, $gpa_method, $escaped_table_name_for_total
        );
        if (!mysqli_stmt_execute($stmt_update)) {
            error_log("更新 total 記錄失敗: " . mysqli_error($conn) . " 表: " . $escaped_table_name_for_total);
            return false;
        }
        mysqli_stmt_close($stmt_update);
    } else {
        $sql_insert = "INSERT INTO `" . $escaped_total_name . "` 
                       (`table_name`, `GPA_total`, `score_total`, `credit_total`, `Original_credits`, `GPA_sort`)
                       VALUES (?, ?, ?, ?, ?, ?)";
        $stmt_insert = mysqli_prepare($conn, $sql_insert);
        if (!$stmt_insert) {
            error_log("準備插入 total 記錄語句失敗: " . mysqli_error($conn));
            return false;
        }
        mysqli_stmt_bind_param($stmt_insert, "sddiis", 
            $escaped_table_name_for_total, $GPA_calculated_final, $score_calculated_final, $credit_total, $Original_credits, $gpa_method
        );
        if (!mysqli_stmt_execute($stmt_insert)) {
            error_log("插入 total 記錄失敗: " . mysqli_error($conn) . " 表: " . $escaped_table_name_for_total);
            return false;
        }
        mysqli_stmt_close($stmt_insert);
    }
    return true;
}


// 接收表單提交
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES["pdfFile"])) {
    $target_dir = "uploads/"; 
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true); 
    }

    $uploaded_file_name = basename($_FILES["pdfFile"]["name"]);
    $target_file = $target_dir . uniqid() . "_" . preg_replace("/[^a-zA-Z0-9_\-\.]/", "", $uploaded_file_name);

    $accountUser = $_SESSION['user']; 
    $safe_account_user = preg_replace('/[^a-zA-Z0-9_]/', '', $accountUser); 

    if ($_FILES["pdfFile"]["error"] !== UPLOAD_ERR_OK) {
        $error_message = "檔案上傳失敗，錯誤代碼: " . $_FILES["pdfFile"]["error"];
        header("Location: gpa_upload_form.php?status=error&msg=" . urlencode($error_message));
        exit();
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $file_mime_type = finfo_file($finfo, $_FILES["pdfFile"]["tmp_name"]);
    finfo_close($finfo);

    if ($file_mime_type !== 'application/pdf') {
        header("Location: gpa_upload_form.php?status=error&msg=" . urlencode("只允許上傳 PDF 檔案。實際類型: " . $file_mime_type));
        exit();
    }

    if (move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $target_file)) {
        $python_executable = 'C:/Users/USER/AppData/Local/Programs/Python/Python312/python.exe'; 
        $python_script = 'PDF.py'; 
        
        $command = escapeshellcmd($python_executable) . " " . escapeshellarg($python_script) . " " . escapeshellarg($target_file);
        
        $python_output = shell_exec($command . ' 2>&1');

        if (file_exists($target_file)) {
            unlink($target_file);
        }

        error_log("Python Command: " . $command);
        error_log("Raw Python Output: " . $python_output);

        $processed_data = json_decode($python_output, true);

        if (json_last_error() !== JSON_ERROR_NONE || !is_array($processed_data)) {
            $error_msg = "PDF 處理失敗。請檢查 PDF 內容或伺服器日誌。";
            $decoded_error = json_decode($python_output, true);
            if (isset($decoded_error['error'])) {
                $error_msg = "Python 腳本錯誤: " . $decoded_error['error'];
                if (isset($decoded_error['traceback'])) {
                    error_log("Python Traceback: " . $decoded_error['traceback']);
                }
            }
            header("Location: gpa_upload_form.php?status=error&msg=" . urlencode($error_msg));
            exit();
        }

        $all_semesters_processed_successfully = true;
        $failed_semesters = [];
        $gpa_method = $_COOKIE['account']['GPA_sort'] ?? 'NKUST'; 

        foreach ($processed_data as $year_semester_key => $subjects_list) {
            if (empty($subjects_list)) {
                continue;
            }

            $current_tableName = "table_" . $year_semester_key . $safe_account_user;
            $current_totalname = "total" . $safe_account_user;

            $escaped_current_table_name = mysqli_real_escape_string($conn, $current_tableName);
            $check_table_sql = "SHOW TABLES LIKE '" . $escaped_current_table_name . "'";
            $res_check = mysqli_query($conn, $check_table_sql);

            if (!$res_check) {
                error_log("檢查科目資料表失敗: " . mysqli_error($conn) . " SQL: " . $check_table_sql);
                $all_semesters_processed_successfully = false;
                continue; 
            }

            if (mysqli_num_rows($res_check) == 0) {
                $createTableSQL = "CREATE TABLE `" . $escaped_current_table_name . "` (
                    `Required_elective` ENUM('必修', '選修') NOT NULL DEFAULT '必修',
                    `course` ENUM('專業', '通識') NOT NULL DEFAULT '專業',
                    `suject` VARCHAR(30) NOT NULL,
                    `score` VARCHAR(10) DEFAULT NULL,
                    `credit` INT(2) DEFAULT NULL,
                    `GPA` FLOAT DEFAULT NULL,
                    PRIMARY KEY (`suject`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
                if (!mysqli_query($conn, $createTableSQL)) {
                    error_log("創建科目資料表失敗: " . mysqli_error($conn) . " SQL: " . $createTableSQL);
                    $all_semesters_processed_successfully = false;
                    continue;
                }
            } else {
                $truncate_sql = "TRUNCATE TABLE `" . $escaped_current_table_name . "`";
                if (!mysqli_query($conn, $truncate_sql)) {
                    error_log("清空科目資料表失敗: " . mysqli_error($conn) . " SQL: " . $truncate_sql);
                    $all_semesters_processed_successfully = false;
                    continue;
                }
            }

            foreach ($subjects_list as $subject_data) {
                $required_elective = $subject_data['Required_elective'];
                $course_type = $subject_data['course'];
                $subject_name = $subject_data['suject'];
                $score = $subject_data['score'];
                $credit = $subject_data['credit'];

                $calculated_gpa = 0.0;
                if (is_numeric($score) && function_exists('calculateGPA')) {
                    $calculated_gpa = calculateGPA((float)$score, $gpa_method);
                } else if ($score === '合格') {
                    $calculated_gpa = 0.0; 
                } else {
                    $calculated_gpa = 0.0;
                }
                
                $insert_subject_sql = "INSERT INTO `" . $escaped_current_table_name . "` 
                    (`Required_elective`, `course`, `suject`, `score`, `credit`, `GPA`) VALUES 
                    (?, ?, ?, ?, ?, ?)";
                
                $stmt_insert = mysqli_prepare($conn, $insert_subject_sql);
                if (!$stmt_insert) {
                    error_log("準備插入科目語句失敗: " . mysqli_error($conn) . " SQL: " . $insert_subject_sql);
                    $all_semesters_processed_successfully = false;
                    continue;
                }
                
                mysqli_stmt_bind_param($stmt_insert, "ssssid", 
                    $required_elective, 
                    $course_type, 
                    $subject_name, 
                    $score,
                    $credit, 
                    $calculated_gpa
                );

                if (!mysqli_stmt_execute($stmt_insert)) {
                    error_log("插入科目失敗: " . mysqli_error($conn) . " 科目: " . $subject_name . " 錯誤: " . mysqli_stmt_error($stmt_insert));
                    $all_semesters_processed_successfully = false;
                }
                mysqli_stmt_close($stmt_insert);
            }
            
            if (!recalculateAndStoreTotalData_GPA($conn, $current_tableName, $current_totalname, $gpa_method)) {
                $all_semesters_processed_successfully = false;
                error_log("更新總計表失敗：學期 " . $year_semester_key);
            }
        }

        if ($all_semesters_processed_successfully) {
            header("Location: gpa_upload_form.php?status=success&msg=" . urlencode("所有學期成績已成功匯入。"));
        } else {
            header("Location: gpa_upload_form.php?status=error&msg=" . urlencode("部分學期成績匯入失敗：" . implode(", ", $failed_semesters) . "。請檢查伺服器日誌。"));
        }
        exit();

    } else {
        header("Location: gpa_upload_form.php?status=error&msg=" . urlencode("移動上傳檔案失敗。請檢查 uploads 目錄權限。"));
        exit();
    }
} else {
    header("Location: gpa_upload_form.php?status=error&msg=" . urlencode("無效的檔案上傳請求或無檔案。"));
    exit();
}

// 關閉資料庫連接
if (isset($conn) && $conn) {
    mysqli_close($conn);
}
?>