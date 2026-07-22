import test from "node:test";
import assert from "node:assert/strict";
import { parseCourseInput, parsePositiveId, parseSettings, toStoredCourse } from "../lib/domain.ts";
import { scoreToGpa } from "../lib/gpa.ts";

test("GPA boundary tables match all three grading methods", () => {
  assert.deepEqual([49, 50, 59, 60, 69, 70, 79, 80].map(s => scoreToGpa(s, "NKUST")), [0, 1, 1, 2, 2, 3, 3, 4]);
  assert.deepEqual([59, 60, 62, 63, 92, 93].map(s => scoreToGpa(s, "TW0")), [0, .7, .7, 1, 3.7, 4]);
  assert.deepEqual([59, 60, 62, 63, 89, 90].map(s => scoreToGpa(s, "TW3")), [0, 1.7, 1.7, 2, 4, 4.3]);
});

test("course validation accepts custom category and trims text", () => {
  const result = parseCourseInput({ semester: " 113-1 ", name: " 程式設計 ", credits: 3, score: 88, courseClass: "跨域學程", gpaMethod: "TW3" });
  assert.equal(result.ok, true);
  if (result.ok) assert.deepEqual(toStoredCourse(result.value), { semester: "113-1", name: "程式設計", credits: 3, score: 88, courseClass: "跨域學程", gpaMethod: "TW3", grade: "88", gradePoints: 4 });
});

test("course validation rejects empty, non-finite and out-of-range data", () => {
  for (const payload of [
    { semester: "", name: "x", credits: 3, score: 80, courseClass: "系必修" },
    { semester: "1", name: "x", credits: 0, score: 80, courseClass: "系必修" },
    { semester: "1", name: "x", credits: 3, score: 101, courseClass: "系必修" },
    { semester: "1", name: "x", credits: 3, score: 80, courseClass: "" },
  ]) assert.equal(parseCourseInput(payload).ok, false);
});

test("settings are bounded and invalid GPA methods fail closed", () => {
  const value = parseSettings({ gpaMethod: "INJECT", totalTarget: 999, generalTarget: -5, systemRequiredTarget: "NaN" });
  assert.equal(value.gpaMethod, "NKUST");
  assert.equal(value.totalTarget, 300);
  assert.equal(value.generalTarget, 0);
  assert.equal(value.systemRequiredTarget, 0);
});

test("IDs only accept positive integers", () => {
  assert.equal(parsePositiveId("1"), 1);
  for (const value of ["0", "-1", "1.5", "x", null]) assert.equal(parsePositiveId(value), null);
});
