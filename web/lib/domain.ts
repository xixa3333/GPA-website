import { isGpaMethod, scoreToGpa } from "./gpa.ts";
import type { GpaMethod } from "./gpa.ts";

export const COURSE_CLASSES = ["系必修", "系選修", "通識", "共同必修", "自由選修"] as const;
export const DEFAULT_SETTINGS: UserSettings = {
  gpaMethod: "NKUST", systemRequiredTarget: 53, systemElectiveTarget: 47,
  generalTarget: 16, commonRequiredTarget: 12, freeElectiveTarget: 0, totalTarget: 128,
};

export type CourseInput = { semester: string; name: string; credits: number; score: number; courseClass: string; gpaMethod: GpaMethod };
export type UserSettings = { gpaMethod: GpaMethod; systemRequiredTarget: number; systemElectiveTarget: number; generalTarget: number; commonRequiredTarget: number; freeElectiveTarget: number; totalTarget: number };
export type ValidationResult<T> = { ok: true; value: T } | { ok: false; error: string };

function cleanText(value: unknown, max: number) {
  return typeof value === "string" ? value.trim().slice(0, max) : "";
}

export function parseCourseInput(body: Record<string, unknown>): ValidationResult<CourseInput> {
  const semester = cleanText(body.semester, 30);
  const name = cleanText(body.name, 100);
  const courseClass = cleanText(body.courseClass, 40);
  const credits = typeof body.credits === "number" ? body.credits : Number(body.credits);
  const score = typeof body.score === "number" ? body.score : Number(body.score);
  if (!semester || !name) return { ok: false, error: "學期與課程名稱為必填" };
  if (!Number.isFinite(credits) || credits <= 0 || credits > 20) return { ok: false, error: "學分必須介於 0 到 20" };
  if (!Number.isFinite(score) || score < 0 || score > 100) return { ok: false, error: "分數必須介於 0 到 100" };
  if (!courseClass) return { ok: false, error: "類別為必填" };
  const gpaMethod = isGpaMethod(body.gpaMethod) ? body.gpaMethod : "NKUST";
  return { ok: true, value: { semester, name, credits, score, courseClass, gpaMethod } };
}

export function parseSettings(body: Record<string, unknown>): UserSettings {
  const bounded = (key: keyof UserSettings) => {
    const value = Number(body[key]);
    return Number.isFinite(value) ? Math.max(0, Math.min(300, value)) : 0;
  };
  return {
    gpaMethod: isGpaMethod(body.gpaMethod) ? body.gpaMethod : "NKUST",
    systemRequiredTarget: bounded("systemRequiredTarget"), systemElectiveTarget: bounded("systemElectiveTarget"),
    generalTarget: bounded("generalTarget"), commonRequiredTarget: bounded("commonRequiredTarget"),
    freeElectiveTarget: bounded("freeElectiveTarget"), totalTarget: bounded("totalTarget"),
  };
}

export function toStoredCourse(input: CourseInput) {
  return { ...input, grade: String(input.score), gradePoints: scoreToGpa(input.score, input.gpaMethod) };
}

export function parsePositiveId(value: unknown): number | null {
  const id = Number(value);
  return Number.isInteger(id) && id > 0 ? id : null;
}
