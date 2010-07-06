-- DROP TABLE IF EXISTS `zt_action`;
CREATE TABLE IF NOT EXISTS `zt_action` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `objectType` varchar(30) NOT NULL default '',
  `objectID` mediumint(8) unsigned NOT NULL default '0',
  `actor` varchar(30) NOT NULL default '',
  `action` varchar(30) NOT NULL default '',
  `date` datetime NOT NULL,
  `comment` text NOT NULL,
  `extra` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_bug`;
CREATE TABLE IF NOT EXISTS `zt_bug` (
  `id` mediumint(8) NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `module` mediumint(8) unsigned NOT NULL default '0',
  `project` mediumint(8) unsigned NOT NULL default '0',
  `story` mediumint(8) unsigned NOT NULL default '0',
  `storyVersion` smallint(6) NOT NULL default '1',
  `task` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(150) NOT NULL default '',
  `keywords` varchar(255) NOT NULL,
  `severity` tinyint(4) NOT NULL default '0',
  `pri` tinyint(3) unsigned NOT NULL,
  `type` varchar(30) NOT NULL default '',
  `os` varchar(30) NOT NULL default '',
  `browser` varchar(30) NOT NULL default '',
  `hardware` varchar(30) NOT NULL,
  `found` varchar(30) NOT NULL default '',
  `steps` text NOT NULL,
  `status` enum('active','resolved','closed') NOT NULL default 'active',
  `mailto` varchar(255) NOT NULL default '',
  `openedBy` varchar(30) NOT NULL default '',
  `openedDate` datetime NOT NULL,
  `openedBuild` varchar(255) NOT NULL,
  `assignedTo` varchar(30) NOT NULL default '',
  `assignedDate` datetime NOT NULL,
  `resolvedBy` varchar(30) NOT NULL default '',
  `resolution` varchar(30) NOT NULL default '',
  `resolvedBuild` varchar(30) NOT NULL default '',
  `resolvedDate` datetime NOT NULL,
  `closedBy` varchar(30) NOT NULL default '',
  `closedDate` datetime NOT NULL,
  `duplicateBug` mediumint(8) unsigned NOT NULL,
  `linkBug` varchar(255) NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `caseVersion` smallint(6) NOT NULL default '1',
  `result` mediumint(8) unsigned NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL default '',
  `lastEditedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_build`;
CREATE TABLE IF NOT EXISTS `zt_build` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `project` mediumint(8) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `scmPath` char(255) NOT NULL,
  `filePath` char(255) NOT NULL,
  `date` date NOT NULL,
  `builder` char(30) NOT NULL default '',
  `desc` char(255) NOT NULL default '',
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_burn`;
CREATE TABLE IF NOT EXISTS `zt_burn` (
  `company` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `date` date NOT NULL,
  `left` float NOT NULL,
  `consumed` float NOT NULL,
  PRIMARY KEY  (`project`,`date`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_case`;
CREATE TABLE IF NOT EXISTS `zt_case` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `module` mediumint(8) unsigned NOT NULL default '0',
  `path` mediumint(8) unsigned NOT NULL default '0',
  `story` mediumint(30) unsigned NOT NULL default '0',
  `storyVersion` smallint(6) NOT NULL default '1',
  `title` char(90) NOT NULL,
  `keywords` varchar(255) NOT NULL,
  `pri` tinyint(3) unsigned NOT NULL default '0',
  `type` char(30) NOT NULL default '1',
  `stage` varchar(255) NOT NULL,
  `howRun` varchar(30) NOT NULL,
  `scriptedBy` varchar(30) NOT NULL,
  `scriptedDate` date NOT NULL,
  `scriptStatus` varchar(30) NOT NULL,
  `scriptLocation` varchar(255) NOT NULL,
  `status` char(30) NOT NULL default '1',
  `frequency` enum('1','2','3') NOT NULL default '1',
  `order` tinyint(30) unsigned NOT NULL default '0',
  `openedBy` char(30) NOT NULL default '',
  `openedDate` datetime NOT NULL,
  `lastEditedBy` char(30) NOT NULL default '',
  `lastEditedDate` datetime NOT NULL,
  `version` tinyint(3) unsigned NOT NULL default '0',
  `linkCase` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_caseStep`;
CREATE TABLE IF NOT EXISTS `zt_caseStep` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL default '0',
  `version` smallint(3) unsigned NOT NULL default '0',
  `desc` text NOT NULL,
  `expect` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `case` (`case`,`version`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_company`;
CREATE TABLE IF NOT EXISTS `zt_company` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` char(120) default NULL,
  `phone` char(20) default NULL,
  `fax` char(20) default NULL,
  `address` char(120) default NULL,
  `zipcode` char(10) default NULL,
  `website` char(120) default NULL,
  `backyard` char(120) default NULL,
  `pms` char(120) default NULL,
  `guest` enum('1','0') NOT NULL default '0',
  `admins` char(255) default NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `pms` (`pms`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_config`;
CREATE TABLE IF NOT EXISTS `zt_config` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `owner` char(30) NOT NULL default '',
  `section` char(30) NOT NULL default '',
  `key` char(30) NOT NULL default '',
  `value` char(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_dept`;
CREATE TABLE IF NOT EXISTS `zt_dept` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `parent` mediumint(8) unsigned NOT NULL default '0',
  `path` char(255) NOT NULL default '',
  `grade` tinyint(3) unsigned NOT NULL default '0',
  `order` tinyint(3) unsigned NOT NULL default '0',
  `position` char(30) NOT NULL default '',
  `function` char(255) NOT NULL default '',
  `manager` char(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_effort`;
CREATE TABLE IF NOT EXISTS `zt_effort` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `user` char(30) NOT NULL default '',
  `todo` enum('1','0') NOT NULL default '1',
  `date` date NOT NULL default '0000-00-00',
  `begin` datetime NOT NULL default '0000-00-00 00:00:00',
  `end` datetime NOT NULL default '0000-00-00 00:00:00',
  `type` enum('1','2','3') NOT NULL default '1',
  `idvalue` mediumint(8) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `desc` char(255) NOT NULL default '',
  `status` enum('1','2','3') NOT NULL default '1',
  PRIMARY KEY  (`id`),
  KEY `user` (`user`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_file`;
CREATE TABLE IF NOT EXISTS `zt_file` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `pathname` char(50) NOT NULL,
  `title` char(90) NOT NULL,
  `extension` char(30) NOT NULL,
  `size` mediumint(8) unsigned NOT NULL default '0',
  `objectType` char(30) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `addedBy` char(30) NOT NULL default '',
  `addedDate` datetime NOT NULL,
  `downloads` mediumint(8) unsigned NOT NULL default '0',
  `extra` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_group`;
CREATE TABLE IF NOT EXISTS `zt_group` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `name` char(30) NOT NULL,
  `desc` char(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_groupPriv`;
CREATE TABLE IF NOT EXISTS `zt_groupPriv` (
  `company` mediumint(9) NOT NULL,
  `group` mediumint(8) unsigned NOT NULL default '0',
  `module` char(30) NOT NULL default '',
  `method` char(30) NOT NULL default '',
  UNIQUE KEY `group` (`group`,`module`,`method`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_history`;
CREATE TABLE IF NOT EXISTS `zt_history` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `action` mediumint(8) unsigned NOT NULL default '0',
  `field` varchar(30) NOT NULL default '',
  `old` text NOT NULL,
  `new` text NOT NULL,
  `diff` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_module`;
CREATE TABLE IF NOT EXISTS `zt_module` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `parent` mediumint(8) unsigned NOT NULL default '0',
  `path` char(255) NOT NULL default '',
  `grade` tinyint(3) unsigned NOT NULL default '0',
  `order` tinyint(3) unsigned NOT NULL default '0',
  `view` char(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_product`;
CREATE TABLE IF NOT EXISTS `zt_product` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `order` tinyint(3) unsigned NOT NULL default '0',
  `status` varchar(30) NOT NULL default '',
  `desc` text NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_productPlan`;
CREATE TABLE IF NOT EXISTS `zt_productPlan` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `title` varchar(90) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_project`;
CREATE TABLE IF NOT EXISTS `zt_project` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `isCat` enum('1','0') NOT NULL default '0',
  `catID` mediumint(8) unsigned NOT NULL,
  `type` enum('sprint','project') NOT NULL default 'sprint',
  `parent` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `status` varchar(10) NOT NULL,
  `statge` enum('1','2','3','4','5') NOT NULL default '1',
  `pri` enum('1','2','3','4') NOT NULL default '1',
  `desc` text NOT NULL,
  `goal` text NOT NULL,
  `openedBy` varchar(30) NOT NULL default '',
  `openedDate` int(10) unsigned NOT NULL default '0',
  `closedBy` varchar(30) NOT NULL default '',
  `closedDate` int(10) unsigned NOT NULL default '0',
  `canceledBy` varchar(30) NOT NULL default '',
  `canceledDate` int(10) unsigned NOT NULL default '0',
  `PO` varchar(30) NOT NULL default '',
  `PM` varchar(30) NOT NULL default '',
  `QM` varchar(30) NOT NULL default '',
  `team` varchar(30) NOT NULL,
  `acl` enum('open','private','custom') NOT NULL default 'open',
  `whitelist` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `company` (`company`,`type`,`parent`,`begin`,`end`,`status`,`statge`,`pri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_projectProduct`;
CREATE TABLE IF NOT EXISTS `zt_projectProduct` (
  `company` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`project`,`product`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_projectStory`;
CREATE TABLE IF NOT EXISTS `zt_projectStory` (
  `company` mediumint(9) NOT NULL,
  `project` mediumint(8) unsigned NOT NULL default '0',
  `product` mediumint(8) unsigned NOT NULL,
  `story` mediumint(8) unsigned NOT NULL default '0',
  `version` smallint(6) NOT NULL default '1',
  UNIQUE KEY `project` (`project`,`story`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_release`;
CREATE TABLE IF NOT EXISTS `zt_release` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `build` mediumint(8) unsigned NOT NULL,
  `name` char(30) NOT NULL default '',
  `date` date NOT NULL,
  `desc` text NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_story`;
CREATE TABLE IF NOT EXISTS `zt_story` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `module` mediumint(8) unsigned NOT NULL default '0',
  `plan` mediumint(8) unsigned NOT NULL default '0',
  `fromBug` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(90) NOT NULL default '',
  `keywords` varchar(255) NOT NULL,
  `type` varchar(30) NOT NULL default '',
  `pri` tinyint(3) unsigned NOT NULL default '3',
  `estimate` float unsigned NOT NULL,
  `status` varchar(30) NOT NULL default '',
  `stage` varchar(30) NOT NULL,
  `mailto` varchar(255) NOT NULL default '',
  `openedBy` varchar(30) NOT NULL default '',
  `openedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL default '',
  `assignedDate` datetime NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL default '',
  `lastEditedDate` datetime NOT NULL,
  `reviewedBy` varchar(30) NOT NULL,
  `reviewedDate` date NOT NULL,
  `closedBy` varchar(30) NOT NULL default '',
  `closedDate` datetime NOT NULL,
  `closedReason` varchar(30) NOT NULL,
  `toBug` mediumint(9) NOT NULL,
  `childStories` varchar(255) NOT NULL,
  `linkStories` varchar(255) NOT NULL,
  `duplicateStory` mediumint(8) unsigned NOT NULL,
  `version` smallint(6) NOT NULL default '1',
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `product` (`product`,`module`,`plan`,`type`,`pri`),
  KEY `status` (`status`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_storySpec`;
CREATE TABLE IF NOT EXISTS `zt_storySpec` (
  `company` mediumint(8) unsigned NOT NULL,
  `story` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `title` varchar(90) NOT NULL,
  `spec` text NOT NULL,
  UNIQUE KEY `story` (`story`,`version`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_task`;
CREATE TABLE IF NOT EXISTS `zt_task` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL default '0',
  `story` mediumint(8) unsigned NOT NULL default '0',
  `storyVersion` smallint(6) NOT NULL default '1',
  `name` varchar(90) NOT NULL,
  `type` varchar(20) NOT NULL,
  `pri` tinyint(3) unsigned NOT NULL default '0',
  `owner` char(30) NOT NULL default '',
  `estimate` float unsigned NOT NULL,
  `consumed` float unsigned NOT NULL,
  `left` float unsigned NOT NULL,
  `deadline` date NOT NULL,
  `status` enum('wait','doing','done','cancel') NOT NULL default 'wait',
  `statusCustom` tinyint(3) unsigned NOT NULL,
  `mailto` varchar(255) NOT NULL default '',
  `desc` text NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `statusOrder` (`statusCustom`),
  KEY `type` (`type`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_taskEstimate`;
CREATE TABLE IF NOT EXISTS `zt_taskEstimate` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `task` mediumint(8) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `estimate` tinyint(3) unsigned NOT NULL default '0',
  `estimater` char(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `task` (`task`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_team`;
CREATE TABLE IF NOT EXISTS `zt_team` (
  `company` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL default '0',
  `account` char(30) NOT NULL default '',
  `role` char(30) NOT NULL default '',
  `joinDate` date NOT NULL default '0000-00-00',
  `workingHour` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`project`,`account`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_testResult`;
CREATE TABLE IF NOT EXISTS `zt_testResult` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `run` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  `caseResult` char(30) NOT NULL,
  `stepResults` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `run` (`run`),
  KEY `case` (`case`,`version`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_testRun`;
CREATE TABLE IF NOT EXISTS `zt_testRun` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `task` mediumint(8) unsigned NOT NULL default '0',
  `case` mediumint(8) unsigned NOT NULL default '0',
  `version` tinyint(3) unsigned NOT NULL default '0',
  `assignedTo` char(30) NOT NULL default '',
  `lastRun` datetime NOT NULL,
  `lastResult` char(30) NOT NULL,
  `status` char(30) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `task` (`task`,`case`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_testTask`;
CREATE TABLE IF NOT EXISTS `zt_testTask` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL,
  `name` char(90) NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL default '0',
  `build` char(30) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `desc` text NOT NULL,
  `status` char(30) NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_todo`;
CREATE TABLE IF NOT EXISTS `zt_todo` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(9) NOT NULL,
  `account` char(30) NOT NULL,
  `date` date NOT NULL default '0000-00-00',
  `begin` smallint(4) unsigned zerofill NOT NULL,
  `end` smallint(4) unsigned zerofill NOT NULL,
  `type` char(10) NOT NULL,
  `idvalue` mediumint(8) unsigned NOT NULL default '0',
  `pri` tinyint(3) unsigned NOT NULL,
  `name` char(150) NOT NULL,
  `desc` text NOT NULL,
  `status` char(20) NOT NULL default '',
  `private` tinyint(1) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `user` (`account`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_user`;
CREATE TABLE IF NOT EXISTS `zt_user` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `dept` mediumint(8) unsigned NOT NULL default '0',
  `account` char(30) NOT NULL default '',
  `password` char(32) NOT NULL default '',
  `realname` char(30) NOT NULL default '',
  `nickname` char(60) NOT NULL default '',
  `avatar` char(30) NOT NULL default '',
  `birthyear` smallint(5) unsigned NOT NULL default '0',
  `birthday` date NOT NULL default '0000-00-00',
  `gendar` enum('f','m') NOT NULL default 'f',
  `email` char(90) NOT NULL default '',
  `msn` char(90) NOT NULL default '',
  `qq` char(20) NOT NULL default '',
  `yahoo` char(90) NOT NULL default '',
  `gtalk` char(90) NOT NULL default '',
  `wangwang` char(90) NOT NULL default '',
  `mobile` char(11) NOT NULL default '',
  `phone` char(20) NOT NULL default '',
  `address` char(120) NOT NULL default '',
  `zipcode` char(10) NOT NULL default '',
  `join` date NOT NULL default '0000-00-00',
  `visits` mediumint(8) unsigned NOT NULL default '0',
  `ip` char(15) NOT NULL default '',
  `last` int(10) unsigned NOT NULL default '0',
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `account` (`account`),
  KEY `company` (`company`,`dept`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_userGroup`;
CREATE TABLE IF NOT EXISTS `zt_userGroup` (
  `company` mediumint(8) unsigned NOT NULL,
  `account` char(30) NOT NULL default '',
  `group` mediumint(8) unsigned NOT NULL default '0',
  UNIQUE KEY `account` (`account`,`group`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_userQuery`;
CREATE TABLE IF NOT EXISTS `zt_userQuery` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `account` char(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `title` varchar(90) NOT NULL,
  `form` text NOT NULL,
  `sql` text NOT NULL,
  `mode` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `company` (`company`),
  KEY `account` (`account`),
  KEY `module` (`module`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
