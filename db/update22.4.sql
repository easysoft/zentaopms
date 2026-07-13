ALTER TABLE `zt_testrun` ADD COLUMN `caseVersion` smallint unsigned NOT NULL DEFAULT 0 COMMENT '用例版本' AFTER `case`;
ALTER TABLE `zt_suitecase` ADD COLUMN `caseVersion` smallint unsigned NOT NULL DEFAULT 0 COMMENT '用例版本' AFTER `case`;

INSERT INTO `zt_config`(`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('', 'system', 'project',   '', 'ganttVersionSettings', 'deliverable');
INSERT INTO `zt_config`(`vision`, `owner`, `module`, `section`, `key`, `value`) VALUES ('', 'system', 'execution', '', 'ganttVersionSettings', 'gantt');
