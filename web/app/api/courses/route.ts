import { env } from "cloudflare:workers";
import { getChatGPTUser } from "../../chatgpt-auth";

const gradePoints: Record<string, number> = { "A+": 4.3, A: 4, "A-": 3.7, "B+": 3.3, B: 3, "B-": 2.7, "C+": 2.3, C: 2, "C-": 1.7, "D+": 1.3, D: 1, F: 0 };

export async function GET() {
  const user = await getChatGPTUser();
  if (!user) return Response.json({ error: "請先登入" }, { status: 401 });
  const result = await env.DB.prepare("SELECT id, semester, name, requirement, category, credits, grade, grade_points AS gradePoints, created_at AS createdAt FROM courses WHERE owner_email = ? ORDER BY semester DESC, id DESC").bind(user.email).all();
  return Response.json({ courses: result.results });
}

export async function POST(request: Request) {
  const user = await getChatGPTUser();
  if (!user) return Response.json({ error: "請先登入" }, { status: 401 });
  const body = await request.json() as { semester?: string; name?: string; requirement?: string; category?: string; credits?: number; grade?: string };
  const semester = body.semester?.trim() ?? "";
  const name = body.name?.trim() ?? "";
  const credits = Number(body.credits);
  const grade = body.grade?.trim() ?? "";
  const requirement = body.requirement === "選修" ? "選修" : "必修";
  const category = body.category === "通識" ? "通識" : "專業";
  if (!semester || !name || !Number.isFinite(credits) || credits <= 0 || credits > 20 || !(grade in gradePoints)) {
    return Response.json({ error: "請確認學期、課程、學分與成績格式" }, { status: 400 });
  }
  const result = await env.DB.prepare("INSERT INTO courses (owner_email, semester, name, requirement, category, credits, grade, grade_points) VALUES (?, ?, ?, ?, ?, ?, ?, ?) RETURNING id, semester, name, requirement, category, credits, grade, grade_points AS gradePoints, created_at AS createdAt")
    .bind(user.email, semester.slice(0, 30), name.slice(0, 100), requirement, category, credits, grade, gradePoints[grade]).first();
  return Response.json({ course: result }, { status: 201 });
}

export async function PATCH(request: Request) {
  const user = await getChatGPTUser();
  if (!user) return Response.json({ error: "請先登入" }, { status: 401 });
  const body = await request.json() as { id?: number; semester?: string; name?: string; requirement?: string; category?: string; credits?: number; grade?: string };
  const id = Number(body.id);
  const semester = body.semester?.trim() ?? "";
  const name = body.name?.trim() ?? "";
  const credits = Number(body.credits);
  const grade = body.grade?.trim() ?? "";
  const requirement = body.requirement === "選修" ? "選修" : "必修";
  const category = body.category === "通識" ? "通識" : "專業";
  if (!Number.isInteger(id) || !semester || !name || !Number.isFinite(credits) || credits <= 0 || credits > 20 || !(grade in gradePoints)) {
    return Response.json({ error: "請確認課程資料" }, { status: 400 });
  }
  const result = await env.DB.prepare("UPDATE courses SET semester = ?, name = ?, requirement = ?, category = ?, credits = ?, grade = ?, grade_points = ? WHERE id = ? AND owner_email = ? RETURNING id, semester, name, requirement, category, credits, grade, grade_points AS gradePoints, created_at AS createdAt")
    .bind(semester.slice(0, 30), name.slice(0, 100), requirement, category, credits, grade, gradePoints[grade], id, user.email).first();
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
