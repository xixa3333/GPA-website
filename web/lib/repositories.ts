import type { CourseInput, UserSettings } from "./domain.ts";

type D1Statement = { bind: (...values: unknown[]) => D1Statement; all: () => Promise<{ results: unknown[] }>; first: () => Promise<unknown>; run: () => Promise<{ meta: { changes?: number } }> };
export type Database = { prepare: (sql: string) => D1Statement };

export function courseRepository(db: Database) {
  return {
    async list(owner: string) {
      return (await db.prepare("SELECT id, semester, name, course_class AS courseClass, credits, score, created_at AS createdAt FROM courses WHERE owner_email = ? ORDER BY semester ASC, id ASC").bind(owner).all()).results;
    },
    async create(owner: string, value: CourseInput & { grade: string; gradePoints: number }) {
      return db.prepare("INSERT INTO courses (owner_email, semester, name, requirement, category, course_class, credits, score, grade, grade_points) VALUES (?, ?, ?, '必修', '其他', ?, ?, ?, ?, ?) RETURNING id, semester, name, course_class AS courseClass, credits, score, created_at AS createdAt").bind(owner, value.semester, value.name, value.courseClass, value.credits, value.score, value.grade, value.gradePoints).first();
    },
    async update(owner: string, id: number, value: CourseInput & { grade: string; gradePoints: number }) {
      return db.prepare("UPDATE courses SET semester=?, name=?, course_class=?, credits=?, score=?, grade=?, grade_points=? WHERE id=? AND owner_email=? RETURNING id, semester, name, course_class AS courseClass, credits, score, created_at AS createdAt").bind(value.semester, value.name, value.courseClass, value.credits, value.score, value.grade, value.gradePoints, id, owner).first();
    },
    async remove(owner: string, id: number) {
      return Boolean((await db.prepare("DELETE FROM courses WHERE id=? AND owner_email=?").bind(id, owner).run()).meta.changes);
    },
  };
}

export function settingsRepository(db: Database) {
  return {
    async get(owner: string) {
      return db.prepare("SELECT gpa_method AS gpaMethod, system_required_target AS systemRequiredTarget, system_elective_target AS systemElectiveTarget, general_target AS generalTarget, common_required_target AS commonRequiredTarget, free_elective_target AS freeElectiveTarget, total_target AS totalTarget FROM user_settings WHERE owner_email=?").bind(owner).first() as Promise<UserSettings | null>;
    },
    async save(owner: string, s: UserSettings) {
      await db.prepare("INSERT INTO user_settings (owner_email,gpa_method,system_required_target,system_elective_target,general_target,common_required_target,free_elective_target,total_target) VALUES (?,?,?,?,?,?,?,?) ON CONFLICT(owner_email) DO UPDATE SET gpa_method=excluded.gpa_method,system_required_target=excluded.system_required_target,system_elective_target=excluded.system_elective_target,general_target=excluded.general_target,common_required_target=excluded.common_required_target,free_elective_target=excluded.free_elective_target,total_target=excluded.total_target").bind(owner, s.gpaMethod, s.systemRequiredTarget, s.systemElectiveTarget, s.generalTarget, s.commonRequiredTarget, s.freeElectiveTarget, s.totalTarget).run();
      return s;
    },
  };
}
