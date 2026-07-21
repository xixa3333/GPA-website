import type { Metadata } from "next";
import { chatGPTSignOutPath, requireChatGPTUser } from "../chatgpt-auth";
import DashboardClient from "./DashboardClient";

export const dynamic = "force-dynamic";
export const metadata: Metadata = { title: "我的成績" };

export default async function DashboardPage() {
  const user = await requireChatGPTUser("/dashboard");
  return <DashboardClient displayName={user.displayName} signOutHref={chatGPTSignOutPath("/")} />;
}
