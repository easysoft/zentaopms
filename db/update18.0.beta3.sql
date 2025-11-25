ALTER TABLE `zt_host` ADD `type` varchar(30) NOT NULL DEFAULT 'normal' AFTER `admin`;
ALTER TABLE `zt_host` ADD `secret` varchar(50) NOT NULL DEFAULT '' AFTER `type`;
ALTER TABLE `zt_host` ADD `token` varchar(50) NOT NULL DEFAULT '' AFTER `secret`;
ALTER TABLE `zt_host` ADD `expiredDate` datetime NOT NULL AFTER `token`;
ALTER TABLE `zt_host` ADD `virtualSoftware` varchar(30) NOT NULL DEFAULT '' AFTER `expiredDate`;

ALTER TABLE `zt_host`
DROP COLUMN `cabinet`,
DROP COLUMN `cpuRate`,
DROP COLUMN `diskType`,
DROP COLUMN `unit`,
DROP COLUMN `nic`,
DROP COLUMN `webserver`,
DROP COLUMN `database`,
DROP COLUMN `language`,
DROP COLUMN `instanceNum`,
DROP COLUMN `pri`,
DROP COLUMN `tags`,
DROP COLUMN `bridgeID`,
DROP COLUMN `cloudKey`,
DROP COLUMN `cloudSecret`,
DROP COLUMN `cloudRegion`,
DROP COLUMN `cloudNamespace`,
DROP COLUMN `cloudUser`,
DROP COLUMN `cloudAccount`,
DROP COLUMN `cloudPassword`,
DROP COLUMN `couldVPC`,
ADD COLUMN `name` varchar(255) NOT NULL DEFAULT '' AFTER `id`,
MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT 'normal' AFTER `name`,
MODIFY COLUMN `hostType` varchar(30) NOT NULL DEFAULT '' AFTER `type`,
MODIFY COLUMN `mac` varchar(128) NOT NULL AFTER `hostType`,
MODIFY COLUMN `memory` varchar(30) NOT NULL AFTER `mac`,
MODIFY COLUMN `diskSize` varchar(30) NOT NULL AFTER `memory`,
MODIFY COLUMN `status` varchar(50) NOT NULL AFTER `diskSize`,
MODIFY COLUMN `secret` varchar(50) NOT NULL DEFAULT '' AFTER `status`,
ADD COLUMN `desc` text NOT NULL AFTER `secret`,
CHANGE COLUMN `token` `tokenSN` varchar(50) NOT NULL DEFAULT '' AFTER `desc`,
CHANGE COLUMN `expiredDate` `tokenTime` datetime NOT NULL AFTER `tokenSN`,
ADD COLUMN `oldTokenSN` varchar(50) NOT NULL DEFAULT '' AFTER `tokenTime`,
CHANGE COLUMN `virtualSoftware` `vsoft` varchar(30) NOT NULL DEFAULT '' AFTER `tokenTime`,
CHANGE COLUMN `heartbeatTime` `heartbeat` datetime NOT NULL AFTER `vsoft`,
CHANGE COLUMN `agentPort` `zap` varchar(10) NOT NULL AFTER `heartbeat`,
MODIFY COLUMN `provider` varchar(255) NOT NULL DEFAULT '' AFTER `zap`,
ADD COLUMN `vnc` int(11) NOT NULL AFTER `provider`,
ADD COLUMN `ztf` int(11) NOT NULL AFTER `vnc`,
ADD COLUMN `zd` int(11) NOT NULL AFTER `ztf`,
ADD COLUMN `ssh` int(11) NOT NULL AFTER `zd`,
ADD COLUMN `parent` int(11) unsigned NOT NULL DEFAULT '0' AFTER `vnc`,
ADD COLUMN `image` int(11) unsigned NOT NULL DEFAULT '0' AFTER `parent`,
ADD COLUMN `group` varchar(128) NOT NULL DEFAULT '' AFTER `osVersion`,
ADD COLUMN `createdBy` varchar(30) NOT NULL,
ADD COLUMN  `createdDate` datetime NOT NULL,
ADD COLUMN  `editedBy` varchar(30) NOT NULL,
ADD COLUMN  `editedDate` datetime NOT NULL,
ADD COLUMN  `deleted` enum('0','1') NOT NULL DEFAULT '0',
CHANGE COLUMN `privateIP` `intranet` varchar(128) NOT NULL AFTER `cpuCores`,
CHANGE COLUMN `publicIP` `extranet` varchar(128) NOT NULL AFTER `intranet`;

UPDATE zt_host h,
zt_asset a
SET h.`name` = a.`name`,
h.`createdBy` = a.`createdBy`,
h.`createdDate` = a.`createdDate`,
h.`editedBy` = a.`editedBy`,
h.`editedDate` = a.`editedDate`,
h.`group` = a.`group`,
h.`deleted` = a.`deleted`
WHERE
h.`assetID` = a.`id`;

ALTER TABLE `zt_host` DROP COLUMN `assetID`;

DROP TABLE IF EXISTS `zt_asset`;
DROP TABLE IF EXISTS `zt_baseimagebrowser`;
DROP TABLE IF EXISTS `zt_browser`;
DROP TABLE IF EXISTS `zt_baseimage`;
DROP TABLE IF EXISTS `zt_vmtemplate`;
DROP TABLE IF EXISTS `zt_vm`;

CREATE TABLE `zt_image` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `host` int(11) unsigned NOT NULL DEFAULT 0,
  `name` varchar(64) NOT NULL DEFAULT '',
  `address` varchar(64) NOT NULL DEFAULT '',
  `path` varchar(64) NOT NULL DEFAULT '',
  `status` varchar(20) NOT NULL DEFAULT '',
  `osName` varchar(32) NOT NULL DEFAULT '',
  `from` varchar(10) NOT NULL DEFAULT 'zentao',
  `memory` float unsigned NOT NULL,
  `disk` float unsigned NOT NULL,
  `fileSize` float unsigned NOT NULL,
  `md5` varchar(64) NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `zt_automation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `node` int(11) unsigned NOT NULL DEFAULT 0,
  `product` int(11) unsigned NOT NULL DEFAULT 0,
  `scriptPath` varchar(255) NOT NULL DEFAULT '',
  `shell` mediumtext NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

ALTER TABLE `zt_repofiles` ADD `oldPath` varchar(255) DEFAULT '' AFTER `path`;
ALTER TABLE `zt_case` ADD `script` longtext NOT NULL AFTER `howRun`;
ALTER TABLE `zt_testresult` ADD `ZTFResult` text NOT NULL AFTER `stepResults`;
ALTER TABLE `zt_testresult` ADD `node` int(8) unsigned NOT NULL DEFAULT '0' AFTER `ZTFResult`;

REPLACE INTO `zt_workflow` (`parent`, `child`, `type`, `navigator`, `app`, `position`, `module`, `table`, `name`, `flowchart`, `js`, `css`, `order`, `buildin`, `administrator`, `desc`, `version`, `status`, `approval`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `titleField`, `contentField`) VALUES
('', '', 'flow', 'secondary', 'feedback', '', 'ticket', 'zt_ticket', '工单', '', '', '', 0, 1, '', '', '1.0', 'normal', 'disabled', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', '', ''),
('', '', 'flow', 'secondary', 'feedback', '', 'ticket', 'zt_ticket', '工单', '', '', '', 0, 1, '', '', '1.0', 'normal', 'disabled', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', '', '');

REPLACE INTO `zt_workflowfield` (`module`, `field`, `type`, `length`, `name`, `control`, `expression`, `options`, `default`, `rules`, `placeholder`, `canExport`, `canSearch`, `isValue`, `order`, `searchOrder`, `exportOrder`, `buildin`, `role`, `desc`, `readonly`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('ticket', 'id', 'mediumint', '8', '编号', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'product', 'mediumint', '8', '所属产品', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'module', 'mediumint', '8', '所属模块', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'title', 'varchar', '255', '标题', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'type', 'varchar', '30', '类型', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'desc', 'text', '', '描述', 'textarea', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'openedBuild', 'varchar', '255', '影响版本', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'feedback', 'mediumint', '8', '来源反馈', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'assignedTo', 'varchar', '255', '指派给', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'assignedDate', 'datetime', '', '指派日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'realStarted', 'datetime', '', '实际开始', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'startedBy', 'varchar', '255', '由谁开始', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'startedDate', 'datetime', '', '开始日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'deadline', 'date', '', '截止日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'pri', 'tinyint', '3', '优先级', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'estimate', 'float', '', '最初预计', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'left', 'float', '', '预计剩余', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'status', 'varchar', '255', '状态', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'subStatus', 'varchar', '30', '子状态', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 0, 'buildin', '', '0', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'openedBy', 'varchar', '30', '由谁创建', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'openedDate', 'datetime', '', '创建日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'activatedCount', 'int', '10', '激活次数', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'activatedBy', 'varchar', '30', '由谁激活', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'activatedDate', 'datetime', '', '激活日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'closedBy', 'varchar', '30', '由谁关闭', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'closedDate', 'datetime', '', '关闭日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'closedReason', 'varchar', '30', '关闭原因', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'finishedBy', 'varchar', '30', '由谁完成', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'finishedDate', 'datetime', '', '完成日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'resolvedBy', 'varchar', '30', '由谁解决', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'resolvedDate', 'datetime', '', '解决日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'resolution', 'varchar', '1000', '解决方案', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'editedBy', 'varchar', '30', '由谁编辑', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'editedDate', 'datetime', '', '编辑日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'keywords', 'varchar', '255', '关键词', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'repeatTicket', 'mediumint', '8', '重复工单', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'mailto', 'varchar', '255', '抄送给', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'consumed', 'float', '', '消耗', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'deleted', 'enum', '', '是否删除', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00');

REPLACE INTO `zt_workflowaction` (`module`, `action`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `role`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `method`) VALUES
('ticket', 'browse', '浏览工单', 'batch', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'browse'),
('ticket', 'create', '创建工单', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'create'),
('ticket', 'edit', '编辑工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'edit'),
('ticket', 'batchedit', '批量编辑', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'start', '开始工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'finish', '完成工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'activate', '激活工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'assign', '指派工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'close', '关闭工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'view', '工单详情', 'single', 'different', 'none', 'normal', 'browse', 'side', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'view'),
('ticket', 'browse', '浏览工单', 'batch', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'browse'),
('ticket', 'create', '创建工单', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'create'),
('ticket', 'edit', '编辑工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'edit'),
('ticket', 'batchedit', '批量编辑', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'start', '开始工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'finish', '完成工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'activate', '激活工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'assign', '指派工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'close', '关闭工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'view', '工单详情', 'single', 'different', 'none', 'normal', 'browse', 'side', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'view');

UPDATE `zt_workflowfield` SET `name` = '类型'  WHERE `module` = 'feedback' and `field` = 'type';
UPDATE `zt_workflowfield` SET `name` = '创建者'  WHERE `module` = 'feedback' and `field` = 'openedBy';
UPDATE `zt_workflowfield` SET `name` = '创建时间'  WHERE `module` = 'feedback' and `field` = 'openedDate';
UPDATE `zt_workflowfield` SET `name` = '反馈邮箱'  WHERE `module` = 'feedback' and `field` = 'notifyEmail';
UPDATE `zt_workflowfield` SET `name` = '最后操作'  WHERE `module` = 'feedback' and `field` = 'editedBy';
UPDATE `zt_workflowfield` SET `name` = '最后操作时间'  WHERE `module` = 'feedback' and `field` = 'editedDate';

REPLACE INTO `zt_workflowfield` (`module`, `field`, `type`, `length`, `name`, `control`, `expression`, `options`, `default`, `rules`, `placeholder`, `canExport`, `canSearch`, `isValue`, `order`, `searchOrder`, `exportOrder`, `buildin`, `role`, `desc`, `readonly`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('feedback', 'pri', 'tinyint', '3', '优先级', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('feedback', 'source', 'varchar', '30', '来源公司', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('feedback', 'repeatFeedback', 'varchar', '30', '重复反馈', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('feedback', 'activatedBy', 'varchar', '30', '由谁激活', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('feedback', 'activatedDate', 'datetime', '', '激活时间', 'datetime', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00');

REPLACE INTO `zt_workflowaction` (`module`, `action`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `role`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `method`) VALUES
('feedback', 'activate', '激活反馈', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'activate'),
('feedback', 'import', '导入', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'import'),
('feedback', 'exporttemplate', '导出模板', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'exporttemplate'),
('feedback', 'batchclose', '批量关闭', 'batch', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'batchclose');
