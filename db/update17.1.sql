ALTER TABLE `zt_user` ADD `resetToken` varchar(50) NOT NULL AFTER `scoreLevel`;
CREATE TABLE `zt_riskissue` (
  `risk` mediumint(8) unsigned NOT NULL,
  `issue` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `risk_issue` (`risk`,`issue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `zt_projectadmin` (
  `group` smallint(6) NOT NULL,
  `account` char(30) NOT NULL,
  `programs` text NOT NULL,
  `projects` text NOT NULL,
  `products` text NOT NULL,
  `executions` text NOT NULL,
  UNIQUE KEY `group_account` (`group`, `account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_kanban` ADD `showWIP` enum('0','1') NOT NULL DEFAULT '1' AFTER `displayCards`;
ALTER TABLE `zt_kanban` ADD `alignment` varchar(10) NOT NULL DEFAULT 'center' AFTER `object`;

ALTER TABLE `zt_module` ADD `from` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `type`;

ALTER TABLE `zt_story` ADD `changedBy` VARCHAR(30) NOT NULL AFTER `lastEditedDate`;
ALTER TABLE `zt_story` ADD `changedDate` DATETIME NOT NULL AFTER `changedBy`;

ALTER TABLE `zt_action` CHANGE `id` `id` int(9) unsigned NOT NULL AUTO_INCREMENT FIRST;
ALTER TABLE `zt_history` CHANGE `id` `id` int(9) unsigned NOT NULL AUTO_INCREMENT FIRST;

ALTER TABLE `zt_workflow` ADD `approval` enum('enabled', 'disabled') NOT NULL DEFAULT 'disabled' AFTER `status`;
ALTER TABLE `zt_workflowaction` ADD `role` varchar(10) NOT NULL DEFAULT 'custom' AFTER `buildin`;
ALTER TABLE `zt_workflowfield` ADD `role` varchar(10) NOT NULL DEFAULT 'custom' AFTER `buildin`;
ALTER TABLE `zt_workflowlabel` ADD `role` varchar(10) NOT NULL DEFAULT 'custom' AFTER `buildin`;

UPDATE `zt_workflowaction` SET `role` = 'buildin' WHERE `role` = 'custom' AND `buildin` = '1';
UPDATE `zt_workflowaction` SET `role` = 'virtual' WHERE `role` = 'custom' AND `virtual` = '1';
UPDATE `zt_workflowaction` SET `role` = 'default' WHERE `role` = 'custom' AND `action` IN ('browse', 'create', 'batchcreate', 'edit', 'view', 'delete', 'link', 'unlink', 'export', 'exporttemplate', 'import', 'showimport', 'report', 'assign', 'batchedit', 'batchassign');
UPDATE `zt_workflowfield` SET `role` = 'buildin' WHERE `role` = 'custom' AND (`buildin` = '1' OR `field` = 'subStatus');
UPDATE `zt_workflowfield` SET `role` = 'default' WHERE `role` = 'custom' AND `field` IN ('id', 'parent', 'assignedTo', 'status', 'createdBy', 'createdDate', 'editedBy', 'editedDate', 'assignedBy', 'assignedDate' 'mailto', 'deleted');
UPDATE `zt_workflowlabel` SET `role` = 'buildin' WHERE `role` = 'custom' AND `buildin` = '1';
