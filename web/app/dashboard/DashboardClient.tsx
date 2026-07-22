"use client";

import Link from "next/link";
import { CSSProperties, FormEvent, useCallback, useEffect, useMemo, useState } from "react";
import { GpaMethod, gpaMethodLabels, scoreToGpa } from "../../lib/gpa";

type Course = { id: number; semester: string; name: string; courseClass: string; credits: number; score: number | null };
type Settings = { gpaMethod: GpaMethod; systemRequiredTarget: number; systemElectiveTarget: number; generalTarget: number; commonRequiredTarget: number; freeElectiveTarget: number; totalTarget: number };
type ChartMetric = "score" | "gpa" | "credits";

const classOptions = ["系必修", "系選修", "通識", "共同必修", "自由選修", "自己寫"];
const defaultSettings: Settings = { gpaMethod: "NKUST", systemRequiredTarget: 53, systemElectiveTarget: 47, generalTarget: 16, commonRequiredTarget: 12, freeElectiveTarget: 0, totalTarget: 128 };
const targetKeys: Array<{ label: string; courseClass: string; key: keyof Settings }> = [
  { label: "系必修", courseClass: "系必修", key: "systemRequiredTarget" },
  { label: "系選修", courseClass: "系選修", key: "systemElectiveTarget" },
  { label: "通識", courseClass: "通識", key: "generalTarget" },
  { label: "共同必修", courseClass: "共同必修", key: "commonRequiredTarget" },
  { label: "自由選修", courseClass: "自由選修", key: "freeElectiveTarget" },
];

function CreditDonut({ label, value, target, total = false }: { label: string; value: number; target: number; total?: boolean }) {
  const ratio = target > 0 ? Math.min(value / target, 1) : value > 0 ? 1 : 0;
  const style = { "--donut-angle": `${ratio * 360}deg` } as CSSProperties;
  return <article className={`donut-card${total ? " total" : ""}`}><div className="donut" style={style}><div><strong>{value}</strong><small>/ {target || "—"}</small></div></div><h3>{label}</h3><p>{target > 0 ? `${Math.round(ratio * 100)}% 達成` : "尚未設定目標"}</p></article>;
}

function TrendChart({ rows, metric }: { rows: Array<{ semester: string; score: number; gpa: number; credits: number }>; metric: ChartMetric }) {
  const values = rows.map((row) => row[metric]);
  const max = metric === "score" ? 100 : metric === "gpa" ? Math.max(4.3, ...values) : Math.max(1, ...values);
  const min = 0;
  const points = rows.map((row, index) => {
    const x = rows.length === 1 ? 300 : 40 + index * (520 / (rows.length - 1));
    const y = 185 - ((row[metric] - min) / (max - min || 1)) * 145;
    return { x, y, label: row.semester, value: row[metric] };
  });
  if (!rows.length) return <p className="empty-state">新增課程後會顯示學期趨勢。</p>;
  return <div className="trend-scroll"><svg className="trend-chart" viewBox="0 0 600 235" role="img" aria-label="各學期趨勢曲線">
    {[40, 88, 136, 184].map((y) => <line key={y} x1="35" y1={y} x2="570" y2={y} className="chart-grid" />)}
    <polyline points={points.map((point) => `${point.x},${point.y}`).join(" ")} className="chart-line" />
    {points.map((point) => <g key={point.label}><circle cx={point.x} cy={point.y} r="5" className="chart-point" /><text x={point.x} y={point.y - 12} textAnchor="middle" className="chart-value">{point.value.toFixed(metric === "credits" ? 1 : 2)}</text><text x={point.x} y="218" textAnchor="middle" className="chart-label">{point.label}</text></g>)}
  </svg></div>;
}

export default function DashboardClient({ displayName, signOutHref }: { displayName: string; signOutHref: string }) {
  const [courses, setCourses] = useState<Course[]>([]);
  const [settings, setSettings] = useState<Settings>(defaultSettings);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [settingsSaved, setSettingsSaved] = useState(false);
  const [error, setError] = useState("");
  const [semester, setSemester] = useState("");
  const [name, setName] = useState("");
  const [credits, setCredits] = useState("3");
  const [score, setScore] = useState("");
  const [courseClass, setCourseClass] = useState("系必修");
  const [customClass, setCustomClass] = useState("");
  const [editingId, setEditingId] = useState<number | null>(null);
  const [semesterFilter, setSemesterFilter] = useState("全部學期");
  const [sortBy, setSortBy] = useState("semester");
  const [chartMetric, setChartMetric] = useState<ChartMetric>("score");

  const loadData = useCallback(async () => {
    setLoading(true);
    const [coursesResponse, settingsResponse] = await Promise.all([fetch("/api/courses", { cache: "no-store" }), fetch("/api/settings", { cache: "no-store" })]);
    const courseData = await coursesResponse.json(); const settingsData = await settingsResponse.json();
    if (coursesResponse.ok) setCourses(courseData.courses); else setError(courseData.error ?? "無法載入課程");
    if (settingsResponse.ok) setSettings(settingsData.settings);
    setLoading(false);
  }, []);
  useEffect(() => {
    const timer = window.setTimeout(() => { void loadData(); }, 0);
    return () => window.clearTimeout(timer);
  }, [loadData]);

  const computed = useMemo(() => {
    const valid = courses.filter((course) => course.score !== null);
    const totalCredits = valid.filter((course) => Number(course.score) >= 60).reduce((sum, course) => sum + Number(course.credits), 0);
    const attemptedCredits = valid.reduce((sum, course) => sum + Number(course.credits), 0);
    const weightedScore = valid.reduce((sum, course) => sum + Number(course.credits) * Number(course.score), 0);
    const weightedGpa = valid.reduce((sum, course) => sum + Number(course.credits) * scoreToGpa(Number(course.score), settings.gpaMethod), 0);
    const classCredits = Object.fromEntries(targetKeys.map(({ courseClass: value }) => [value, valid.filter((course) => course.courseClass === value && Number(course.score) >= 60).reduce((sum, course) => sum + Number(course.credits), 0)]));
    const groups = new Map<string, Course[]>(); valid.forEach((course) => groups.set(course.semester, [...(groups.get(course.semester) ?? []), course]));
    const trends = Array.from(groups.entries()).sort(([a], [b]) => a.localeCompare(b, "zh-Hant", { numeric: true })).map(([term, items]) => {
      const termCredits = items.reduce((sum, item) => sum + Number(item.credits), 0);
      return { semester: term, score: termCredits ? items.reduce((sum, item) => sum + Number(item.score) * Number(item.credits), 0) / termCredits : 0, gpa: termCredits ? items.reduce((sum, item) => sum + scoreToGpa(Number(item.score), settings.gpaMethod) * Number(item.credits), 0) / termCredits : 0, credits: items.filter((item) => Number(item.score) >= 60).reduce((sum, item) => sum + Number(item.credits), 0) };
    });
    return { totalCredits, averageScore: attemptedCredits ? weightedScore / attemptedCredits : 0, gpa: attemptedCredits ? weightedGpa / attemptedCredits : 0, classCredits, trends };
  }, [courses, settings.gpaMethod]);

  const currentGpa = score === "" ? null : scoreToGpa(Number(score), settings.gpaMethod);
  const semesters = useMemo(() => ["全部學期", ...Array.from(new Set(courses.map((course) => course.semester)))], [courses]);
  const visibleCourses = useMemo(() => courses.filter((course) => semesterFilter === "全部學期" || course.semester === semesterFilter).sort((a, b) => sortBy === "name" ? a.name.localeCompare(b.name, "zh-Hant") : sortBy === "credits" ? Number(b.credits) - Number(a.credits) : sortBy === "score" ? Number(b.score) - Number(a.score) : b.semester.localeCompare(a.semester, "zh-Hant", { numeric: true })), [courses, semesterFilter, sortBy]);

  async function saveCourse(event: FormEvent) {
    event.preventDefault(); setSaving(true); setError("");
    const finalClass = courseClass === "自己寫" ? customClass.trim() : courseClass;
    const response = await fetch("/api/courses", { method: editingId ? "PATCH" : "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ id: editingId, semester, name, courseClass: finalClass, credits: Number(credits), score: Number(score), gpaMethod: settings.gpaMethod }) });
    const data = await response.json();
    if (response.ok) { setCourses((current) => editingId ? current.map((course) => course.id === editingId ? data.course : course) : [...current, data.course]); resetForm(); } else setError(data.error ?? "儲存失敗");
    setSaving(false);
  }
  function resetForm() { setName(""); setScore(""); setEditingId(null); setCourseClass("系必修"); setCustomClass(""); }
  function editCourse(course: Course) { const standard = classOptions.includes(course.courseClass); setEditingId(course.id); setSemester(course.semester); setName(course.name); setCredits(String(course.credits)); setScore(course.score === null ? "" : String(course.score)); setCourseClass(standard ? course.courseClass : "自己寫"); setCustomClass(standard ? "" : course.courseClass); setError(""); window.scrollTo({ top: 260, behavior: "smooth" }); }
  async function removeCourse(id: number) { const response = await fetch(`/api/courses?id=${id}`, { method: "DELETE" }); if (response.ok) setCourses((current) => current.filter((course) => course.id !== id)); }
  async function saveSettings() { setSettingsSaved(false); const response = await fetch("/api/settings", { method: "PUT", headers: { "Content-Type": "application/json" }, body: JSON.stringify(settings) }); if (response.ok) setSettingsSaved(true); }
  function updateTarget(key: keyof Settings, value: string) { setSettings((current) => ({ ...current, [key]: Math.max(0, Number(value) || 0) })); setSettingsSaved(false); }

  return <main className="dashboard-page">
    <nav className="nav shell"><Link className="brand" href="/"><span className="brand-mark">G</span><span>GPA Compass</span></Link><a className="nav-link" href={signOutHref}>登出</a></nav>
    <div className="dashboard shell">
      <header className="dashboard-header"><div><p className="eyebrow">我的學習座標</p><h1>嗨，{displayName}</h1><p>分數、GPA 與學分進度，都在同一張地圖上。</p></div><label className="method-picker">GPA 換算<select value={settings.gpaMethod} onChange={(event) => { setSettings((current) => ({ ...current, gpaMethod: event.target.value as GpaMethod })); setSettingsSaved(false); }}>{Object.entries(gpaMethodLabels).map(([value, label]) => <option key={value} value={value}>{label}</option>)}</select></label></header>
      <section className="summary-grid" aria-label="成績摘要"><article className="summary-card accent"><span>累積 GPA</span><strong>{computed.gpa.toFixed(2)}</strong><small>{gpaMethodLabels[settings.gpaMethod]}</small></article><article className="summary-card"><span>總平均成績</span><strong>{computed.averageScore.toFixed(1)}</strong><small>百分制</small></article><article className="summary-card"><span>已獲得學分</span><strong>{computed.totalCredits}</strong><small>及格課程</small></article></section>
      <section className="workspace-grid">
        <form className="course-form" onSubmit={saveCourse}><div><p className="eyebrow">{editingId ? "修改紀錄" : "新增紀錄"}</p><h2>{editingId ? "更新這門課" : "加入一門課"}</h2></div><label>學期<input value={semester} onChange={(event) => setSemester(event.target.value)} placeholder="例如：2026 春季" maxLength={30} required /></label><label>課程名稱<input value={name} onChange={(event) => setName(event.target.value)} placeholder="例如：互動式網頁設計" maxLength={100} required /></label><label>類別<select value={courseClass} onChange={(event) => setCourseClass(event.target.value)}>{classOptions.map((item) => <option key={item}>{item}</option>)}</select></label>{courseClass === "自己寫" && <label>自訂類別<input value={customClass} onChange={(event) => setCustomClass(event.target.value)} placeholder="輸入類別名稱" maxLength={40} required /></label>}<div className="form-row"><label>學分<input type="number" min="0.5" max="20" step="0.5" value={credits} onChange={(event) => setCredits(event.target.value)} required /></label><label>成績（分數）<input type="number" min="0" max="100" step="0.1" value={score} onChange={(event) => setScore(event.target.value)} placeholder="0–100" required /></label></div><div className="gpa-preview"><span>自動換算 GPA</span><strong>{currentGpa === null ? "—" : currentGpa.toFixed(2)}</strong></div>{error && <p className="form-error" role="alert">{error}</p>}<button className="button primary" disabled={saving}>{saving ? "儲存中…" : editingId ? "儲存修改" : "儲存課程"}</button>{editingId && <button type="button" className="cancel-button" onClick={resetForm}>取消修改</button>}</form>
        <section className="course-list"><div className="list-heading"><div><p className="eyebrow">課程清單</p><h2>我的成績</h2></div><span>{courses.length} 門課</span></div><div className="list-controls"><label>學期<select value={semesterFilter} onChange={(event) => setSemesterFilter(event.target.value)}>{semesters.map((item) => <option key={item}>{item}</option>)}</select></label><label>排序<select value={sortBy} onChange={(event) => setSortBy(event.target.value)}><option value="semester">學期</option><option value="name">課程名稱</option><option value="credits">學分</option><option value="score">分數</option></select></label></div>{loading ? <p className="empty-state">正在載入你的資料…</p> : courses.length === 0 ? <p className="empty-state">還沒有課程，從左邊新增第一筆紀錄吧。</p> : <div className="table-wrap"><table><thead><tr><th>課程</th><th>類別</th><th>學期</th><th>學分</th><th>分數</th><th>GPA</th><th><span className="sr-only">操作</span></th></tr></thead><tbody>{visibleCourses.map((course) => <tr key={course.id}><td>{course.name}</td><td>{course.courseClass}</td><td>{course.semester}</td><td>{course.credits}</td><td>{course.score ?? "待補"}</td><td><span className="grade-badge">{course.score === null ? "—" : scoreToGpa(Number(course.score), settings.gpaMethod).toFixed(2)}</span></td><td><div className="row-actions"><button className="edit-button" onClick={() => editCourse(course)}>修改</button><button className="delete-button" onClick={() => void removeCourse(course.id)}>刪除</button></div></td></tr>)}</tbody></table></div>}</section>
      </section>
      <section className="analytics-card"><div className="analytics-heading"><div><p className="eyebrow">學期趨勢</p><h2>成績曲線</h2></div><div className="metric-tabs">{(["score", "gpa", "credits"] as ChartMetric[]).map((metric) => <button key={metric} className={chartMetric === metric ? "active" : ""} onClick={() => setChartMetric(metric)}>{metric === "score" ? "平均分數" : metric === "gpa" ? "平均 GPA" : "獲得學分"}</button>)}</div></div><TrendChart rows={computed.trends} metric={chartMetric} /></section>
      <section className="goals-card"><div className="analytics-heading"><div><p className="eyebrow">畢業進度</p><h2>學分目標</h2></div><button className="save-settings" onClick={() => void saveSettings()}>{settingsSaved ? "已儲存" : "儲存目標與換算方式"}</button></div><div className="donut-grid"><CreditDonut label="總學分" value={computed.totalCredits} target={settings.totalTarget} total />{targetKeys.map(({ label, courseClass: value, key }) => <CreditDonut key={value} label={label} value={Number(computed.classCredits[value] ?? 0)} target={Number(settings[key])} />)}</div><div className="target-editor"><label>總學分目標<input type="number" min="0" max="300" value={settings.totalTarget} onChange={(event) => updateTarget("totalTarget", event.target.value)} /></label>{targetKeys.map(({ label, key }) => <label key={String(key)}>{label}<input type="number" min="0" max="300" value={Number(settings[key])} onChange={(event) => updateTarget(key, event.target.value)} /></label>)}</div></section>
    </div>
  </main>;
}
