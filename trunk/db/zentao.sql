-- DROP TABLE IF EXISTS `zt_action`;
CREATE TABLE IF NOT EXISTS `zt_action` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `objectType` varchar(30) NOT NULL default '',
  `objectID` mediumint(8) unsigned NOT NULL default '0',
  `actor` varchar(30) NOT NULL default '',
  `action` varchar(30) NOT NULL default '',
  `date` int(10) unsigned NOT NULL default '0',
  `comment` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_bug`;
CREATE TABLE IF NOT EXISTS `zt_bug` (
  `id` mediumint(8) NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `module` mediumint(8) unsigned NOT NULL default '0',
  `project` mediumint(8) unsigned NOT NULL default '0',
  `story` mediumint(8) unsigned NOT NULL default '0',
  `task` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(150) NOT NULL default '',
  `severity` tinyint(4) NOT NULL default '0',
  `type` varchar(30) NOT NULL default '',
  `os` varchar(30) NOT NULL default '',
  `browser` varchar(30) NOT NULL default '',
  `hardware` varchar(30) NOT NULL default '',
  `found` varchar(30) NOT NULL default '',
  `steps` text NOT NULL,
  `status` enum('active','resolved','closed') NOT NULL default 'active',
  `mailto` varchar(255) NOT NULL default '',
  `openedBy` varchar(30) NOT NULL default '',
  `openedDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `openedBuild` varchar(30) NOT NULL default '',
  `assignedTo` varchar(30) NOT NULL default '',
  `assignedDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `resolvedBy` varchar(30) NOT NULL default '',
  `resolution` varchar(30) NOT NULL default '',
  `resolvedBuild` varchar(30) NOT NULL default '',
  `resolvedDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `closedBy` varchar(30) NOT NULL default '',
  `closedDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `duplicateBug` mediumint(8) unsigned NOT NULL default '0',
  `linkBug` varchar(255) NOT NULL default '',
  `case` mediumint(8) unsigned NOT NULL default '0',
  `result` mediumint(8) unsigned NOT NULL default '0',
  `lastEditedBy` varchar(30) NOT NULL default '',
  `lastEditedDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `field1` varchar(255) NOT NULL default '',
  `field2` varchar(255) NOT NULL default '',
  `feild3` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_build`;
CREATE TABLE IF NOT EXISTS `zt_build` (
  `id` mediumint(8) unsigned NOT NULL default '0',
  `product` mediumint(8) unsigned NOT NULL default '0',
  `sprintprj` mediumint(8) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `scmPath` char(255) NOT NULL default '',
  `buildDate` int(10) unsigned NOT NULL default '0',
  `builder` char(30) NOT NULL default '',
  `tasks` char(255) NOT NULL default '',
  `desc` char(255) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_burn`;
CREATE TABLE IF NOT EXISTS `zt_burn` (
  `project` mediumint(8) unsigned NOT NULL default '0',
  `date` date NOT NULL default '0000-00-00',
  `left` float NOT NULL default '0',
  `consumed` float NOT NULL default '0',
  PRIMARY KEY  (`project`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_case`;
CREATE TABLE IF NOT EXISTS `zt_case` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `module` mediumint(8) unsigned NOT NULL default '0',
  `path` mediumint(8) unsigned NOT NULL default '0',
  `story` mediumint(30) unsigned NOT NULL default '0',
  `title` varchar(30) NOT NULL default '',
  `pri` tinyint(3) unsigned NOT NULL default '0',
  `type` varchar(30) NOT NULL default '1',
  `status` varchar(30) NOT NULL default '1',
  `steps` text NOT NULL,
  `frequency` enum('1','2','3') NOT NULL default '1',
  `order` tinyint(30) unsigned NOT NULL default '0',
  `openedBy` varchar(30) NOT NULL default '',
  `openedDate` datetime NOT NULL,
  `lastEditedBy` varchar(30) NOT NULL default '',
  `lastEditedDate` datetime NOT NULL,
  `field1` varchar(30) NOT NULL default '',
  `field2` varchar(30) NOT NULL default '',
  `feidl3` varchar(30) NOT NULL default '',
  `version` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_caseResult`;
CREATE TABLE IF NOT EXISTS `zt_caseResult` (
  `id` mediumint(8) unsigned NOT NULL default '0',
  `plan` mediumint(30) unsigned NOT NULL default '0',
  `build` mediumint(30) unsigned NOT NULL default '0',
  `case` mediumint(30) unsigned NOT NULL default '0',
  `result` enum('pass','fail','skip') NOT NULL default 'pass',
  `status` enum('finished','blocked') NOT NULL default 'finished',
  `executedBy` char(30) NOT NULL default '',
  `executedDate` int(30) unsigned NOT NULL default '0',
  `os` char(30) NOT NULL default '',
  `browser` char(30) NOT NULL default '',
  `hardware` char(30) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_caseStep`;
CREATE TABLE IF NOT EXISTS `zt_caseStep` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `case` mediumint(8) unsigned NOT NULL default '0',
  `caseVersion` tinyint(3) unsigned NOT NULL default '0',
  `step` char(255) NOT NULL default '',
  `expect` char(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_company`;
CREATE TABLE IF NOT EXISTS `zt_company` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` char(120) NOT NULL default '',
  `phone` char(20) NOT NULL default '',
  `fax` char(20) NOT NULL default '',
  `address` char(120) NOT NULL default '',
  `zipcode` char(10) NOT NULL default '',
  `website` char(120) NOT NULL default '',
  `backyard` char(120) NOT NULL default '',
  `pms` char(120) NOT NULL default '',
  `guest` enum('1','0') NOT NULL default '0',
  `admins` char(255) NOT NULL default '',
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
  KEY `user` (`user`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_file`;
CREATE TABLE IF NOT EXISTS `zt_file` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `pathname` char(50) NOT NULL,
  `title` char(90) NOT NULL,
  `extension` char(30) NOT NULL,
  `size` mediumint(8) unsigned NOT NULL default '0',
  `objectType` char(10) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `addedBy` char(30) NOT NULL default '',
  `addedDate` datetime NOT NULL,
  `downloads` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_group`;
CREATE TABLE IF NOT EXISTS `zt_group` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `desc` char(255) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_groupPriv`;
CREATE TABLE IF NOT EXISTS `zt_groupPriv` (
  `group` mediumint(8) unsigned NOT NULL default '0',
  `module` char(30) NOT NULL default '',
  `method` char(30) NOT NULL default '',
  UNIQUE KEY `group` (`group`,`module`,`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_history`;
CREATE TABLE IF NOT EXISTS `zt_history` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `action` mediumint(8) unsigned NOT NULL default '0',
  `field` varchar(30) NOT NULL default '',
  `old` text NOT NULL,
  `new` text NOT NULL,
  `diff` text NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_module`;
CREATE TABLE IF NOT EXISTS `zt_module` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `parent` mediumint(8) unsigned NOT NULL default '0',
  `path` char(255) NOT NULL default '',
  `grade` tinyint(3) unsigned NOT NULL default '0',
  `order` tinyint(3) unsigned NOT NULL default '0',
  `view` char(30) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_planCase`;
CREATE TABLE IF NOT EXISTS `zt_planCase` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `plan` mediumint(8) unsigned NOT NULL default '0',
  `case` mediumint(8) unsigned NOT NULL default '0',
  `caseVersion` tinyint(3) unsigned NOT NULL default '0',
  `assignedTo` char(30) NOT NULL default '',
  `assignedDate` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_product`;
CREATE TABLE IF NOT EXISTS `zt_product` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `code` varchar(10) NOT NULL default '',
  `order` tinyint(3) unsigned NOT NULL default '0',
  `status` varchar(30) NOT NULL default '',
  `desc` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_productPlan`;
CREATE TABLE IF NOT EXISTS `zt_productPlan` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL,
  `title` varchar(90) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
-- DROP TABLE IF EXISTS `zt_project`;
CREATE TABLE IF NOT EXISTS `zt_project` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `isCat` enum('1','0') NOT NULL default '0',
  `catID` mediumint(8) unsigned NOT NULL default '0',
  `type` enum('sprint','project') NOT NULL default 'sprint',
  `parent` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `code` varchar(20) NOT NULL default '',
  `begin` date NOT NULL default '0000-00-00',
  `end` date NOT NULL default '0000-00-00',
  `status` varchar(10) NOT NULL default '',
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
  `team` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `company` (`company`,`type`,`parent`,`begin`,`end`,`status`,`statge`,`pri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_projectProduct`;
CREATE TABLE IF NOT EXISTS `zt_projectProduct` (
  `project` mediumint(8) unsigned NOT NULL default '0',
  `product` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`project`,`product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_projectStory`;
CREATE TABLE IF NOT EXISTS `zt_projectStory` (
  `project` mediumint(8) unsigned NOT NULL default '0',
  `product` mediumint(8) unsigned NOT NULL default '0',
  `story` mediumint(8) unsigned NOT NULL default '0',
  UNIQUE KEY `project` (`project`,`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_release`;
CREATE TABLE IF NOT EXISTS `zt_release` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(30) NOT NULL default '',
  `desc` text NOT NULL,
  `status` varchar(30) NOT NULL default '',
  `planDate` date NOT NULL default '0000-00-00',
  `releaseDate` datetime NOT NULL default '0000-00-00 00:00:00',
  PRIMARY KEY  (`id`),
  KEY `product` (`product`,`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_releation`;
CREATE TABLE IF NOT EXISTS `zt_releation` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `type` char(30) NOT NULL default '',
  `id1` mediumint(8) unsigned NOT NULL default '0',
  `id2` mediumint(8) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_resultStep`;
CREATE TABLE IF NOT EXISTS `zt_resultStep` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `result` mediumint(8) unsigned NOT NULL default '0',
  `step` mediumint(8) unsigned NOT NULL default '0',
  `stepResult` enum('pass','fail','block','n/a') NOT NULL default 'pass',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_story`;
CREATE TABLE IF NOT EXISTS `zt_story` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `module` mediumint(8) unsigned NOT NULL default '0',
  `plan` mediumint(8) unsigned NOT NULL default '0',
  `bug` mediumint(8) unsigned NOT NULL default '0',
  `title` varchar(90) NOT NULL default '',
  `spec` text NOT NULL,
  `type` varchar(30) NOT NULL default '',
  `pri` tinyint(3) unsigned NOT NULL default '3',
  `estimate` tinyint(3) unsigned NOT NULL default '0',
  `status` varchar(30) NOT NULL default '',
  `mailto` varchar(255) NOT NULL default '',
  `openedBy` varchar(30) NOT NULL default '',
  `openedDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `assignedTo` varchar(30) NOT NULL default '',
  `assignedDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `lastEditedBy` varchar(30) NOT NULL default '',
  `lastEditedDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `closedBy` varchar(30) NOT NULL default '',
  `closedDate` datetime NOT NULL default '0000-00-00 00:00:00',
  `version` float(4,1) NOT NULL default '0.0',
  `attatchment` varchar(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `product` (`product`,`module`,`plan`,`type`,`pri`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_task`;
CREATE TABLE IF NOT EXISTS `zt_task` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `project` mediumint(8) unsigned NOT NULL default '0',
  `story` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(90) NOT NULL default '',
  `type` VARCHAR( 20 ) NOT NULL, 
  `pri` tinyint(3) unsigned NOT NULL default '0',
  `owner` varchar(30) NOT NULL default '',
  `estimate` float unsigned NOT NULL default '0',
  `consumed` float unsigned NOT NULL default '0',
  `left` float unsigned NOT NULL default '0',
  `status` enum('wait','doing','done','cancel') NOT NULL default 'wait',
  `statusCustom` tinyint(3) unsigned NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `statusCustom` (`statusCustom`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_taskEstimate`;
CREATE TABLE IF NOT EXISTS `zt_taskEstimate` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `task` mediumint(8) unsigned NOT NULL default '0',
  `date` int(10) unsigned NOT NULL default '0',
  `estimate` tinyint(3) unsigned NOT NULL default '0',
  `estimater` char(30) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `task` (`task`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_team`;
CREATE TABLE IF NOT EXISTS `zt_team` (
  `project` mediumint(8) unsigned NOT NULL default '0',
  `account` char(30) NOT NULL default '',
  `role` char(30) NOT NULL default '',
  `joinDate` date NOT NULL default '0000-00-00',
  `workingHour` tinyint(3) unsigned NOT NULL default '0',
  PRIMARY KEY  (`project`,`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_testPlan`;
CREATE TABLE IF NOT EXISTS `zt_testPlan` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` char(30) NOT NULL default '',
  `sprintprj` mediumint(8) unsigned NOT NULL default '0',
  `planBegin` int(10) unsigned NOT NULL default '0',
  `planEnd` int(10) unsigned NOT NULL default '0',
  `realBegin` int(10) unsigned NOT NULL default '0',
  `realEnd` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_todo`;
CREATE TABLE IF NOT EXISTS `zt_todo` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `account` char(30) NOT NULL default '',
  `date` date NOT NULL default '0000-00-00',
  `begin` smallint(4) unsigned zerofill NOT NULL default '0000',
  `end` smallint(4) unsigned zerofill NOT NULL default '0000',
  `type` char(10) NOT NULL default '',
  `idvalue` mediumint(8) unsigned NOT NULL default '0',
  `pri` tinyint(3) unsigned NOT NULL default '0',
  `name` char(150) NOT NULL,
  `desc` char(255) NOT NULL default '',
  `status` enum('wait','doing','done') NOT NULL default 'wait',
  `private` tinyint(1) NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `user` (`account`)
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
  PRIMARY KEY  (`id`),
  UNIQUE KEY `account` (`account`),
  KEY `company` (`company`,`dept`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_userGroup`;
CREATE TABLE IF NOT EXISTS `zt_userGroup` (
  `account` char(30) NOT NULL default '',
  `group` mediumint(8) unsigned NOT NULL default '0',
  UNIQUE KEY `account` (`account`,`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
