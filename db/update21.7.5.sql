ALTER TABLE `zt_doc` ADD `cycle` char(10) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '' AFTER `chapterType`;
ALTER TABLE `zt_doc` ADD `objects` text COLLATE 'utf8mb4_general_ci' NULL AFTER `templateDesc`;
ALTER TABLE `zt_doc` ADD `cycleConfig` text COLLATE 'utf8mb4_general_ci' NULL AFTER `cycle`;
ALTER TABLE `zt_doc` ADD `weeklyDate` char(8) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '' AFTER `collects`;

CREATE INDEX `templateType` ON `zt_doc`(`templateType`);

DELETE FROM `zt_cron` WHERE `command`='moduleName=weekly&methodName=computeWeekly' AND `type`='zentao';
DELETE FROM `zt_cron` WHERE `command`='moduleName=weekly&methodName=createCycleReport' AND `type`='zentao';
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES('1','0','*','*','*','moduleName=weekly&methodName=createCycleReport','定时生成报告','zentao',1,'normal');

UPDATE `zt_ai_miniprogram` SET `model` = 0 WHERE `model` IS NULL;
UPDATE `zt_ai_prompt` SET `model` = 0 WHERE `model` IS NULL;
UPDATE `zt_ai_promptrole` SET `model` = 0 WHERE `model` IS NULL;

ALTER TABLE `zt_ai_miniprogram` MODIFY `model` varchar(255) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '';
ALTER TABLE `zt_ai_prompt` MODIFY `model` varchar(255) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '';
ALTER TABLE `zt_ai_promptrole` MODIFY `model` varchar(255) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '';

UPDATE `zt_ai_miniprogram` SET `model` = '' WHERE `model` = '0';
UPDATE `zt_ai_prompt` SET `model` = '' WHERE `model` = '0';
UPDATE `zt_ai_promptrole` SET `model` = '' WHERE `model` = '0';
