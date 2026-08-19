ALTER TABLE `zt_task` ADD COLUMN `deliverable` text DEFAULT NULL COMMENT '任务关联的交付物类型' AFTER `desc`;
