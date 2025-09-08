ALTER TABLE `zt_doc` ADD `cycle` char(10) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '' AFTER `chapterType`;
ALTER TABLE `zt_doc` ADD `objects` text COLLATE 'utf8mb4_general_ci' NULL AFTER `templateDesc`;
ALTER TABLE `zt_doc` ADD `cycleConfig` text COLLATE 'utf8mb4_general_ci' NULL AFTER `cycle`;

CREATE INDEX `templateType` ON `zt_doc`(`templateType`);

DELETE FROM `zt_cron` WHERE `command`='moduleName=weekly&methodName=computeWeekly' AND `type`='zentao';
DELETE FROM `zt_cron` WHERE `command`='moduleName=reporttemplate&methodName=createCycleReport' AND `type`='zentao';
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`) VALUES('1','0','*','*','*','moduleName=reporttemplate&methodName=createCycleReport','定时生成报告','zentao',1,'normal');