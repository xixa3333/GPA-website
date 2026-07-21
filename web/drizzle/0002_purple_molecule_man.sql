CREATE TABLE `user_settings` (
	`owner_email` text PRIMARY KEY NOT NULL,
	`gpa_method` text DEFAULT 'NKUST' NOT NULL,
	`system_required_target` real DEFAULT 53 NOT NULL,
	`system_elective_target` real DEFAULT 47 NOT NULL,
	`general_target` real DEFAULT 16 NOT NULL,
	`common_required_target` real DEFAULT 12 NOT NULL,
	`free_elective_target` real DEFAULT 0 NOT NULL,
	`total_target` real DEFAULT 128 NOT NULL
);
--> statement-breakpoint
ALTER TABLE `courses` ADD `course_class` text DEFAULT '系必修' NOT NULL;--> statement-breakpoint
ALTER TABLE `courses` ADD `score` real;
--> statement-breakpoint
UPDATE `courses` SET `course_class` = CASE
  WHEN `category` = '通識' THEN '通識'
  WHEN `requirement` = '選修' THEN '系選修'
  ELSE '系必修'
END;
--> statement-breakpoint
UPDATE `courses` SET `score` = CASE
  WHEN `grade` GLOB '[0-9]*' THEN CAST(`grade` AS real)
  WHEN `grade` = 'A+' THEN 95 WHEN `grade` = 'A' THEN 92 WHEN `grade` = 'A-' THEN 87
  WHEN `grade` = 'B+' THEN 82 WHEN `grade` = 'B' THEN 78 WHEN `grade` = 'B-' THEN 75
  WHEN `grade` = 'C+' THEN 70 WHEN `grade` = 'C' THEN 68 WHEN `grade` = 'C-' THEN 65
  WHEN `grade` = 'D+' THEN 62 WHEN `grade` = 'D' THEN 60 WHEN `grade` = 'F' THEN 50
  ELSE NULL
END
WHERE `score` IS NULL;
