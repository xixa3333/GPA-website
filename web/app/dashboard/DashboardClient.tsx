"use client";

import Link from "next/link";
import { FormEvent, useCallback, useEffect, useMemo, useState } from "react";

type Course = { id: number; semester: string; name: string; requirement: string; category: string; credits: number; grade: string; gradePoints: number };
const grades = ["A+", "A", "A-", "B+", "B", "B-", "C+", "C", "C-", "D+", "D", "F"];

export default function DashboardClient({ displayName, signOutHref }: { displayName: string; signOutHref: string }) {
  const [courses, setCourses] = useState<Course[]>([]);
  const [loading, setLoading] = useState(true);
  const [saving, setSaving] = useState(false);
  const [error, setError] = useState("");
  const [semester, setSemester] = useState("");
  const [name, setName] = useState("");
  const [credits, setCredits] = useState("3");
  const [grade, setGrade] = useState("A");
  const [requirement, setRequirement] = useState("必修");
  const [category, setCategory] = useState("專業");
  const [editingId, setEditingId] = useState<number | null>(null);
  const [semesterFilter, setSemesterFilter] = useState("全部學期");
  const [sortBy, setSortBy] = useState("semester");

  const loadCourses = useCallback(async () => {
    setLoading(true);
    const response = await fetch("/api/courses", { cache: "no-store" });
    const data = await response.json();
    if (response.ok) setCourses(data.courses); else setError(data.error ?? "無法載入資料");
    setLoading(false);
  }, []);

  useEffect(() => { void loadCourses(); }, [loadCourses]);

  const summary = useMemo(() => {
    const totalCredits = courses.reduce((sum, course) => sum + Number(course.credits), 0);
    const weighted = courses.reduce((sum, course) => sum + Number(course.credits) * Number(course.gradePoints), 0);
    const gpa = totalCredits ? weighted / totalCredits : 0;
    const passed = courses.filter((course) => course.grade !== "F");
    const professionalCredits = passed.filter((course) => course.category === "專業").reduce((sum, course) => sum + Number(course.credits), 0);
    const generalCredits = passed.filter((course) => course.category === "通識").reduce((sum, course) => sum + Number(course.credits), 0);
    return { totalCredits, gpa, semesters: new Set(courses.map((course) => course.semester)).size, professionalCredits, generalCredits };
  }, [courses]);

  const semesters = useMemo(() => ["全部學期", ...Array.from(new Set(courses.map((course) => course.semester)))], [courses]);
  const visibleCourses = useMemo(() => courses.filter((course) => semesterFilter === "全部學期" || course.semester === semesterFilter).sort((a, b) => {
    if (sortBy === "name") return a.name.localeCompare(b.name, "zh-Hant");
    if (sortBy === "credits") return Number(b.credits) - Number(a.credits);
    if (sortBy === "grade") return Number(b.gradePoints) - Number(a.gradePoints);
    return b.semester.localeCompare(a.semester, "zh-Hant");
  }), [courses, semesterFilter, sortBy]);

  async function addCourse(event: FormEvent) {
    event.preventDefault(); setSaving(true); setError("");
    const response = await fetch("/api/courses", { method: editingId ? "PATCH" : "POST", headers: { "Content-Type": "application/json" }, body: JSON.stringify({ id: editingId, semester, name, requirement, category, credits: Number(credits), grade }) });
    const data = await response.json();
    if (response.ok) {
      setCourses((current) => editingId ? current.map((course) => course.id === editingId ? data.course : course) : [data.course, ...current]);
      setName(""); setEditingId(null);
    } else setError(data.error ?? "儲存失敗");
    setSaving(false);
  }

  function editCourse(course: Course) {
    setEditingId(course.id); setSemester(course.semester); setName(course.name); setRequirement(course.requirement); setCategory(course.category); setCredits(String(course.credits)); setGrade(course.grade); setError("");
    window.scrollTo({ top: 300, behavior: "smooth" });
  }

  async function removeCourse(id: number) {
    const response = await fetch(`/api/courses?id=${id}`, { method: "DELETE" });
    if (response.ok) setCourses((current) => current.filter((course) => course.id !== id));
  }

  return (
    <main className="dashboard-page">
      <nav className="nav shell"><Link className="brand" href="/"><span className="brand-mark">G</span><span>GPA Compass</span></Link><a className="nav-link" href={signOutHref}>登出</a></nav>
      <div className="dashboard shell">
        <header className="dashboard-header"><div><p className="eyebrow">我的學習座標</p><h1>嗨，{displayName}</h1><p>每一次更新，都讓目標更清楚一點。</p></div></header>
        <section className="summary-grid" aria-label="成績摘要">
          <article className="summary-card accent"><span>累積 GPA</span><strong>{summary.gpa.toFixed(2)}</strong><small>4.3 scale</small></article>
          <article className="summary-card"><span>累積學分</span><strong>{summary.totalCredits}</strong><small>credits</small></article>
          <article className="summary-card"><span>已記錄學期</span><strong>{summary.semesters}</strong><small>專業 {summary.professionalCredits} · 通識 {summary.generalCredits}</small></article>
        </section>
        <section className="workspace-grid">
          <form className="course-form" onSubmit={addCourse}>
            <div><p className="eyebrow">{editingId ? "修改紀錄" : "新增紀錄"}</p><h2>{editingId ? "更新這門課" : "加入一門課"}</h2></div>
            <label>學期<input value={semester} onChange={(event) => setSemester(event.target.value)} placeholder="例如：2026 春季" maxLength={30} required /></label>
            <label>課程名稱<input value={name} onChange={(event) => setName(event.target.value)} placeholder="例如：互動式網頁設計" maxLength={100} required /></label>
            <div className="form-row"><label>必／選修<select value={requirement} onChange={(event) => setRequirement(event.target.value)}><option>必修</option><option>選修</option></select></label><label>課程分類<select value={category} onChange={(event) => setCategory(event.target.value)}><option>專業</option><option>通識</option></select></label></div>
            <div className="form-row"><label>學分<input type="number" min="0.5" max="20" step="0.5" value={credits} onChange={(event) => setCredits(event.target.value)} required /></label><label>成績<select value={grade} onChange={(event) => setGrade(event.target.value)}>{grades.map((item) => <option key={item}>{item}</option>)}</select></label></div>
            {error && <p className="form-error" role="alert">{error}</p>}
            <button className="button primary" disabled={saving}>{saving ? "儲存中…" : editingId ? "儲存修改" : "儲存課程"}</button>
            {editingId && <button type="button" className="cancel-button" onClick={() => { setEditingId(null); setName(""); }}>取消修改</button>}
          </form>
          <section className="course-list">
            <div className="list-heading"><div><p className="eyebrow">課程清單</p><h2>我的成績</h2></div><span>{courses.length} 門課</span></div>
            <div className="list-controls"><label>學期<select value={semesterFilter} onChange={(event) => setSemesterFilter(event.target.value)}>{semesters.map((item) => <option key={item}>{item}</option>)}</select></label><label>排序<select value={sortBy} onChange={(event) => setSortBy(event.target.value)}><option value="semester">學期</option><option value="name">課程名稱</option><option value="credits">學分</option><option value="grade">GPA</option></select></label></div>
            {loading ? <p className="empty-state">正在載入你的資料…</p> : courses.length === 0 ? <p className="empty-state">還沒有課程，從左邊新增第一筆紀錄吧。</p> : <div className="table-wrap"><table><thead><tr><th>課程</th><th>分類</th><th>學期</th><th>學分</th><th>成績</th><th><span className="sr-only">操作</span></th></tr></thead><tbody>{visibleCourses.map((course) => <tr key={course.id}><td>{course.name}<small className="course-meta">{course.requirement}</small></td><td>{course.category}</td><td>{course.semester}</td><td>{course.credits}</td><td><span className="grade-badge">{course.grade}</span></td><td><div className="row-actions"><button className="edit-button" onClick={() => editCourse(course)} aria-label={`修改 ${course.name}`}>修改</button><button className="delete-button" onClick={() => void removeCourse(course.id)} aria-label={`刪除 ${course.name}`}>刪除</button></div></td></tr>)}</tbody></table></div>}
          </section>
        </section>
      </div>
    </main>
  );
}
