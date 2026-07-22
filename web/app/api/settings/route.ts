import { env } from "cloudflare:workers";
import { getChatGPTUser } from "../../chatgpt-auth";
import { DEFAULT_SETTINGS, parseSettings } from "../../../lib/domain";
import { settingsRepository, type Database } from "../../../lib/repositories";

const repo = () => settingsRepository(env.DB as unknown as Database);
const unauthorized = () => Response.json({ error: "請先登入" }, { status: 401 });
export async function GET() {
  const user = await getChatGPTUser(); if (!user) return unauthorized();
  return Response.json({ settings: await repo().get(user.email) ?? DEFAULT_SETTINGS });
}
export async function PUT(request: Request) {
  const user = await getChatGPTUser(); if (!user) return unauthorized();
  let body: Record<string, unknown>; try { body = await request.json() as Record<string, unknown>; } catch { return Response.json({ error: "JSON 格式錯誤" }, { status: 400 }); }
  return Response.json({ settings: await repo().save(user.email, parseSettings(body)) });
}
