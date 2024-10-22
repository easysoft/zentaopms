ALTER TABLE `zt_auditresult` ADD `severity` char(30) NOT NULL DEFAULT '' AFTER `comment`;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'file', 'download' FROM `zt_grouppriv` WHERE `module` = 'file' AND `method` = 'preview';
ALTER TABLE zt_metriclib ADD `deleted` ENUM('0', '1') NOT NULL DEFAULT '0' AFTER `date`;
CREATE INDEX `deleted` ON `zt_metriclib` (`deleted`);

ALTER TABLE `zt_pipeline` ADD `instanceID` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `private`;
ALTER TABLE `zt_mr` ADD `isFlow` ENUM('0', '1') NOT NULL DEFAULT '0' AFTER `squash`;

UPDATE `zt_grouppriv` SET `method` = 'recordWorkhour' WHERE `module` = 'task' AND `method` = 'recordEstimate';
UPDATE `zt_grouppriv` SET `method` = 'editEffort' WHERE `module` = 'task' AND `method` = 'editEstimate';
UPDATE `zt_grouppriv` SET `method` = 'deleteWorkhour' WHERE `module` = 'task' AND `method` = 'deleteEstimate';
UPDATE `zt_grouppriv` SET `method` = 'confirm' WHERE `module` = 'bug' AND `method` = 'confirmBug';
UPDATE `zt_grouppriv` SET `method` = 'batchChangeType' WHERE `module` = 'testcase' AND `method` = 'batchCaseTypeChange';
UPDATE `zt_grouppriv` SET `method` = 'provider' WHERE `module` = 'ops' AND `method` = 'provide';

CREATE UNIQUE INDEX `account_openID` ON `zt_oauth`(`account`,`openID`,`providerType`,`providerID`);

ALTER TABLE `zt_taskteam` CHANGE `order` `order` int(8) NOT NULL DEFAULT '0';

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('*/5', '*', '*', '*', '*', 'moduleName=program&methodName=refreshStats', '刷新项目集统计数据', 'zentao', 1, 'normal', NULL),
('*/5', '*', '*', '*', '*', 'moduleName=product&methodName=refreshStats', '刷新产品统计数据',   'zentao', 1, 'normal', NULL);
