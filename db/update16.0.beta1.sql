-- DROP TABLE IF EXISTS `zt_kanbanspace`;
CREATE TABLE `zt_kanbanspace` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `team` text NOT NULL,
  `desc` text NOT NULL,
  `acl` char(30) NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
  `status` enum('active','closed') NOT NULL default 'active',
  `order` smallint(6) NOT NULL DEFAULT '0',
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `lastEditedBy` char(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL,
  `closedBy` char(30) NOT NULL,
  `closedDate` datetime NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_kanban`;
CREATE TABLE `zt_kanban` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `space` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `owner` varchar(30) NOT NULL,
  `team` text NOT NULL,
  `desc` text NOT NULL,
  `acl` char(30) NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
  `archived` enum('0', '1') NOT NULL DEFAULT '0',
  `status` enum('active','closed') NOT NULL default 'active',
  `order` smallint(6) NOT NULL DEFAULT '0',
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `lastEditedBy` char(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL,
  `closedBy` char(30) NOT NULL,
  `closedDate` datetime NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_kanbanregion`;
CREATE TABLE `zt_kanbanregion` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `space` mediumint(8) unsigned NOT NULL,
  `kanban` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `lastEditedBy` char(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_kanbancard`;
CREATE TABLE `zt_kanbancard` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `kanban` mediumint(8) unsigned NOT NULL,
  `region` mediumint(8) unsigned NOT NULL,
  `group` mediumint(8) unsigned NOT NULL,
  `lane` mediumint(8) unsigned NOT NULL,
  `column` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `pri` mediumint(8) unsigned NOT NULL,
  `assignedTo` text NOT NULL,
  `desc` text NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `estimate` float unsigned NOT NULL,
  `color` char(7) NOT NULL,
  `acl` char(30) NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  `archived` enum('0', '1') NOT NULL DEFAULT '0',
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `lastEditedBy` char(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL,
  `archivedBy` char(30) NOT NULL,
  `archivedDate` datetime NOT NULL,
  `assignedBy` char(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_kanbangroup`;
CREATE TABLE `zt_kanbangroup` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `kanban` mediumint(8) unsigned NOT NULL,
  `region` mediumint(8) unsigned NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_kanbanlane` ADD COLUMN `region` mediumint(8) unsigned NOT NULL AFTER `type`;
ALTER TABLE `zt_kanbanlane` ADD COLUMN `group` mediumint(8) unsigned NOT NULL AFTER `region`;
ALTER TABLE `zt_kanbancolumn` ADD COLUMN `region` mediumint(8) unsigned NOT NULL AFTER `type`;
ALTER TABLE `zt_kanbancolumn` ADD COLUMN `group` mediumint(8) unsigned NOT NULL AFTER `region`;
ALTER TABLE `zt_kanbancolumn` ADD COLUMN  `archived` enum('0', '1') NOT NULL DEFAULT '0' AFTER `cards`;
ALTER TABLE `zt_project` ADD COLUMN `suspendedDate` date NOT NULL AFTER `canceledDate`;

ALTER TABLE `zt_task` ADD `repo` mediumint unsigned NOT NULL AFTER `activatedDate`;
ALTER TABLE `zt_task` ADD `entry` varchar(255) NOT NULL AFTER `repo`;
ALTER TABLE `zt_task` ADD `lines` varchar(10) NOT NULL AFTER `entry`;
ALTER TABLE `zt_task` ADD `v1` varchar(40) NOT NULL AFTER `lines`;
ALTER TABLE `zt_task` ADD `v2` varchar(40) NOT NULL AFTER `v1`;
ALTER TABLE `zt_task` ADD `mr` mediumint(8) unsigned NOT NULL AFTER `repo`;
ALTER TABLE `zt_bug` ADD `mr` mediumint(8) unsigned NOT NULL AFTER `repo`;

UPDATE `zt_grouppriv` SET `method`='addReview' where `module`='mr' and `method`='addBug';

ALTER TABLE `zt_repo` ADD `preMerge` enum('0','1') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '0' AFTER `extra`;
ALTER TABLE `zt_repo` ADD `job` mediumint unsigned NOT NULL AFTER `preMerge`;
ALTER TABLE `zt_mr` ADD `synced` enum('0','1') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '1';
ALTER TABLE `zt_mr` ADD `hasNoConflict` enum('0','1') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '0';
ALTER TABLE `zt_mr` ADD `diffs` longtext COLLATE 'utf8_general_ci' NULL AFTER `synced`;
ALTER TABLE `zt_mr` ADD `removeSourceBranch` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `compileStatus`;
ALTER TABLE `zt_mr` ADD `syncError` VARCHAR(255) NOT NULL AFTER `synced`;

ALTER TABLE zt_repo ADD `fileServerUrl` text COLLATE 'utf8_general_ci' NULL AFTER `job`;
ALTER TABLE zt_repo ADD `fileServerAccount` varchar(40) NOT NULL default '' AFTER `fileServerUrl`;
ALTER TABLE zt_repo ADD `fileServerPassword` varchar(100) NOT NULL default '' AFTER `fileServerAccount`;
