REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) VALUES
('zh-cn', 'custom', 'relationList', '1', '{\"relation\":\"\\u76f8\\u5173\",\"relativeRelation\":\"\\u76f8\\u5173\"}', '0', 'all'),
('zh-cn', 'custom', 'relationList', '2', '{\"relation\":\"\\u4f9d\\u8d56\",\"relativeRelation\":\"\\u88ab\\u4f9d\\u8d56\"}', '0', 'all'),
('zh-cn', 'custom', 'relationList', '3', '{\"relation\":\"\\u91cd\\u590d\",\"relativeRelation\":\"\\u91cd\\u590d\"}', '0', 'all'),
('zh-cn', 'custom', 'relationList', '4', '{\"relation\":\"\\u5f15\\u7528\",\"relativeRelation\":\"\\u88ab\\u5f15\\u7528\"}', '0', 'all'),
('en', 'custom', 'relationList', '1', '{\"relation\":\"Relate\",\"relativeRelation\":\"Relate\"}', '0', 'all'),
('en', 'custom', 'relationList', '2', '{\"relation\":\"Dependence\",\"relativeRelation\":\"Depended On\"}', '0', 'all'),
('en', 'custom', 'relationList', '3', '{\"relation\":\"Repetition\",\"relativeRelation\":\"Repetition\"}', '0', 'all'),
('en', 'custom', 'relationList', '4', '{\"relation\":\"Quote\",\"relativeRelation\":\"Quoted\"}', '0', 'all'),
('de', 'custom', 'relationList', '1', '{\"relation\":\"Relate\",\"relativeRelation\":\"Relate\"}', '0', 'all'),
('de', 'custom', 'relationList', '2', '{\"relation\":\"Dependence\",\"relativeRelation\":\"Depended On\"}', '0', 'all'),
('de', 'custom', 'relationList', '3', '{\"relation\":\"Repetition\",\"relativeRelation\":\"Repetition\"}', '0', 'all'),
('de', 'custom', 'relationList', '4', '{\"relation\":\"Quote\",\"relativeRelation\":\"Quoted\"}', '0', 'all'),
('fr', 'custom', 'relationList', '1', '{\"relation\":\"Relate\",\"relativeRelation\":\"Relate\"}', '0', 'all'),
('fr', 'custom', 'relationList', '2', '{\"relation\":\"Dependence\",\"relativeRelation\":\"Depended On\"}', '0', 'all'),
('fr', 'custom', 'relationList', '3', '{\"relation\":\"Repetition\",\"relativeRelation\":\"Repetition\"}', '0', 'all'),
('fr', 'custom', 'relationList', '4', '{\"relation\":\"Quote\",\"relativeRelation\":\"Quoted\"}', '0', 'all'),
('zh-tw', 'custom', 'relationList', '1', '{\"relation\":\"\\u76f8\\u95dc\",\"relativeRelation\":\"\\u76f8\\u95dc\"}', '0', 'all'),
('zh-tw', 'custom', 'relationList', '2', '{\"relation\":\"\\u4f9d\\u8cf4\",\"relativeRelation\":\"\\u88ab\\u4f9d\\u8cf4\"}', '0', 'all'),
('zh-tw', 'custom', 'relationList', '3', '{\"relation\":\"\\u91cd\\u8907\",\"relativeRelation\":\"\\u91cd\\u8907\"}', '0', 'all'),
('zh-tw', 'custom', 'relationList', '4', '{\"relation\":\"\\u5f15\\u7528\",\"relativeRelation\":\"\\u88ab\\u5f15\\u7528\"}', '0', 'all');

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
