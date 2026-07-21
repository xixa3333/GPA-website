import { env } from "cloudflare:workers";
import { getChatGPTUser } from "../../chatgpt-auth";

const defaults = { gpaMethod: "NKUST", systemRequiredTarget: 53, systemElectiveTarget: 47, generalTarget: 16, commonRequiredTarget: 12, freeElectiveTarget: 0, totalTarget: 128 };

export async function GET() {
  const user = await getChatGPTUser();
  if (!user) return Response.json({ error: "請先登入" }, { status: 401 });
  const row = await env.DB.prepare("SELECT gpa_method AS gpaMethod, system_required_target AS systemRequiredTarget, system_elective_target AS systemElectiveTarget, general_target AS generalTarget, common_required_target AS commonRequiredTarget, free_elective_target AS freeElectiveTarget, total_target AS totalTarget FROM user_settings WHERE owner_email = ?").bind(user.email).first();
  return Response.json({ settings: row ?? defaults });
}

export async function PUT(request: Request) {
  const user = await getChatGPTUser();
  if (!user) return Response.json({ error: "請先登入" }, { status: 401 });
  const body = await request.json() as Record<string, unknown>;
  const gpaMethod = ["NKUST", "TW0", "TW3"].includes(String(body.gpaMethod)) ? String(body.gpaMethod) : "NKUST";
  const values = ["systemRequiredTarget", "systemElectiveTarget", "generalTarget", "commonRequiredTarget", "freeElectiveTarget", "totalTarget"].map((key) => Math.max(0, Math.min(300, Number(body[key]) || 0)));
  await env.DB.prepare("INSERT INTO user_settings (owner_email, gpa_method, system_required_target, system_elective_target, general_target, common_required_target, free_elective_target, total_target) VALUES (?, ?, ?, ?, ?, ?, ?, ?) ON CONFLICT(owner_email) DO UPDATE SET gpa_method=excluded.gpa_method, system_required_target=excluded.system_required_target, system_elective_target=excluded.system_elective_target, general_target=excluded.general_target, common_required_target=excluded.common_required_target, free_elective_target=excluded.free_elective_target, total_target=excluded.total_target")
    .bind(user.email, gpaMethod, ...values).run();
  return Response.json({ settings: { gpaMethod, systemRequiredTarget: values[0], systemElectiveTarget: values[1], generalTarget: values[2], commonRequiredTarget: values[3], freeElectiveTarget: values[4], totalTarget: values[5] } });
}
