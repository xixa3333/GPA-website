# GPA 成績管理系統

這是一套以 PHP、MySQL 與 Python 製作的學業成績管理網站，可記錄各學期課程、計算 GPA、查看學分統計，並從特定格式的成績單 PDF 匯入資料。

## 主要功能

- 註冊、登入、忘記密碼與帳號管理
- 新增、修改與刪除課程成績
- 依學期查看 GPA、學分與統計圖表
- 依課程類型、必選修等條件整理資料
- 上傳 PDF 成績單並自動匯入成績

## 執行需求

- XAMPP（Apache、PHP、MySQL/MariaDB）
- Composer
- Python 3
- Python 套件 `pdfplumber`

## 安裝方式

1. 下載或複製此專案至 XAMPP 的網站目錄，例如：

   ```text
   C:\xampp\htdocs\GPA-website
   ```

2. 在專案的 `app` 目錄安裝 PHP 相依套件：

   ```powershell
   cd C:\xampp\htdocs\GPA-website\app
   composer install
   ```

3. 安裝 PDF 解析套件：

   ```powershell
   py -m pip install pdfplumber
   ```

4. 啟動 XAMPP 的 Apache 與 MySQL。

5. 開啟 phpMyAdmin，建立 `C112151111` 資料庫，並匯入 [`database/init.sql`](database/init.sql)。建議資料庫使用 `utf8mb4` 編碼。

6. 瀏覽 [http://localhost/GPA-website/](http://localhost/GPA-website/)，系統會自動進入登入頁。

## 設定

未設定環境變數時，系統預設連線至本機 MySQL、使用 `root` 帳號與空白密碼。正式環境請在 Apache/PHP 執行環境中設定下列變數：

| 環境變數 | 用途 | 預設值 |
| --- | --- | --- |
| `GPA_DB_HOST` | 資料庫主機 | `127.0.0.1` |
| `GPA_DB_USER` | 資料庫帳號 | `root` |
| `GPA_DB_PASSWORD` | 資料庫密碼 | 空白 |
| `GPA_DB_NAME` | 資料庫名稱 | `C112151111` |
| `GPA_PYTHON` | Python 執行檔路徑 | 自動偵測，找不到時使用系統 `PATH` |
| `GPA_SMTP_HOST` | SMTP 主機 | `smtp.gmail.com` |
| `GPA_SMTP_PORT` | SMTP 連接埠 | `587` |
| `GPA_SMTP_USERNAME` | 寄件帳號 | 無 |
| `GPA_SMTP_PASSWORD` | SMTP App Password | 無 |
| `GPA_SMTP_FROM` | 寄件地址 | 同寄件帳號 |

若未設定 SMTP 帳密，網站仍可執行，但寄送驗證信與重設密碼郵件的功能無法使用。請勿把任何密碼、App Password 或個人成績單提交到 GitHub。

## 專案結構

```text
GPA-website/
├─ index.php          # 網站入口
├─ README.md          # 使用說明
├─ app/               # PHP、前端資源與 PDF 解析程式
│  └─ uploads/        # 暫存上傳檔案（不提交 Git）
└─ database/
   └─ init.sql        # 初始資料庫結構與資料
```

`app/vendor/` 由 Composer 產生，因此不收錄於版本控制。GitHub 也不收錄使用者上傳檔案與測試成績單。

## PDF 匯入提醒

PDF 解析器是依目前支援的成績單版面擷取資料；若學校更改欄位名稱或排版，可能需要調整 `app/PDF.py`。上傳前請確認 PDF 不含不必要的個人敏感資訊。

## 授權與發行

目前專案尚未附授權條款，也沒有獨立安裝程式。一般使用者可直接從 GitHub 下載原始碼並依本頁安裝；等到版本、資料庫升級流程與授權條款穩定後，再建立 GitHub Release 會更合適。
