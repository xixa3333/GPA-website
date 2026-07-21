import type { Metadata } from "next";
import { headers } from "next/headers";
import "./globals.css";

export async function generateMetadata(): Promise<Metadata> {
  const requestHeaders = await headers();
  const host = requestHeaders.get("x-forwarded-host") ?? requestHeaders.get("host") ?? "localhost";
  const protocol = requestHeaders.get("x-forwarded-proto") ?? (host.includes("localhost") ? "http" : "https");
  const base = new URL(`${protocol}://${host}`);
  return {
    metadataBase: base,
    title: { default: "GPA Compass｜成績與學分管理", template: "%s｜GPA Compass" },
    description: "安全保存每學期課程，自動計算 GPA 與累積學分的個人成績管理工具。",
    openGraph: { title: "GPA Compass", description: "成績不只是數字，是下一步的座標。", type: "website", images: [{ url: new URL("/og.png", base).toString(), width: 1536, height: 1024, alt: "GPA Compass 成績管理工具" }] },
    twitter: { card: "summary_large_image", title: "GPA Compass", description: "成績不只是數字，是下一步的座標。", images: [new URL("/og.png", base).toString()] },
  };
}

export default function RootLayout({ children }: Readonly<{ children: React.ReactNode }>) {
  return <html lang="zh-Hant"><body>{children}</body></html>;
}
