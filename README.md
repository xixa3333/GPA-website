# GPA Compass 成績與學分管理

GPA Compass 是給大學生使用的免費成績管理工具，可記錄各學期分數、自動換算 GPA、觀察成績趨勢，並用圓餅圖追蹤畢業學分目標。

## 立即使用

**[開啟 GPA Compass 公開網站](https://gpa-compass-tw.gpt-sub-team.chatgpt.site)**

使用 ChatGPT 帳號登入後即可新增課程。課程、學分目標及 GPA 制度都會依登入帳號分開保存，不會與其他使用者共用。

## 主要功能

- 以 0–100 分輸入成績，自動換算 GPA
- 支援高科大 4.0、臺灣常用 4.0、臺灣常用 4.3 制度
- 顯示學期 GPA、平均分數與學分趨勢
- 類別包含系必修、系選修、通識、共同必修、自由選修及自訂內容
- 設定各領域與總畢業學分目標，以中央顯示學分數的圓餅圖追蹤
- 使用雲端資料庫持久保存資料，並以登入帳號隔離個人紀錄

## 使用方式

1. 開啟公開網站並登入。
2. 在「新增課程」輸入學期、名稱、類別、學分與分數。
3. 在個人設定選擇 GPA 制度並設定各類學分目標。
4. 從趨勢圖與學分圓餅圖查看學習進度。

## 隱私與安全

- 每筆課程與個人化設定都以登入帳號作為資料存取邊界。
- 更新、刪除與查詢均同時驗證資料所屬帳號。
- 專案不保存 Gmail App Password、GitHub Token 或其他私密憑證。
- 請勿將 `.env`、郵件密碼或服務權杖提交到 GitHub。

## 開發者說明

主要公開網站位於 `web/`，採前後端 API 分離與領域／資料存取分層：

```text
web/
├─ app/                 # React 頁面與 API 路由
├─ lib/domain.ts        # 輸入驗證與核心規則
├─ lib/gpa.ts           # GPA 換算策略
├─ lib/repositories.ts  # D1 資料存取層
├─ db/                  # Drizzle 資料表定義
└─ tests/               # 單元、整合、邊界及資安測試
```

本機開發需要 Node.js 22.13 以上：

```powershell
cd web
npm install
npm run dev
```

完整驗證：

```powershell
npm run test:all
```

舊版 XAMPP/PHP 程式保留於 `app/`，資料庫初始化檔位於 `database/init.sql`。根目錄只保留入口、README 與專案必要設定；網站實作集中於各自目錄。

## GitHub Releases

目前不需要上傳 Release 附件：網站由原始碼自動建置部署，依賴套件可由 lockfile 重現。只有在日後提供離線安裝包、桌面版或正式版本下載檔時，才適合使用 GitHub Releases。
