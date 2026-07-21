import { sql } from "drizzle-orm";
import { index, integer, real, sqliteTable, text } from "drizzle-orm/sqlite-core";

export const courses = sqliteTable("courses", {
  id: integer("id").primaryKey({ autoIncrement: true }),
  ownerEmail: text("owner_email").notNull(),
  semester: text("semester").notNull(),
  name: text("name").notNull(),
  requirement: text("requirement").notNull().default("必修"),
  category: text("category").notNull().default("專業"),
  credits: real("credits").notNull(),
  grade: text("grade").notNull(),
  gradePoints: real("grade_points").notNull(),
  createdAt: text("created_at").notNull().default(sql`CURRENT_TIMESTAMP`),
}, (table) => [index("courses_owner_idx").on(table.ownerEmail), index("courses_owner_semester_idx").on(table.ownerEmail, table.semester)]);
