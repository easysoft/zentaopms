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
  `lane` mediumint(8) unsigned NOT NULL,
  `column` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `pri` mediumint(8) unsigned NOT NULL,
  `assignedTo` text NOT NULL,
  `desc` text NOT NULL,
  `begin` datetime NOT NULL,
  `end` datetime NOT NULL,
  `estimate` float unsigned NOT NULL,
  `color` char(7) NOT NULL,
  `acl` char(30) NOT NULL DEFAULT 'open',
  `whitelist` text NOT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_kanbanorder`;
CREATE TABLE `zt_kanbanorder` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parentID` mediumint(8) unsigned NOT NULL,
  `parentType` varchar(20) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `objectType` varchar(20) NOT NULL,
  `account` varchar(30) NOT NULL,
  `order` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_kanbanlane` ADD COLUMN `region` mediumint(8) unsigned NOT NULL AFTER `type`;
ALTER TABLE `zt_kanbanlane` ADD COLUMN `group` mediumint(8) unsigned NOT NULL AFTER `region`;
ALTER TABLE `zt_kanbancolumn` ADD COLUMN `region` mediumint(8) unsigned NOT NULL AFTER `type`;
ALTER TABLE `zt_kanbancolumn` ADD COLUMN `group` mediumint(8) unsigned NOT NULL AFTER `region`;
