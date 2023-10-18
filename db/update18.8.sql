ALTER TABLE `zt_testtask` ADD COLUMN `members` text NULL;
UPDATE `zt_solutions` SET `deleted` = '0' WHERE `deleted` = '';
