ALTER TABLE `zt_auditresult` ADD `severity` char(30) NOT NULL DEFAULT '' AFTER `comment`;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'file', 'download' FROM `zt_grouppriv` WHERE `module` = 'file' AND `method` = 'preview';
ALTER TABLE zt_metriclib ADD `deleted` ENUM('0', '1') NOT NULL DEFAULT '0' AFTER `date`;
CREATE INDEX `deleted` ON `zt_metriclib` (`deleted`);

ALTER TABLE `zt_pipeline` ADD `instanceID` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `private`;
ALTER TABLE `zt_mr` ADD `isFlow` ENUM('0', '1') NOT NULL DEFAULT '0' AFTER `squash`;
