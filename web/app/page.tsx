import Link from "next/link";
import { chatGPTSignInPath, getChatGPTUser } from "./chatgpt-auth";

export default async function Home() {
  const user = await getChatGPTUser();
  const dashboardHref = user ? "/dashboard" : chatGPTSignInPath("/dashboard");

  return (
    <main>
      <nav className="nav shell" aria-label="主要導覽">
        <Link className="brand" href="/" aria-label="GPA Compass 首頁">
          <span className="brand-mark">G</span>
          <span>GPA Compass</span>
        </Link>
        <Link className="nav-link" href={dashboardHref}>
          {user ? "前往我的儀表板" : "使用 ChatGPT 登入"}
        </Link>
      </nav>

      <section className="hero shell">
        <div className="hero-copy">
          <p className="eyebrow">把每一門課，變成看得見的方向</p>
          <h1>成績不只是數字，<br />是下一步的座標。</h1>
          <p className="hero-lead">
            安全保存每學期課程，自動計算 GPA 與累積學分。資料跟著你的帳號，不會因為換裝置或清除瀏覽器而消失。
          </p>
          <div className="hero-actions">
            <Link className="button primary" href={dashboardHref}>開始整理我的成績</Link>
            <a className="button secondary" href="#features">看看能做什麼</a>
          </div>
          <p className="trust-note"><span>●</span> 私人資料分開保存 · 不需要 Gmail 密碼</p>
        </div>

        <div className="hero-visual" aria-label="GPA 儀表板預覽">
          <div className="orbit orbit-one" />
          <div className="orbit orbit-two" />
          <div className="preview-card">
            <div className="preview-top"><span>本學期</span><span className="pill">持續進步</span></div>
            <strong className="preview-gpa">3.82</strong>
            <span className="preview-label">Semester GPA</span>
            <div className="mini-chart" aria-hidden="true">
              <i style={{ height: "48%" }} /><i style={{ height: "62%" }} /><i style={{ height: "54%" }} /><i style={{ height: "80%" }} /><i style={{ height: "92%" }} />
            </div>
            <div className="preview-stats"><span><b>18</b> 學分</span><span><b>6</b> 門課</span></div>
          </div>
        </div>
      </section>

      <section className="feature-section" id="features">
        <div className="shell">
          <p className="eyebrow">簡單、持久、只屬於你</p>
          <h2>少一點試算表，多一點掌握感。</h2>
          <div className="feature-grid">
            <article><span className="feature-number">01</span><h3>即時計算</h3><p>輸入學分與成績，立即看見學期 GPA、累積學分與課程分布。</p></article>
            <article><span className="feature-number">02</span><h3>跨裝置保存</h3><p>成績存放於持久化雲端資料庫，登入後就能延續上次的進度。</p></article>
            <article><span className="feature-number">03</span><h3>資料彼此隔離</h3><p>所有讀寫都在伺服器確認身分，每位使用者只能存取自己的資料。</p></article>
          </div>
        </div>
      </section>

      <footer className="footer shell"><span>GPA Compass</span><span>為學習路線留下清楚紀錄。</span></footer>
    </main>
  );
}
