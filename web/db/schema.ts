import { sql } from "drizzle-orm";
import { index, integer, real, sqliteTable, text } from "drizzle-orm/sqlite-core";
export const courses = sqliteTable("courses", {
  id: integer("id").primaryKey({ autoIncrement: true }), ownerEmail: text("owner_email").notNull(), semester: text("semester").notNull(), name: text("name").notNull(),
  requirement: text("requirement").notNull().default("必修"), category: text("category").notNull().default("其他"), courseClass: text("course_class").notNull().default("系必修"),
  credits: real("credits").notNull(), score: real("score"), grade: text("grade").notNull(), gradePoints: real("grade_points").notNull(), createdAt: text("created_at").notNull().default(sql`CURRENT_TIMESTAMP`),
}, (table) => [index("courses_owner_idx").on(table.ownerEmail), index("courses_owner_semester_idx").on(table.ownerEmail, table.semester)]);
export const userSettings = sqliteTable("user_settings", {
  ownerEmail: text("owner_email").primaryKey(), gpaMethod: text("gpa_method").notNull().default("NKUST"), systemRequiredTarget: real("system_required_target").notNull().default(53),
  systemElectiveTarget: real("system_elective_target").notNull().default(47), generalTarget: real("general_target").notNull().default(16), commonRequiredTarget: real("common_required_target").notNull().default(12),
  freeElectiveTarget: real("free_elective_target").notNull().default(0), totalTarget: real("total_target").notNull().default(128),
});
