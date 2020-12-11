ALTER TABLE `zt_project` DROP `isCat`, DROP `catID`;
ALTER TABLE `zt_project` ADD `project` mediumint(8) NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `zt_project` ADD `model` char(30) NOT NULL AFTER `project`;
ALTER TABLE `zt_project` CHANGE `type` `type` char(30) NOT NULL DEFAULT 'sprint' AFTER `model`;
ALTER TABLE `zt_project` CHANGE `acl` `acl` char(30) NOT NULL DEFAULT 'open';
ALTER TABLE `zt_project` ADD `product` varchar(20) NOT NULL DEFAULT 'single' AFTER `type`;
ALTER TABLE `zt_project` ADD `lifetime` char(30) NOT NULL AFTER `product`;
ALTER TABLE `zt_project` ADD `budget` varchar(30) NOT NULL DEFAULT '0' AFTER `lifetime`;
ALTER TABLE `zt_project` ADD `budgetUnit` char(30) NOT NULL  DEFAULT 'yuan' AFTER `budget`;
ALTER TABLE `zt_project` ADD `percent` float unsigned NOT NULL DEFAULT '0' AFTER `budgetUnit`;
ALTER TABLE `zt_project` ADD `path` varchar(255) NOT NULL AFTER `parent`;
ALTER TABLE `zt_project` ADD `grade` tinyint unsigned NOT NULL AFTER `path`;
ALTER TABLE `zt_project` ADD `auth` char(30) NOT NULL AFTER `percent`;
ALTER TABLE `zt_project` ADD `milestone` enum('0','1') NOT NULL default '0' AFTER `percent`; 
ALTER TABLE `zt_project` ADD `attribute` varchar(30) NOT NULL DEFAULT '' AFTER `budgetUnit`; 
ALTER TABLE `zt_project` ADD `realBegan` date NOT NULL AFTER `end`;
ALTER TABLE `zt_project` ADD `realEnd` date NOT NULL AFTER `realBegan`;
ALTER TABLE `zt_project` ADD `version` smallint(6) NOT NULL AFTER `desc`;
ALTER TABLE `zt_project` ADD `parentVersion` smallint(6) NOT NULL AFTER `version`;
ALTER TABLE `zt_project` ADD `planDuration` int(11) NOT NULL AFTER `parentVersion`;
ALTER TABLE `zt_project` ADD `realDuration` int(11) NOT NULL AFTER `planDuration`;
ALTER TABLE `zt_project` ADD `output` text NOT NULL AFTER `milestone`;

ALTER TABLE `zt_product` ADD `program` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_product` ADD `bind` enum('0','1') NOT NULL DEFAULT '0' AFTER `code`;
ALTER TABLE `zt_task` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_doc` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_story` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_repo` ADD `PRJ` varchar(255) NOT NULL AFTER `id`;
ALTER TABLE `zt_bug` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_case` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_testtask` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_testreport` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_testsuite` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_build` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_release` ADD `PRJ` mediumint(8) unsigned NOT NULL AFTER `id`;

ALTER TABLE `zt_group` ADD `PRJ` mediumint NOT NULL AFTER `id`;
INSERT INTO `zt_group` (`name`, `role`, `desc`) VALUES ('项目管理员', 'PRJAdmin', '项目管理员可以维护项目的权限');

ALTER TABLE `zt_usergroup` ADD `PRJ` text NOT NULL;

ALTER TABLE `zt_userview` ADD `programs` mediumtext NOT NULL AFTER `account`;
ALTER TABLE `zt_userview` ADD `sprints` mediumtext NOT NULL AFTER `programs`;

ALTER TABLE `zt_user` ADD `company` mediumint unsigned NOT NULL AFTER `id`;
ALTER TABLE `zt_user` ADD `type` char(30) NOT NULL default 'inside' AFTER `account`;
ALTER TABLE `zt_user` ADD `nature` text NOT NULL AFTER `zipcode`;
ALTER TABLE `zt_user` ADD `analysis` text NOT NULL AFTER `nature`;
ALTER TABLE `zt_user` ADD `strategy` text NOT NULL AFTER `analysis`;

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

-- DROP TABLE IF EXISTS `zt_stakeholder`;
CREATE TABLE IF NOT EXISTS `zt_stakeholder` (
 `id` mediumint(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
 `objectID` mediumint(8) NOT NULL,
 `objectType` char(30) NOT NULL,
 `user` char(30) NOT NULL,
 `type` char(30) NOT NULL,
 `key` enum('0','1') NOT NULL,
 `from` char(30) NOT NULL,
 `createdBy` char(30) NOT NULL,
 `createdDate` date NOT NULL,
 `editedBy` char(30) NOT NULL,
 `editedDate` date NOT NULL,
 `deleted` enum('0','1') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_expect`;
CREATE TABLE IF NOT EXISTS `zt_expect` (
  `id` mediumint(8) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `userID` mediumint(8) NOT NULL,
  `PRJ` mediumint(8) NOT NULL DEFAULT 0,
  `expect` text NOT NULL,
  `progress` text NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` date NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

REPLACE INTO `zt_stage` (`name`,`percent`,`type`,`createdBy`,`createdDate`,`editedBy`,`editedDate`,`deleted`) VALUES 
('需求','10','request','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('设计','10','design','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('开发','50','dev','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('测试','15','qa','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('发布','10','release','admin','2020-02-08 21:08:30','admin','2020-02-12 13:50:27','0'),
('总结评审','5','review','admin','2020-02-08 21:08:45','admin','2020-02-12 13:50:27','0');

-- DROP TABLE IF EXISTS `zt_design`;
CREATE TABLE IF NOT EXISTS `zt_design` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `commit` text NOT NULL,
  `project` mediumint(9) NOT NULL DEFAULT '0',
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

CREATE TABLE IF NOT EXISTS `zt_designspec` (
  `design` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `files` varchar(255) NOT NULL,
  UNIQUE KEY `design` (`design`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_issue`;
CREATE TABLE IF NOT EXISTS `zt_issue` (
  `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `resolvedBy` varchar(30) NOT NULL,
  `PRJ` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `pri` char(30) NOT NULL,
  `severity` char(30) NOT NULL,
  `type` char(30) NOT NULL,
  `activity` varchar(255) NOT NULL,
  `deadline` date NOT NULL,
  `resolution` char(30) NOT NULL,
  `resolutionComment` text NOT NULL,
  `objectID` varchar(255) NOT NULL,
  `resolvedDate` date NOT NULL,
  `status` varchar(30) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `activateBy` varchar(30) NOT NULL,
  `activateDate` date NOT NULL,
  `closeBy` varchar(30) NOT NULL,
  `closedDate` date NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedBy` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_risk` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `source` char(30) NOT NULL,
  `category` char(30) NOT NULL,
  `strategy` char(30) NOT NULL,
  `status` varchar(30) NOT NULL DEFAULT 'active',
  `impact` char(30) NOT NULL,
  `probability` char(30) NOT NULL,
  `rate` char(30) NOT NULL,
  `pri` char(30) NOT NULL,
  `identifiedDate` date NOT NULL,
  `prevention` text NOT NULL,
  `remedy` text NOT NULL,
  `plannedClosedDate` date NOT NULL,
  `actualClosedDate` date NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `resolution` text NOT NULL,
  `resolvedBy` varchar(30) NOT NULL,
  `activateBy` varchar(30) NOT NULL,
  `activateDate` date NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `cancelBy` varchar(30) NOT NULL,
  `cancelDate` date NOT NULL,
  `cancelReason` char(30) NOT NULL,
  `hangupBy` varchar(30) NOT NULL,
  `hangupDate` date NOT NULL,
  `trackedBy` varchar(30) NOT NULL,
  `trackedDate` date NOT NULL,
  `assignedDate` date NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
   PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_projectspec`;
CREATE TABLE IF NOT EXISTS `zt_projectspec` (
  `project` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `milestone` enum('0','1') NOT NULL DEFAULT '0',
  `begin` date NOT NULL,
  `end` date NOT NULL,
  UNIQUE KEY `project` (`project`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_budget`;
CREATE TABLE IF NOT EXISTS `zt_budget` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `PRJ` mediumint(8) NOT NULL,
  `stage` char(30) NOT NULL,
  `subject` mediumint(8) NOT NULL,
  `amount` char(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` date NOT NULL,
  `lastEditedBy` char(30) NOT NULL,
  `lastEditedDate` date NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_durationestimation`;
CREATE TABLE IF NOT EXISTS `zt_durationestimation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` mediumint(8) unsigned NOT NULL,
  `stage` mediumint(9) NOT NULL,
  `workload` varchar(255) NOT NULL,
  `worktimeRate` varchar(255) NOT NULL,
  `people` varchar(255) NOT NULL,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_workestimation`;
CREATE TABLE IF NOT EXISTS `zt_workestimation` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` mediumint(8) unsigned NOT NULL,
  `scale` mediumint(8) unsigned NOT NULL,
  `productivity` smallint(3) unsigned NOT NULL,
  `duration` mediumint(8) unsigned NOT NULL,
  `unitLaborCost` mediumint(8) unsigned NOT NULL,
  `totalLaborCost` mediumint(8) unsigned NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `dayHour` float(5,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

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

ALTER TABLE `zt_task` ADD `design` mediumint(8) unsigned NOT NULL AFTER `module`;
ALTER TABLE `zt_task` ADD `version` smallint(6) NOT NULL AFTER `desc`;
ALTER TABLE `zt_task` ADD `activatedDate` date NOT NULL AFTER `lastEditedDate`;
ALTER TABLE `zt_task` ADD `planDuration` int(11) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_task` ADD `realDuration` int(11) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_task` ADD `designVersion` smallint(6) unsigned NOT NULL AFTER `storyVersion`;

ALTER TABLE `zt_burn` ADD `storyPoint` float NOT NULL AFTER `consumed`;
ALTER TABLE `zt_burn` ADD `product` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `project`;

-- DROP TABLE IF EXISTS `zt_taskspec`;
CREATE TABLE IF NOT EXISTS `zt_taskspec` (
  `task` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `estStarted` date NOT NULL,
  `deadline` date NOT NULL,
  UNIQUE KEY `task` (`task`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_weeklyreport`(
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` mediumint(8) unsigned NOT NULL,
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
  UNIQUE KEY `week` (`PRJ`,`weekStart`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system','custom','','hourPoint','1');

ALTER TABLE `zt_block` ADD `type` char(30) NOT NULL AFTER `module`;
ALTER TABLE `zt_block` ADD UNIQUE `account_module_type_order` (`account`, `module`, `type`, `order`), DROP INDEX `accountModuleOrder`;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('1', '0', '*', '*', '*', 'moduleName=weekly&methodName=computeWeekly', '更新项目周报', 'system', 0, 'normal', '2020-08-27 10:07:53');

ALTER TABLE `zt_story` ADD `URChanged` enum('0','1') NOT NULL DEFAULT '0' AFTER `version`; 

ALTER TABLE `zt_team` MODIFY `type` enum('project','task','stage','sprint') NOT NULL DEFAULT 'project' AFTER `root`;

CREATE TABLE IF NOT EXISTS `zt_planstory` (
  `plan` mediumint(8) unsigned NOT NULL,
  `story` mediumint(8) unsigned NOT NULL,
  `order` mediumint(9) NOT NULL,
  UNIQUE KEY `unique` (`plan`,`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_acl` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `objectType` char(30) NOT NULL,
  `objectID` mediumint(9) NOT NULL DEFAULT '0',
  `type` char(40) NOT NULL DEFAULT 'whitelist',
  `source` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
