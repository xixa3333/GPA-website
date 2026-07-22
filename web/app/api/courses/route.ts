import { env } from "cloudflare:workers";
import { getChatGPTUser } from "../../chatgpt-auth";
import { parseCourseInput, parsePositiveId, toStoredCourse } from "../../../lib/domain";
import { courseRepository, type Database } from "../../../lib/repositories";

const repo = () => courseRepository(env.DB as unknown as Database);
const unauthorized = () => Response.json({ error: "請先登入" }, { status: 401 });
async function bodyOf(request: Request) { try { return await request.json() as Record<string, unknown>; } catch { return null; } }

export async function GET() {
  const user = await getChatGPTUser(); if (!user) return unauthorized();
  return Response.json({ courses: await repo().list(user.email) });
}
export async function POST(request: Request) {
  const user = await getChatGPTUser(); if (!user) return unauthorized();
  const body = await bodyOf(request); if (!body) return Response.json({ error: "JSON 格式錯誤" }, { status: 400 });
  const parsed = parseCourseInput(body); if (!parsed.ok) return Response.json({ error: parsed.error }, { status: 400 });
  return Response.json({ course: await repo().create(user.email, toStoredCourse(parsed.value)) }, { status: 201 });
}
export async function PATCH(request: Request) {
  const user = await getChatGPTUser(); if (!user) return unauthorized();
  const body = await bodyOf(request); if (!body) return Response.json({ error: "JSON 格式錯誤" }, { status: 400 });
  const id = parsePositiveId(body.id); const parsed = parseCourseInput(body);
  if (!id || !parsed.ok) return Response.json({ error: parsed.ok ? "無效的課程編號" : parsed.error }, { status: 400 });
  const course = await repo().update(user.email, id, toStoredCourse(parsed.value));
  return course ? Response.json({ course }) : Response.json({ error: "找不到課程" }, { status: 404 });
}
export async function DELETE(request: Request) {
  const user = await getChatGPTUser(); if (!user) return unauthorized();
  const id = parsePositiveId(new URL(request.url).searchParams.get("id"));
  if (!id) return Response.json({ error: "無效的課程編號" }, { status: 400 });
  return await repo().remove(user.email, id) ? Response.json({ ok: true }) : Response.json({ error: "找不到課程" }, { status: 404 });
}
