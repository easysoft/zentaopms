ALTER TABLE `zt_branch` ADD `default` enum ('0', '1') NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_branch` ADD `status` enum ('active', 'closed') NOT NULL DEFAULT 'active' AFTER `default`;
ALTER TABLE `zt_branch` ADD `desc` varchar(255) NOT NULL AFTER `status`;
ALTER TABLE `zt_branch` ADD `createdDate` date NOT NULL AFTER `desc`;
ALTER TABLE `zt_branch` ADD `closedDate` date NOT NULL AFTER `createdDate`;
ALTER TABLE `zt_projectstory` ADD `branch` mediumint(8) NOT NULL AFTER `product`;
ALTER TABLE `zt_projectproduct` ADD PRIMARY KEY `project_product_branch` (`project`, `product`, `branch`), DROP INDEX `PRIMARY`;
ALTER TABLE `zt_apistruct` CHANGE COLUMN `editEdBy` `editedBy` varchar(30) NOT NULL DEFAULT 0;

-- DROP TABLE IF EXISTS `zt_kanbanlane`;
CREATE TABLE IF NOT EXISTS `zt_kanbanlane` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `execution` mediumint(8) NOT NULL DEFAULT '0',
  `type` char(30) NOT NULL,
  `groupby` char(30) NOT NULL,
  `extra` char(30) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `color` char(30) NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  `lastEditedTime` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_kanbancolumn`;
CREATE TABLE IF NOT EXISTS `zt_kanbancolumn` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `lane` mediumint(8) NOT NULL DEFAULT '0',
  `parent` mediumint(8) NOT NULL DEFAULT '0',
  `type` char(30) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `color` char(30) NOT NULL,
  `limit` smallint(6) NOT NULL DEFAULT '-1',
  `order` mediumint(8) NOT NULL DEFAULT '0',
  `cards` text NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_stage`;
CREATE TABLE IF NOT EXISTS `zt_stage` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `percent` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_design`;
CREATE TABLE IF NOT EXISTS `zt_design` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `commit` text NOT NULL,
  `commitedBy` varchar(30) NOT NULL,
  `execution` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `name` varchar(255) NOT NULL,
  `status` varchar(30) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedBy` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `story` char(30) NOT NULL,
  `desc` text NOT NULL,
  `version` smallint(6) NOT NULL,
  `type` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_designspec`;
CREATE TABLE IF NOT EXISTS `zt_designspec` (
  `design` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `files` varchar(255) NOT NULL,
  UNIQUE KEY `design` (`design`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_weeklyreport`;
CREATE TABLE IF NOT EXISTS `zt_weeklyreport`(
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) unsigned NOT NULL,
  `weekStart` date NOT NULL,
  `pv` float(9,2) NOT NULL,
  `ev` float(9,2) NOT NULL,
  `ac` float(9,2) NOT NULL,
  `sv` float(9,2) NOT NULL,
  `cv` float(9,2) NOT NULL,
  `staff` smallint(5) unsigned NOT NULL,
  `progress` varchar(255) NOT NULL,
  `workload` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `week` (`project`,`weekStart`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_holiday`;
CREATE TABLE IF NOT EXISTS `zt_holiday` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `type` enum('holiday', 'working') NOT NULL DEFAULT 'holiday',
  `desc` text NOT NULL,
  `year` char(4) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `year` (`year`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_mrapproval`;
CREATE TABLE IF NOT EXISTS `zt_mrapproval` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `mrID` mediumint(8) unsigned NOT NULL,
  `account` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `action` char(30) NOT NULL,
  `comment` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

REPLACE INTO `zt_stage` (`name`,`percent`,`type`,`createdBy`,`createdDate`,`editedBy`,`editedDate`,`deleted`) VALUES
('需求','10','request','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('设计','10','design','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('开发','50','dev','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('测试','15','qa','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('发布','10','release','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('总结评审','5','review','admin','2020-02-08 21:08:45','admin','2020-02-12 13:50:27','0');

REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`) VALUES
('all','stage','typeList','request','需求', '1'),
('all','stage','typeList','design','设计', '1'),
('all','stage','typeList','dev','开发', '1'),
('all','stage','typeList','qa','测试', '1'),
('all','stage','typeList','release','发布', '1'),
('all','stage','typeList','review','总结评审','1'),
('all','stage','typeList','other','其他','1');

ALTER TABLE `zt_bug`
ADD `feedbackBy` varchar(100) NOT NULL AFTER `activatedDate`,
ADD `notifyEmail` varchar(100) NOT NULL AFTER `feedbackBy`;

ALTER TABLE `zt_story`
ADD `feedbackBy` varchar(100) NOT NULL AFTER `version`,
ADD `notifyEmail` varchar(100) NOT NULL AFTER `feedbackBy`;

ALTER TABLE `zt_product` ADD `reviewer` varchar(255) NOT NULL AFTER `whitelist`;
UPDATE `zt_testtask` SET `pri`=3 WHERE `pri`=0;

ALTER table zt_mr ADD `approver` varchar(255) NOT NULL,
ADD `approvalStatus` char(30) NOT NULL,
ADD `needApproved` enum('0','1') NOT NULL DEFAULT '0',
ADD `needCI` enum('0','1') NOT NULL DEFAULT '0',
ADD `repoID` mediumint(8) unsigned NOT NULL,
ADD `jobID` mediumint(8) unsigned NOT NULL,
ADD `compileID` mediumint(8) unsigned NOT NULL,
ADD `compileStatus` char(30) NOT NULL;
