ALTER TABLE `zt_mr` ADD executionID mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `jobID`;
ALTER TABLE `zt_testtask` ADD COLUMN `members` text NULL;
UPDATE `zt_solutions` SET `deleted` = '0' WHERE `deleted` = '';

/* Update createdBy to system in chart and pivot. */
UPDATE `zt_chart` SET `createdBy` = 'system' WHERE `builtin` = '1';
UPDATE `zt_pivot` SET `createdBy` = 'system' WHERE `id` >= 1000 and `id` <= 1027;
