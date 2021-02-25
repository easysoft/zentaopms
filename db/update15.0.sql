ALTER TABLE `zt_project` DROP `isCat`, DROP `catID`;
ALTER TABLE `zt_project` ADD `project` mediumint(8) NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `zt_project` ADD `model` char(30) NOT NULL AFTER `project`;
ALTER TABLE `zt_project` CHANGE `type` `type` char(30) NOT NULL DEFAULT 'sprint' AFTER `model`;
ALTER TABLE `zt_project` CHANGE `acl` `acl` char(30) NOT NULL DEFAULT 'open';
ALTER TABLE `zt_project` ADD `product` varchar(20) NOT NULL DEFAULT 'single' AFTER `type`;
ALTER TABLE `zt_project` ADD `lifetime` char(30) NOT NULL AFTER `product`;
ALTER TABLE `zt_project` ADD `budget` varchar(30) NOT NULL DEFAULT '0' AFTER `lifetime`;
ALTER TABLE `zt_project` ADD `budgetUnit` char(30) NOT NULL DEFAULT 'CNY' AFTER `budget`;
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
ALTER TABLE `zt_repo` ADD `product` varchar(255) NOT NULL AFTER `id`;
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
ALTER TABLE `zt_user` CHANGE `avatar` `avatar` text NOT NULL AFTER `commiter`;

REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'custom', '', 'URSR', '3');
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'program', '', 'unitList', 'CNY,USD');
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'program', '', 'defaultCurrency', 'CNY');

ALTER TABLE `zt_config` MODIFY COLUMN `value` longtext NOT NULL AFTER `key`;

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

-- DROP TABLE IF EXISTS `zt_design`;
CREATE TABLE IF NOT EXISTS `zt_design` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `PRJ` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `commit` text NOT NULL,
  `commitedBy` varchar(30) NOT NULL,
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
  `closedBy` varchar(30) NOT NULL,
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
  `closedBy` char(30) NOT NULL,
  `closedDate` date NOT NULL,
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
  `scale` decimal(10, 2) unsigned NOT NULL,
  `productivity` decimal(10, 2) unsigned NOT NULL,
  `duration` decimal(10, 2) unsigned NOT NULL,
  `unitLaborCost` decimal(10, 2) unsigned NOT NULL,
  `totalLaborCost` decimal(10, 2) unsigned NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `dayHour` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`)
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

-- DROP TABLE IF EXISTS `zt_searchindex`;
CREATE TABLE IF NOT EXISTS `zt_searchindex` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` char(20) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `addedDate` datetime NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`objectType`,`objectID`),
  KEY `addedDate` (`addedDate`),
  FULLTEXT KEY `content` (`content`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_searchdict`;
CREATE TABLE IF NOT EXISTS `zt_searchdict` (
  `key` smallint(5) unsigned NOT NULL,
  `value` char(3) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) VALUES 
(1,  'product',      'dashboard'),
(1,  'product',      'manageLine'),
(1,  'projectstory', 'story'),
(1,  'projectstory', 'track'),
(1,  'projectstory', 'view'),
(1,  'projectstory', 'linkStory'),
(1,  'projectstory', 'unLinkStory'),
(1,  'my',           'preference'),
(1,  'my',           'uploadAvatar'),
(1,  'user',         'execution'),
(1,  'user',         'issue'),
(1,  'user',         'risk'),
(1,  'user',         'cropAvatar'),
(1,  'program',      'PRJView'),
(1,  'search',       'index'),
(1,  'search',       'buildIndex'),
(2,  'product',      'dashboard'),
(2,  'product',      'manageLine'),
(2,  'projectstory', 'story'),
(2,  'projectstory', 'track'),
(2,  'projectstory', 'view'),
(2,  'my',           'preference'),
(2,  'my',           'uploadAvatar'),
(2,  'user',         'execution'),
(2,  'user',         'issue'),
(2,  'user',         'risk'),
(2,  'user',         'cropAvatar'),
(2,  'search',       'index'),
(3,  'product',      'dashboard'),
(3,  'product',      'manageLine'),
(3,  'projectstory', 'story'),
(3,  'projectstory', 'track'),
(3,  'projectstory', 'view'),
(3,  'my',           'preference'),
(3,  'my',           'uploadAvatar'),
(3,  'user',         'execution'),
(3,  'user',         'issue'),
(3,  'user',         'risk'),
(3,  'user',         'cropAvatar'),
(3,  'search',       'index'),
(4,  'product',      'dashboard'),
(4,  'product',      'manageLine'),
(4,  'projectstory', 'story'),
(4,  'projectstory', 'track'),
(4,  'projectstory', 'view'),
(4,  'my',           'preference'),
(4,  'my',           'uploadAvatar'),
(4,  'user',         'execution'),
(4,  'user',         'issue'),
(4,  'user',         'risk'),
(4,  'user',         'cropAvatar'),
(4,  'program',      'PRJView'),
(4,  'search',       'index'),
(5,  'product',      'dashboard'),
(5,  'product',      'manageLine'),
(5,  'projectstory', 'story'),
(5,  'projectstory', 'track'),
(5,  'projectstory', 'view'),
(5,  'projectstory', 'linkStory'),
(5,  'projectstory', 'unLinkStory'),
(5,  'my',           'preference'),
(5,  'my',           'uploadAvatar'),
(5,  'user',         'execution'),
(5,  'user',         'issue'),
(5,  'user',         'risk'),
(5,  'user',         'cropAvatar'),
(5,  'program',      'PRJView'),
(5,  'search',       'index'),
(6,  'product',      'dashboard'),
(6,  'product',      'manageLine'),
(6,  'projectstory', 'story'),
(6,  'projectstory', 'track'),
(6,  'projectstory', 'view'),
(6,  'my',           'preference'),
(6,  'my',           'uploadAvatar'),
(6,  'user',         'execution'),
(6,  'user',         'issue'),
(6,  'user',         'risk'),
(6,  'user',         'cropAvatar'),
(6,  'program',      'PRJView'),
(6,  'search',       'index'),
(7,  'product',      'dashboard'),
(7,  'product',      'manageLine'),
(7,  'projectstory', 'story'),
(7,  'projectstory', 'track'),
(7,  'projectstory', 'view'),
(7,  'projectstory', 'linkStory'),
(7,  'projectstory', 'unLinkStory'),
(7,  'my',           'preference'),
(7,  'my',           'uploadAvatar'),
(7,  'user',         'execution'),
(7,  'user',         'issue'),
(7,  'user',         'risk'),
(7,  'user',         'cropAvatar'),
(7,  'program',      'PRJView'),
(7,  'search',       'index'),
(8,  'product',      'dashboard'),
(8,  'product',      'manageLine'),
(8,  'projectstory', 'story'),
(8,  'projectstory', 'track'),
(8,  'projectstory', 'view'),
(8,  'my',           'preference'),
(8,  'my',           'uploadAvatar'),
(8,  'user',         'execution'),
(8,  'user',         'issue'),
(8,  'user',         'risk'),
(8,  'user',         'cropAvatar'),
(8,  'program',      'PRJView'),
(8,  'search',       'index'),
(9,  'product',      'dashboard'),
(9,  'product',      'manageLine'),
(9,  'projectstory', 'story'),
(9,  'projectstory', 'track'),
(9,  'projectstory', 'view'),
(9,  'projectstory', 'linkStory'),
(9,  'projectstory', 'unLinkStory'),
(9,  'my',           'preference'),
(9,  'my',           'uploadAvatar'),
(9,  'user',         'execution'),
(9,  'user',         'issue'),
(9,  'user',         'risk'),
(9,  'user',         'cropAvatar'),
(9,  'program',      'PRJView'),
(9,  'search',       'index'),
(10, 'product',      'dashboard'),
(10, 'product',      'manageLine'),
(10, 'projectstory', 'story'),
(10, 'projectstory', 'track'),
(10, 'projectstory', 'view'),
(10, 'my',           'preference'),
(10, 'my',           'uploadAvatar'),
(10, 'user',         'execution'),
(10, 'user',         'issue'),
(10, 'user',         'risk'),
(10, 'user',         'cropAvatar'),
(10,  'search',       'index'),
(11, 'product',      'dashboard'),
(11, 'product',      'manageLine'),
(11, 'projectstory', 'story'),
(11, 'projectstory', 'track'),
(11, 'projectstory', 'view'),
(11, 'my',           'preference'),
(11, 'my',           'uploadAvatar'),
(11, 'user',         'execution'),
(11, 'user',         'issue'),
(11, 'user',         'risk'),
(11, 'user',         'cropAvatar');

INSERT INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`) VALUES
('zh-cn', 'custom', 'URSRList', '1', '{\"SRName\":\"\\u7814\\u53d1\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '1'),('zh-cn', 'custom', 'URSRList', '2', '{\"SRName\":\"\\u8f6f\\u9700\",\"URName\":\"\\u7528\\u9700\"}', '1'),
('zh-cn', 'custom', 'URSRList', '3', '{\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u9700\\u6c42\"}', '1'),
('zh-cn', 'custom', 'URSRList', '4', '{\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u53f2\\u8bd7\"}', '1');
