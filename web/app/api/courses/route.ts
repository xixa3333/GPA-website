import { env } from "cloudflare:workers";
import { getChatGPTUser } from "../../chatgpt-auth";
import { GpaMethod, scoreToGpa } from "../../../lib/gpa";

const standardClasses = new Set(["系必修", "系選修", "通識", "共同必修", "自由選修"]);
const methods = new Set(["NKUST", "TW0", "TW3"]);

function parsePayload(body: Record<string, unknown>) {
  const semester = String(body.semester ?? "").trim().slice(0, 30);
  const name = String(body.name ?? "").trim().slice(0, 100);
  const credits = Number(body.credits);
  const score = Number(body.score);
  const rawClass = String(body.courseClass ?? "系必修").trim().slice(0, 40);
  const courseClass = standardClasses.has(rawClass) ? rawClass : rawClass || "其他";
  const gpaMethod = methods.has(String(body.gpaMethod)) ? String(body.gpaMethod) as GpaMethod : "NKUST";
  if (!semester || !name || !Number.isFinite(credits) || credits <= 0 || credits > 20 || !Number.isFinite(score) || score < 0 || score > 100) return null;
  return { semester, name, credits, score, courseClass, gpaMethod };
}

export async function GET() {
  const user = await getChatGPTUser();
  if (!user) return Response.json({ error: "請先登入" }, { status: 401 });
  const result = await env.DB.prepare("SELECT id, semester, name, course_class AS courseClass, credits, score, created_at AS createdAt FROM courses WHERE owner_email = ? ORDER BY semester ASC, id ASC").bind(user.email).all();
  return Response.json({ courses: result.results });
}

export async function POST(request: Request) {
  const user = await getChatGPTUser();
  if (!user) return Response.json({ error: "請先登入" }, { status: 401 });
  const parsed = parsePayload(await request.json() as Record<string, unknown>);
  if (!parsed) return Response.json({ error: "請確認學期、課程、學分與 0–100 分的成績" }, { status: 400 });
  const points = scoreToGpa(parsed.score, parsed.gpaMethod);
  const result = await env.DB.prepare("INSERT INTO courses (owner_email, semester, name, requirement, category, course_class, credits, score, grade, grade_points) VALUES (?, ?, ?, '必修', '專業', ?, ?, ?, ?, ?) RETURNING id, semester, name, course_class AS courseClass, credits, score, created_at AS createdAt")
    .bind(user.email, parsed.semester, parsed.name, parsed.courseClass, parsed.credits, parsed.score, String(parsed.score), points).first();
  return Response.json({ course: result }, { status: 201 });
}

export async function PATCH(request: Request) {
  const user = await getChatGPTUser();
  if (!user) return Response.json({ error: "請先登入" }, { status: 401 });
  const body = await request.json() as Record<string, unknown>;
  const id = Number(body.id);
  const parsed = parsePayload(body);
  if (!Number.isInteger(id) || id <= 0 || !parsed) return Response.json({ error: "請確認課程資料" }, { status: 400 });
  const points = scoreToGpa(parsed.score, parsed.gpaMethod);
  const result = await env.DB.prepare("UPDATE courses SET semester = ?, name = ?, course_class = ?, credits = ?, score = ?, grade = ?, grade_points = ? WHERE id = ? AND owner_email = ? RETURNING id, semester, name, course_class AS courseClass, credits, score, created_at AS createdAt")
    .bind(parsed.semester, parsed.name, parsed.courseClass, parsed.credits, parsed.score, String(parsed.score), points, id, user.email).first();
  if (!result) return Response.json({ error: "找不到課程" }, { status: 404 });
  return Response.json({ course: result });
}

export async function DELETE(request: Request) {
  const user = await getChatGPTUser();
  if (!user) return Response.json({ error: "請先登入" }, { status: 401 });
  const id = Number(new URL(request.url).searchParams.get("id"));
  if (!Number.isInteger(id) || id <= 0) return Response.json({ error: "無效的課程編號" }, { status: 400 });
  const result = await env.DB.prepare("DELETE FROM courses WHERE id = ? AND owner_email = ?").bind(id, user.email).run();
  if (!result.meta.changes) return Response.json({ error: "找不到課程" }, { status: 404 });
  return Response.json({ ok: true });
}
