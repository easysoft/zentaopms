ALTER TABLE `zt_auditresult` ADD `severity` char(30) NOT NULL DEFAULT '' AFTER `comment`;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'file', 'download' FROM `zt_grouppriv` WHERE `module` = 'file' AND `method` = 'preview';
ALTER TABLE zt_metriclib ADD `deleted` ENUM('0', '1') NOT NULL DEFAULT '0' AFTER `date`;
CREATE INDEX `deleted` ON `zt_metriclib` (`deleted`);

ALTER TABLE `zt_pipeline` ADD `instanceID` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `private`;
ALTER TABLE `zt_mr` ADD `isFlow` ENUM('0', '1') NOT NULL DEFAULT '0' AFTER `squash`;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'task', 'recordWorkhour' FROM `zt_grouppriv` WHERE `module` = 'task' AND `method` = 'recordEstimate';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'task', 'editEffort' FROM `zt_grouppriv` WHERE `module` = 'task' AND `method` = 'editEstimate';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'task', 'deleteWorkhour' FROM `zt_grouppriv` WHERE `module` = 'task' AND `method` = 'deleteEstimate';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'bug', 'confirm' FROM `zt_grouppriv` WHERE `module` = 'bug' AND `method` = 'confirmBug';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'testcase', 'batchChangeType' FROM `zt_grouppriv` WHERE `module` = 'testcase' AND `method` = 'batchCaseTypeChange';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'ops', 'provider' FROM `zt_grouppriv` WHERE `module` = 'ops' AND `method` = 'provide';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'projectstory', 'importToLib' FROM `zt_grouppriv` WHERE `module` = 'story' AND `method` = 'importToLib';
REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'projectstory', 'batchImportToLib' FROM `zt_grouppriv` WHERE `module` = 'story' AND `method` = 'batchImportToLib';

CREATE UNIQUE INDEX `account_openID` ON `zt_oauth`(`account`,`openID`,`providerType`,`providerID`);

ALTER TABLE `zt_taskteam` CHANGE `order` `order` int(8) NOT NULL DEFAULT '0';
