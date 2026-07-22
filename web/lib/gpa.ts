export const GPA_METHODS = ["NKUST", "TW0", "TW3"] as const;
export type GpaMethod = (typeof GPA_METHODS)[number];

export const gpaMethodLabels: Record<GpaMethod, string> = {
  NKUST: "高科大 GPA 4.0",
  TW0: "臺灣常用 GPA 4.0",
  TW3: "臺灣常用 GPA 4.3",
};

export function isGpaMethod(value: unknown): value is GpaMethod {
  return typeof value === "string" && GPA_METHODS.includes(value as GpaMethod);
}

export function scoreToGpa(score: number, method: GpaMethod): number {
  if (!Number.isFinite(score) || score < 0 || score > 100) return 0;
  if (method === "NKUST") {
    if (score < 50) return 0;
    if (score < 60) return 1;
    if (score < 70) return 2;
    if (score < 80) return 3;
    return 4;
  }
  if (method === "TW0") {
    if (score <= 59) return 0;
    if (score <= 62) return 0.7;
    if (score <= 66) return 1;
    if (score <= 69) return 1.3;
    if (score <= 72) return 1.7;
    if (score <= 76) return 2;
    if (score <= 79) return 2.3;
    if (score <= 82) return 2.7;
    if (score <= 86) return 3;
    if (score <= 89) return 3.3;
    if (score <= 92) return 3.7;
    return 4;
  }
  if (score <= 59) return 0;
  if (score <= 62) return 1.7;
  if (score <= 66) return 2;
  if (score <= 69) return 2.3;
  if (score <= 72) return 2.7;
  if (score <= 76) return 3;
  if (score <= 79) return 3.3;
  if (score <= 84) return 3.7;
  if (score <= 89) return 4;
  return 4.3;
}
