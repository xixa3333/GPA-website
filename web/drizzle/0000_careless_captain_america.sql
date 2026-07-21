CREATE TABLE `courses` (
	`id` integer PRIMARY KEY AUTOINCREMENT NOT NULL,
	`owner_email` text NOT NULL,
	`semester` text NOT NULL,
	`name` text NOT NULL,
	`credits` real NOT NULL,
	`grade` text NOT NULL,
	`grade_points` real NOT NULL,
	`created_at` text DEFAULT CURRENT_TIMESTAMP NOT NULL
);
--> statement-breakpoint
CREATE INDEX `courses_owner_idx` ON `courses` (`owner_email`);--> statement-breakpoint
CREATE INDEX `courses_owner_semester_idx` ON `courses` (`owner_email`,`semester`);