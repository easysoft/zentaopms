UPDATE `zt_workflowfield` SET `control` = 'multi-select' WHERE `control` = 'multi-selec';
DELETE FROM `zt_workflowaction` WHERE `action` IN ('link', 'unlink');
ALTER TABLE `zt_workflowlabel`
ADD `type` enum('data', 'sql') NOT NULL DEFAULT 'data' AFTER `label`,
ADD `sql` text NULL AFTER `params`;
ALTER TABLE `zt_workflowlayout` ADD `ditto` tinyint unsigned NOT NULL DEFAULT 0 AFTER `position`;

ALTER TABLE `zt_testrun` ADD COLUMN `caseVersion` smallint unsigned NOT NULL DEFAULT 0 COMMENT '用例版本' AFTER `case`;
ALTER TABLE `zt_suitecase` ADD COLUMN `caseVersion` smallint unsigned NOT NULL DEFAULT 0 COMMENT '用例版本' AFTER `case`;

INSERT INTO `zt_config`(`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('', 'system', 'project',   '', 'ganttVersionSettings', 'deliverable');
INSERT INTO `zt_config`(`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('', 'system', 'execution', '', 'ganttVersionSettings', 'gantt');
