ALTER TABLE `zt_mr` ADD executionID mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `jobID`;
ALTER TABLE `zt_testtask` ADD COLUMN `members` text NULL;
UPDATE `zt_solutions` SET `deleted` = '0' WHERE `deleted` = '';
