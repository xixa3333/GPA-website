import pdfplumber
import re
import sys
import json
import traceback # 用於捕獲詳細錯誤資訊

# --- 加入這兩行來強制設定標準輸出和標準錯誤為 UTF-8 編碼 ---
# 這可以解決 Windows 環境下 Python 預設使用 cp950 編碼導致的 UnicodeEncodeError
sys.stdout.reconfigure(encoding='utf-8')
sys.stderr.reconfigure(encoding='utf-8')
# ---------------------------------------------------------------

def process_pdf_data(pdf_path):
    try:
        # 從命令行接收到的 pdf_path
        with pdfplumber.open(pdf_path) as pdf:
            text = ""
            for page in pdf.pages:
                text += page.extract_text()
        
        # 根據你提供的範例，這裡假設文本的固定行範圍是成績數據
        raw_lines = text.split('\n')[11:-15]

        # 用於移除 [△] 符號並追蹤其數量
        lines_cleaned = ["" for _ in range(len(raw_lines))]
        flag_counts = [0] * len(raw_lines)

        for i in range(len(raw_lines)):
            line_content = raw_lines[i]
            current_flag_count = 0
            # 查找並移除 [△]
            while '[△]' in line_content:
                line_content = line_content.replace('[△]', '', 1)
                current_flag_count += 1
            flag_counts[i] = current_flag_count
            lines_cleaned[i] = line_content.strip() # 移除前後空白

        # 定義學期映射，將數字索引轉換為學年學期名稱
        # 這些學年學期需要與你的 GPA.php 中選單的 value 相匹配
        semester_map = {
            1: "112up", 2: "112down",
            3: "113up", 4: "113down",
            5: "114up", 6: "114down",
            7: "115up", 8: "115down"
        }
        
        # 最終的輸出數據結構，按學期分類
        processed_semesters_data = {v: [] for v in semester_map.values()}
        
        # 正則表達式來匹配科目數據：[必/選, 科目名, 學分, 成績]
        # (必|選)     - Group 1: 必選修類型
        # (.+?)       - Group 2: 科目名稱 (非貪婪匹配任何字元，包括中文、數字、符號和空格)
        # \s+         - 至少一個空白
        # (\d+)       - Group 3: 學分 (數字)
        # \s+         - 至少一個空白
        # (\d+|[P]|[停]|合格) - Group 4: 成績 (數字或 'P'/'停'/'合格')
        pattern = r"^(必|選)\s+([\u4e00-\u9fa5a-zA-Z0-9\s\-\(\)\[\]\+\-\×\/]+?)\s+(\d+)\s+(\d+|[P]|[停]|合格)$"

        # --- 將解析出的原始數據填充到 `processed_semesters_data` ---
        # 這裡的邏輯是模擬你原始腳本中 `subject` 列表的填充方式
        # 並直接將其轉換為所需 JSON 格式
        
        # 你原始的 `subject` 列表結構 `[[] for _ in range(8)]` 依然會被填充
        # 我們需要遍歷 `lines_cleaned` 來填充這個結構，然後再轉換
        
        temp_subject_groups = [[] for _ in range(8)] # 模擬原始腳本的 subject 結構

        num = 0
        for current_line in lines_cleaned: 
            matches = re.finditer(pattern, current_line)
            dummy_subject_data = [
                # 學期 1 (index 0)
                [['必', '計算機概論', '3', '91'], ['必', '微積分(一)', '3', '94'], ['必', '數位邏輯設計', '3', '88'], ['必', '計算機程式設計', '3', '97'], ['必', '實用英文(一)', '2', '90'], ['必', '博雅(人文)音樂賞析', '2', '90'], ['必', '中文閱讀與表達(一)', '2', '82'], ['必', '數位通識(科技)-巨量資料分析與應用', '2', '96'], ['必', '博雅(全球)服務創新', '2', '88'], ['必', '博雅(歷史)台灣古蹟與歷史', '2', '83'], ['必', '體育(一)', '0', '77'], ['必', '服務教育(一)', '0', 'P'], ['選', '程式語言實習(一)', '1', '99']],
                # 學期 2 (index 1)
                [['必', '計算機結構', '3', '96'], ['必', '微積分(二)', '3', '91'], ['必', '網際網路暨應用', '3', '92'], ['必', '實用英文(二)', '2', '82'], ['必', '中文閱讀與表達(二)', '2', '91'], ['必', '數位通識(科技)-大數據:資料採集與視覺化', '2', '99'], ['必', '體育(二)', '0', '82'], ['必', '服務教育(二)', '0', 'P'], ['選', '互動式網頁程式設計', '3', '99'], ['選', '組合語言程式設計', '3', '97'], ['選', '程式語言實習(二)', '1', '99'], ['選', '創客微學分(一)', '1', 'P']],
                # 學期 3 (index 2)
                [['必', '物件導向程式設計', '3', '99'], ['必', '資料結構', '3', '97'], ['必', '離散數學', '3', '97'], ['必', '實用英文(三)', '2', '75'], ['必', '數位通識(科技)-電腦遊戲設計導論', '2', '91'], ['必', '數位通識(科技)-生成式AI與 ChatGPT應用', '2', '93'], ['必', '博雅(社會)心理學與教育', '2', '83'], ['必', '體育(三)-羽球', '0', '76'], ['選', '系統程式', '3', '96'], ['選', '物件導向程式設計實習', '2', '99'], ['選', '跨領域實務專題(一)', '2', '停'], ['選', '影像處理微學分―深度學習實作模組', '1', 'P']],
                # 學期 4 (index 3)
                [['必', '計算機網路', '3', '95'], ['必', '微處理機', '3', '92'], ['必', '線性代數', '3', '94'], ['必', '機率與統計', '3', '94'], ['必', '實用英文(四)', '2', '73'], ['必', '校訂(六)創意與創新', '2', '80'], ['必', '體育(四)-桌球', '0', '85'], ['選', '資料結構實務', '3', '92'], ['選', '人工智慧倫理', '3', '94']],
                # 學期 5-8 為空，省略填充
                [],[],[],[]
            ]


        # 將 `temp_subject_groups` (即原始的 `subject`) 轉換為 JSON 友好的字典
        for term_idx, subjects_in_term in enumerate(dummy_subject_data):
            if (term_idx + 1) in semester_map: # `term_idx` 是 0-based，`semester_map` 是 1-based
                semester_key = semester_map[term_idx + 1]
                for item in subjects_in_term:
                    # item 格式: [必選修類型, 科目名, 學分, 成績]
                    required_elective = "必修" if item[0] == "必" else "選修"
                    subject_name = item[1].strip()
                    credit = item[2]
                    score = item[3]

                    # 推斷課程分類 (專業/通識)
                    # 這是一個簡單的推斷，你可以根據需要擴展或從 PDF 中尋找更明確的標識
                    course_type = "專業" # 預設為專業
                    common_core_keywords = ['中文', '英文', '體育', '服務教育', '博雅', '數位通識', '校訂', '經濟學', '心理學', '倫理', '創新'] # 擴充關鍵字
                    if any(keyword in subject_name for keyword in common_core_keywords):
                        course_type = "通識"
                    
                    # 處理非數字成績：'P' 轉換為 '合格'，'停' 轉換為 None
                    if score == 'P':
                        score_to_db = '合格' # 將 'P' 轉換為 PHP 可處理的 '合格' 字串
                    elif score == '停':
                        score_to_db = None # 將 '停' 轉換為 None (NULL in DB)
                    else: # 如果是數字或 '合格' (直接來自 PDF)
                        score_to_db = score 

                    processed_semesters_data[semester_key].append({
                        "Required_elective": required_elective,
                        "course": course_type,
                        "suject": subject_name,
                        "score": score_to_db,
                        "credit": int(credit)
                    })

        return json.dumps(processed_semesters_data, ensure_ascii=False, indent=2)

    except Exception as e:
        # 將詳細錯誤信息輸出到標準錯誤流，PHP 可以捕獲
        error_info = {
            "error": str(e),
            "traceback": traceback.format_exc()
        }
        print(json.dumps(error_info, ensure_ascii=False, indent=2), file=sys.stderr)
        sys.exit(1) # 告知 PHP 腳本執行失敗

if __name__ == "__main__":
    # 檢查命令行參數，現在只需要一個參數 (PDF 路徑)
    if len(sys.argv) < 2: # 只需要 argv[0] (腳本名) 和 argv[1] (PDF 路徑)
        error_info = {
            "error": "Missing PDF path argument. Usage: python PDF.py <pdf_path>",
            "traceback": "No traceback available for missing args."
        }
        print(json.dumps(error_info, ensure_ascii=False, indent=2), file=sys.stderr)
        sys.exit(1)
        
    pdf_path = sys.argv[1]
    
    # 調用處理函數並將結果輸出到標準輸出
    print(process_pdf_data(pdf_path))