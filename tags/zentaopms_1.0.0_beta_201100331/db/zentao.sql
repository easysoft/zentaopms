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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_bug`;
CREATE TABLE IF NOT EXISTS `zt_bug` (
  `id` mediumint(8) NOT NULL auto_increment,
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_build`;
CREATE TABLE IF NOT EXISTS `zt_build` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `project` mediumint(8) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `scmPath` char(255) NOT NULL,
  `filePath` char(255) NOT NULL,
  `date` date NOT NULL,
  `builder` char(30) NOT NULL default '',
  `desc` char(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_burn`;
CREATE TABLE IF NOT EXISTS `zt_burn` (
  `project` mediumint(8) unsigned NOT NULL,
  `date` date NOT NULL,
  `left` float NOT NULL,
  `consumed` float NOT NULL,
  PRIMARY KEY  (`project`,`date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_case`;
CREATE TABLE IF NOT EXISTS `zt_case` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
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
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_caseStep`;
CREATE TABLE IF NOT EXISTS `zt_caseStep` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `case` mediumint(8) unsigned NOT NULL default '0',
  `version` smallint(3) unsigned NOT NULL default '0',
  `desc` text NOT NULL,
  `expect` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `case` (`case`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
  `objectType` char(30) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `addedBy` char(30) NOT NULL default '',
  `addedDate` datetime NOT NULL,
  `downloads` mediumint(8) unsigned NOT NULL default '0',
  `extra` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_product`;
CREATE TABLE IF NOT EXISTS `zt_product` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `order` tinyint(3) unsigned NOT NULL default '0',
  `status` varchar(30) NOT NULL default '',
  `desc` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `company` (`company`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_productPlan`;
CREATE TABLE IF NOT EXISTS `zt_productPlan` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL,
  `title` varchar(90) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
  PRIMARY KEY  (`id`),
  KEY `company` (`company`,`type`,`parent`,`begin`,`end`,`status`,`statge`,`pri`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_projectProduct`;
CREATE TABLE IF NOT EXISTS `zt_projectProduct` (
  `project` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY  (`project`,`product`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_projectStory`;
CREATE TABLE IF NOT EXISTS `zt_projectStory` (
  `project` mediumint(8) unsigned NOT NULL default '0',
  `product` mediumint(8) unsigned NOT NULL,
  `story` mediumint(8) unsigned NOT NULL default '0',
  `version` smallint(6) NOT NULL default '1',
  UNIQUE KEY `project` (`project`,`story`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_release`;
CREATE TABLE IF NOT EXISTS `zt_release` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `build` mediumint(8) unsigned NOT NULL,
  `name` char(30) NOT NULL default '',
  `date` date NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_story`;
CREATE TABLE IF NOT EXISTS `zt_story` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
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
  PRIMARY KEY  (`id`),
  KEY `product` (`product`,`module`,`plan`,`type`,`pri`),
  KEY `status` (`status`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_storySpec`;
CREATE TABLE IF NOT EXISTS `zt_storySpec` (
  `story` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `title` varchar(90) NOT NULL,
  `spec` text NOT NULL,
  UNIQUE KEY `story` (`story`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_task`;
CREATE TABLE IF NOT EXISTS `zt_task` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
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
  `desc` text NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `statusOrder` (`statusCustom`),
  KEY `type` (`type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
-- DROP TABLE IF EXISTS `zt_testResult`;
CREATE TABLE IF NOT EXISTS `zt_testResult` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `run` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  `caseResult` char(30) NOT NULL,
  `stepResults` text NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `run` (`run`),
  KEY `case` (`case`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_testRun`;
CREATE TABLE IF NOT EXISTS `zt_testRun` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `task` mediumint(8) unsigned NOT NULL default '0',
  `case` mediumint(8) unsigned NOT NULL default '0',
  `version` tinyint(3) unsigned NOT NULL default '0',
  `assignedTo` char(30) NOT NULL default '',
  `lastRun` datetime NOT NULL,
  `lastResult` char(30) NOT NULL,
  `status` char(30) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `task` (`task`,`case`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_testTask`;
CREATE TABLE IF NOT EXISTS `zt_testTask` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `name` char(90) NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL default '0',
  `build` char(30) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `desc` text NOT NULL,
  `status` char(30) NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_todo`;
CREATE TABLE IF NOT EXISTS `zt_todo` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
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
  KEY `user` (`account`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
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
  `status` varchar(30) NOT NULL default 'active',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `account` (`account`),
  KEY `company` (`company`,`dept`),
  KEY `status` (`status`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_userGroup`;
CREATE TABLE IF NOT EXISTS `zt_userGroup` (
  `account` char(30) NOT NULL default '',
  `group` mediumint(8) unsigned NOT NULL default '0',
  UNIQUE KEY `account` (`account`,`group`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `zt_group` VALUES(1, 1, 'admin', 'Admin');
INSERT INTO `zt_group` VALUES(2, 1, 'product', 'Product');
INSERT INTO `zt_group` VALUES(3, 1, 'develop', 'Develop');
INSERT INTO `zt_group` VALUES(4, 1, 'qa', 'QA');
INSERT INTO `zt_group` VALUES(5, 1, 'pm', 'PM');

INSERT INTO `zt_groupPriv` VALUES(1, 'admin', 'browseCompany');
INSERT INTO `zt_groupPriv` VALUES(1, 'admin', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'api', 'getModel');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'activate');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'ajaxGetUserBugs');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'close');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'confirmStoryChange');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'report');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'resolve');
INSERT INTO `zt_groupPriv` VALUES(1, 'bug', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'build', 'ajaxGetProductBuilds');
INSERT INTO `zt_groupPriv` VALUES(1, 'build', 'ajaxGetProjectBuilds');
INSERT INTO `zt_groupPriv` VALUES(1, 'build', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'build', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'build', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'build', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'company', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'company', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'company', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'company', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'company', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'dept', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'dept', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'dept', 'manageChild');
INSERT INTO `zt_groupPriv` VALUES(1, 'dept', 'updateOrder');
INSERT INTO `zt_groupPriv` VALUES(1, 'file', 'download');
INSERT INTO `zt_groupPriv` VALUES(1, 'group', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'group', 'copy');
INSERT INTO `zt_groupPriv` VALUES(1, 'group', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'group', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'group', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'group', 'manageMember');
INSERT INTO `zt_groupPriv` VALUES(1, 'group', 'managePriv');
INSERT INTO `zt_groupPriv` VALUES(1, 'index', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'misc', 'ping');
INSERT INTO `zt_groupPriv` VALUES(1, 'my', 'bug');
INSERT INTO `zt_groupPriv` VALUES(1, 'my', 'editProfile');
INSERT INTO `zt_groupPriv` VALUES(1, 'my', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'my', 'profile');
INSERT INTO `zt_groupPriv` VALUES(1, 'my', 'project');
INSERT INTO `zt_groupPriv` VALUES(1, 'my', 'story');
INSERT INTO `zt_groupPriv` VALUES(1, 'my', 'task');
INSERT INTO `zt_groupPriv` VALUES(1, 'my', 'todo');
INSERT INTO `zt_groupPriv` VALUES(1, 'product', 'ajaxGetPlans');
INSERT INTO `zt_groupPriv` VALUES(1, 'product', 'ajaxGetProjects');
INSERT INTO `zt_groupPriv` VALUES(1, 'product', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'product', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'product', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'product', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'product', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'product', 'roadmap');
INSERT INTO `zt_groupPriv` VALUES(1, 'productplan', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'productplan', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'productplan', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'productplan', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'productplan', 'linkStory');
INSERT INTO `zt_groupPriv` VALUES(1, 'productplan', 'unlinkStory');
INSERT INTO `zt_groupPriv` VALUES(1, 'productplan', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'bug');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'build');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'burn');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'burnData');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'grouptask');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'importtask');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'linkStory');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'manageChilds');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'manageMembers');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'manageProducts');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'story');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'task');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'team');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'unlinkMember');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'unlinkStory');
INSERT INTO `zt_groupPriv` VALUES(1, 'project', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'qa', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'release', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'release', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'release', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'release', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'release', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'search', 'buildForm');
INSERT INTO `zt_groupPriv` VALUES(1, 'search', 'buildQuery');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'activate');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'ajaxGetProductStories');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'ajaxGetProjectStories');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'change');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'close');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'review');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'tasks');
INSERT INTO `zt_groupPriv` VALUES(1, 'story', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'task', 'ajaxGetProjectTasks');
INSERT INTO `zt_groupPriv` VALUES(1, 'task', 'ajaxGetUserTasks');
INSERT INTO `zt_groupPriv` VALUES(1, 'task', 'confirmStoryChange');
INSERT INTO `zt_groupPriv` VALUES(1, 'task', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'task', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'task', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'task', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'testcase', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'testcase', 'confirmStoryChange');
INSERT INTO `zt_groupPriv` VALUES(1, 'testcase', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'testcase', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'testcase', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'testcase', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'batchAssign');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'index');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'linkcase');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'results');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'runcase');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'unlinkcase');
INSERT INTO `zt_groupPriv` VALUES(1, 'testtask', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'todo', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'todo', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'todo', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'todo', 'import2Today');
INSERT INTO `zt_groupPriv` VALUES(1, 'todo', 'mark');
INSERT INTO `zt_groupPriv` VALUES(1, 'todo', 'view');
INSERT INTO `zt_groupPriv` VALUES(1, 'tree', 'ajaxGetOptionMenu');
INSERT INTO `zt_groupPriv` VALUES(1, 'tree', 'browse');
INSERT INTO `zt_groupPriv` VALUES(1, 'tree', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'tree', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'tree', 'manageChild');
INSERT INTO `zt_groupPriv` VALUES(1, 'tree', 'updateOrder');
INSERT INTO `zt_groupPriv` VALUES(1, 'user', 'bug');
INSERT INTO `zt_groupPriv` VALUES(1, 'user', 'create');
INSERT INTO `zt_groupPriv` VALUES(1, 'user', 'delete');
INSERT INTO `zt_groupPriv` VALUES(1, 'user', 'edit');
INSERT INTO `zt_groupPriv` VALUES(1, 'user', 'profile');
INSERT INTO `zt_groupPriv` VALUES(1, 'user', 'project');
INSERT INTO `zt_groupPriv` VALUES(1, 'user', 'task');
INSERT INTO `zt_groupPriv` VALUES(1, 'user', 'todo');
INSERT INTO `zt_groupPriv` VALUES(1, 'user', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'activate');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'ajaxGetUserBugs');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'browse');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'close');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'create');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'index');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'report');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'resolve');
INSERT INTO `zt_groupPriv` VALUES(2, 'bug', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'build', 'ajaxGetProductBuilds');
INSERT INTO `zt_groupPriv` VALUES(2, 'build', 'ajaxGetProjectBuilds');
INSERT INTO `zt_groupPriv` VALUES(2, 'build', 'create');
INSERT INTO `zt_groupPriv` VALUES(2, 'build', 'delete');
INSERT INTO `zt_groupPriv` VALUES(2, 'build', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'build', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'company', 'browse');
INSERT INTO `zt_groupPriv` VALUES(2, 'company', 'index');
INSERT INTO `zt_groupPriv` VALUES(2, 'file', 'download');
INSERT INTO `zt_groupPriv` VALUES(2, 'index', 'index');
INSERT INTO `zt_groupPriv` VALUES(2, 'misc', 'ping');
INSERT INTO `zt_groupPriv` VALUES(2, 'my', 'bug');
INSERT INTO `zt_groupPriv` VALUES(2, 'my', 'editProfile');
INSERT INTO `zt_groupPriv` VALUES(2, 'my', 'index');
INSERT INTO `zt_groupPriv` VALUES(2, 'my', 'profile');
INSERT INTO `zt_groupPriv` VALUES(2, 'my', 'project');
INSERT INTO `zt_groupPriv` VALUES(2, 'my', 'story');
INSERT INTO `zt_groupPriv` VALUES(2, 'my', 'task');
INSERT INTO `zt_groupPriv` VALUES(2, 'my', 'todo');
INSERT INTO `zt_groupPriv` VALUES(2, 'product', 'ajaxGetPlans');
INSERT INTO `zt_groupPriv` VALUES(2, 'product', 'ajaxGetProjects');
INSERT INTO `zt_groupPriv` VALUES(2, 'product', 'browse');
INSERT INTO `zt_groupPriv` VALUES(2, 'product', 'create');
INSERT INTO `zt_groupPriv` VALUES(2, 'product', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'product', 'index');
INSERT INTO `zt_groupPriv` VALUES(2, 'product', 'roadmap');
INSERT INTO `zt_groupPriv` VALUES(2, 'productplan', 'browse');
INSERT INTO `zt_groupPriv` VALUES(2, 'productplan', 'create');
INSERT INTO `zt_groupPriv` VALUES(2, 'productplan', 'delete');
INSERT INTO `zt_groupPriv` VALUES(2, 'productplan', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'productplan', 'linkStory');
INSERT INTO `zt_groupPriv` VALUES(2, 'productplan', 'unlinkStory');
INSERT INTO `zt_groupPriv` VALUES(2, 'productplan', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'browse');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'bug');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'build');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'burn');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'burnData');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'grouptask');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'index');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'linkStory');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'manageProducts');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'story');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'task');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'team');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'unlinkStory');
INSERT INTO `zt_groupPriv` VALUES(2, 'project', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'qa', 'index');
INSERT INTO `zt_groupPriv` VALUES(2, 'release', 'browse');
INSERT INTO `zt_groupPriv` VALUES(2, 'release', 'create');
INSERT INTO `zt_groupPriv` VALUES(2, 'release', 'delete');
INSERT INTO `zt_groupPriv` VALUES(2, 'release', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'release', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'search', 'buildForm');
INSERT INTO `zt_groupPriv` VALUES(2, 'search', 'buildQuery');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'activate');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'ajaxGetProductStories');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'ajaxGetProjectStories');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'change');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'close');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'create');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'delete');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'review');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'tasks');
INSERT INTO `zt_groupPriv` VALUES(2, 'story', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'task', 'ajaxGetProjectTasks');
INSERT INTO `zt_groupPriv` VALUES(2, 'task', 'ajaxGetUserTasks');
INSERT INTO `zt_groupPriv` VALUES(2, 'task', 'create');
INSERT INTO `zt_groupPriv` VALUES(2, 'task', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'task', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'testcase', 'browse');
INSERT INTO `zt_groupPriv` VALUES(2, 'testcase', 'create');
INSERT INTO `zt_groupPriv` VALUES(2, 'testcase', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'testcase', 'index');
INSERT INTO `zt_groupPriv` VALUES(2, 'testcase', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'testtask', 'browse');
INSERT INTO `zt_groupPriv` VALUES(2, 'testtask', 'index');
INSERT INTO `zt_groupPriv` VALUES(2, 'testtask', 'results');
INSERT INTO `zt_groupPriv` VALUES(2, 'testtask', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'todo', 'create');
INSERT INTO `zt_groupPriv` VALUES(2, 'todo', 'delete');
INSERT INTO `zt_groupPriv` VALUES(2, 'todo', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'todo', 'import2Today');
INSERT INTO `zt_groupPriv` VALUES(2, 'todo', 'mark');
INSERT INTO `zt_groupPriv` VALUES(2, 'todo', 'view');
INSERT INTO `zt_groupPriv` VALUES(2, 'tree', 'ajaxGetOptionMenu');
INSERT INTO `zt_groupPriv` VALUES(2, 'tree', 'browse');
INSERT INTO `zt_groupPriv` VALUES(2, 'tree', 'delete');
INSERT INTO `zt_groupPriv` VALUES(2, 'tree', 'edit');
INSERT INTO `zt_groupPriv` VALUES(2, 'tree', 'manageChild');
INSERT INTO `zt_groupPriv` VALUES(2, 'tree', 'updateOrder');
INSERT INTO `zt_groupPriv` VALUES(2, 'user', 'bug');
INSERT INTO `zt_groupPriv` VALUES(2, 'user', 'profile');
INSERT INTO `zt_groupPriv` VALUES(2, 'user', 'project');
INSERT INTO `zt_groupPriv` VALUES(2, 'user', 'task');
INSERT INTO `zt_groupPriv` VALUES(2, 'user', 'todo');
INSERT INTO `zt_groupPriv` VALUES(2, 'user', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'activate');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'ajaxGetUserBugs');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'browse');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'close');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'create');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'edit');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'index');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'report');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'resolve');
INSERT INTO `zt_groupPriv` VALUES(3, 'bug', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'build', 'ajaxGetProductBuilds');
INSERT INTO `zt_groupPriv` VALUES(3, 'build', 'ajaxGetProjectBuilds');
INSERT INTO `zt_groupPriv` VALUES(3, 'build', 'create');
INSERT INTO `zt_groupPriv` VALUES(3, 'build', 'edit');
INSERT INTO `zt_groupPriv` VALUES(3, 'build', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'company', 'browse');
INSERT INTO `zt_groupPriv` VALUES(3, 'company', 'index');
INSERT INTO `zt_groupPriv` VALUES(3, 'file', 'download');
INSERT INTO `zt_groupPriv` VALUES(3, 'index', 'index');
INSERT INTO `zt_groupPriv` VALUES(3, 'misc', 'ping');
INSERT INTO `zt_groupPriv` VALUES(3, 'my', 'bug');
INSERT INTO `zt_groupPriv` VALUES(3, 'my', 'editProfile');
INSERT INTO `zt_groupPriv` VALUES(3, 'my', 'index');
INSERT INTO `zt_groupPriv` VALUES(3, 'my', 'profile');
INSERT INTO `zt_groupPriv` VALUES(3, 'my', 'project');
INSERT INTO `zt_groupPriv` VALUES(3, 'my', 'story');
INSERT INTO `zt_groupPriv` VALUES(3, 'my', 'task');
INSERT INTO `zt_groupPriv` VALUES(3, 'my', 'todo');
INSERT INTO `zt_groupPriv` VALUES(3, 'product', 'ajaxGetPlans');
INSERT INTO `zt_groupPriv` VALUES(3, 'product', 'ajaxGetProjects');
INSERT INTO `zt_groupPriv` VALUES(3, 'product', 'browse');
INSERT INTO `zt_groupPriv` VALUES(3, 'product', 'index');
INSERT INTO `zt_groupPriv` VALUES(3, 'product', 'roadmap');
INSERT INTO `zt_groupPriv` VALUES(3, 'productplan', 'browse');
INSERT INTO `zt_groupPriv` VALUES(3, 'productplan', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'browse');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'bug');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'build');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'burn');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'burnData');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'grouptask');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'index');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'story');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'task');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'team');
INSERT INTO `zt_groupPriv` VALUES(3, 'project', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'qa', 'index');
INSERT INTO `zt_groupPriv` VALUES(3, 'release', 'browse');
INSERT INTO `zt_groupPriv` VALUES(3, 'release', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'search', 'buildForm');
INSERT INTO `zt_groupPriv` VALUES(3, 'search', 'buildQuery');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'activate');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'ajaxGetProductStories');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'ajaxGetProjectStories');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'change');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'close');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'create');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'edit');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'review');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'tasks');
INSERT INTO `zt_groupPriv` VALUES(3, 'story', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'task', 'ajaxGetProjectTasks');
INSERT INTO `zt_groupPriv` VALUES(3, 'task', 'ajaxGetUserTasks');
INSERT INTO `zt_groupPriv` VALUES(3, 'task', 'confirmStoryChange');
INSERT INTO `zt_groupPriv` VALUES(3, 'task', 'create');
INSERT INTO `zt_groupPriv` VALUES(3, 'task', 'edit');
INSERT INTO `zt_groupPriv` VALUES(3, 'task', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'testcase', 'browse');
INSERT INTO `zt_groupPriv` VALUES(3, 'testcase', 'index');
INSERT INTO `zt_groupPriv` VALUES(3, 'testcase', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'testtask', 'browse');
INSERT INTO `zt_groupPriv` VALUES(3, 'testtask', 'index');
INSERT INTO `zt_groupPriv` VALUES(3, 'testtask', 'results');
INSERT INTO `zt_groupPriv` VALUES(3, 'testtask', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'todo', 'create');
INSERT INTO `zt_groupPriv` VALUES(3, 'todo', 'delete');
INSERT INTO `zt_groupPriv` VALUES(3, 'todo', 'edit');
INSERT INTO `zt_groupPriv` VALUES(3, 'todo', 'import2Today');
INSERT INTO `zt_groupPriv` VALUES(3, 'todo', 'mark');
INSERT INTO `zt_groupPriv` VALUES(3, 'todo', 'view');
INSERT INTO `zt_groupPriv` VALUES(3, 'user', 'bug');
INSERT INTO `zt_groupPriv` VALUES(3, 'user', 'profile');
INSERT INTO `zt_groupPriv` VALUES(3, 'user', 'project');
INSERT INTO `zt_groupPriv` VALUES(3, 'user', 'task');
INSERT INTO `zt_groupPriv` VALUES(3, 'user', 'todo');
INSERT INTO `zt_groupPriv` VALUES(3, 'user', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'activate');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'ajaxGetUserBugs');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'browse');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'close');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'confirmStoryChange');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'create');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'edit');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'index');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'report');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'resolve');
INSERT INTO `zt_groupPriv` VALUES(4, 'bug', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'build', 'ajaxGetProductBuilds');
INSERT INTO `zt_groupPriv` VALUES(4, 'build', 'ajaxGetProjectBuilds');
INSERT INTO `zt_groupPriv` VALUES(4, 'build', 'create');
INSERT INTO `zt_groupPriv` VALUES(4, 'build', 'delete');
INSERT INTO `zt_groupPriv` VALUES(4, 'build', 'edit');
INSERT INTO `zt_groupPriv` VALUES(4, 'build', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'company', 'browse');
INSERT INTO `zt_groupPriv` VALUES(4, 'company', 'index');
INSERT INTO `zt_groupPriv` VALUES(4, 'file', 'download');
INSERT INTO `zt_groupPriv` VALUES(4, 'index', 'index');
INSERT INTO `zt_groupPriv` VALUES(4, 'misc', 'ping');
INSERT INTO `zt_groupPriv` VALUES(4, 'my', 'bug');
INSERT INTO `zt_groupPriv` VALUES(4, 'my', 'editProfile');
INSERT INTO `zt_groupPriv` VALUES(4, 'my', 'index');
INSERT INTO `zt_groupPriv` VALUES(4, 'my', 'profile');
INSERT INTO `zt_groupPriv` VALUES(4, 'my', 'project');
INSERT INTO `zt_groupPriv` VALUES(4, 'my', 'story');
INSERT INTO `zt_groupPriv` VALUES(4, 'my', 'task');
INSERT INTO `zt_groupPriv` VALUES(4, 'my', 'todo');
INSERT INTO `zt_groupPriv` VALUES(4, 'product', 'ajaxGetPlans');
INSERT INTO `zt_groupPriv` VALUES(4, 'product', 'ajaxGetProjects');
INSERT INTO `zt_groupPriv` VALUES(4, 'product', 'browse');
INSERT INTO `zt_groupPriv` VALUES(4, 'product', 'index');
INSERT INTO `zt_groupPriv` VALUES(4, 'product', 'roadmap');
INSERT INTO `zt_groupPriv` VALUES(4, 'productplan', 'browse');
INSERT INTO `zt_groupPriv` VALUES(4, 'productplan', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'browse');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'bug');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'build');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'burn');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'burnData');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'grouptask');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'index');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'story');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'task');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'team');
INSERT INTO `zt_groupPriv` VALUES(4, 'project', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'qa', 'index');
INSERT INTO `zt_groupPriv` VALUES(4, 'release', 'browse');
INSERT INTO `zt_groupPriv` VALUES(4, 'release', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'search', 'buildForm');
INSERT INTO `zt_groupPriv` VALUES(4, 'search', 'buildQuery');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'activate');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'ajaxGetProductStories');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'ajaxGetProjectStories');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'change');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'close');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'create');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'edit');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'review');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'tasks');
INSERT INTO `zt_groupPriv` VALUES(4, 'story', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'task', 'ajaxGetProjectTasks');
INSERT INTO `zt_groupPriv` VALUES(4, 'task', 'ajaxGetUserTasks');
INSERT INTO `zt_groupPriv` VALUES(4, 'task', 'create');
INSERT INTO `zt_groupPriv` VALUES(4, 'task', 'edit');
INSERT INTO `zt_groupPriv` VALUES(4, 'task', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'testcase', 'browse');
INSERT INTO `zt_groupPriv` VALUES(4, 'testcase', 'confirmStoryChange');
INSERT INTO `zt_groupPriv` VALUES(4, 'testcase', 'create');
INSERT INTO `zt_groupPriv` VALUES(4, 'testcase', 'edit');
INSERT INTO `zt_groupPriv` VALUES(4, 'testcase', 'index');
INSERT INTO `zt_groupPriv` VALUES(4, 'testcase', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'batchAssign');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'browse');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'create');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'delete');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'edit');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'index');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'linkcase');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'results');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'runcase');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'unlinkcase');
INSERT INTO `zt_groupPriv` VALUES(4, 'testtask', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'todo', 'create');
INSERT INTO `zt_groupPriv` VALUES(4, 'todo', 'delete');
INSERT INTO `zt_groupPriv` VALUES(4, 'todo', 'edit');
INSERT INTO `zt_groupPriv` VALUES(4, 'todo', 'import2Today');
INSERT INTO `zt_groupPriv` VALUES(4, 'todo', 'mark');
INSERT INTO `zt_groupPriv` VALUES(4, 'todo', 'view');
INSERT INTO `zt_groupPriv` VALUES(4, 'tree', 'ajaxGetOptionMenu');
INSERT INTO `zt_groupPriv` VALUES(4, 'tree', 'browse');
INSERT INTO `zt_groupPriv` VALUES(4, 'tree', 'delete');
INSERT INTO `zt_groupPriv` VALUES(4, 'tree', 'edit');
INSERT INTO `zt_groupPriv` VALUES(4, 'tree', 'manageChild');
INSERT INTO `zt_groupPriv` VALUES(4, 'tree', 'updateOrder');
INSERT INTO `zt_groupPriv` VALUES(4, 'user', 'bug');
INSERT INTO `zt_groupPriv` VALUES(4, 'user', 'profile');
INSERT INTO `zt_groupPriv` VALUES(4, 'user', 'project');
INSERT INTO `zt_groupPriv` VALUES(4, 'user', 'task');
INSERT INTO `zt_groupPriv` VALUES(4, 'user', 'todo');
INSERT INTO `zt_groupPriv` VALUES(4, 'user', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'activate');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'ajaxGetUserBugs');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'browse');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'close');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'create');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'edit');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'index');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'report');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'resolve');
INSERT INTO `zt_groupPriv` VALUES(5, 'bug', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'build', 'ajaxGetProductBuilds');
INSERT INTO `zt_groupPriv` VALUES(5, 'build', 'ajaxGetProjectBuilds');
INSERT INTO `zt_groupPriv` VALUES(5, 'build', 'create');
INSERT INTO `zt_groupPriv` VALUES(5, 'build', 'delete');
INSERT INTO `zt_groupPriv` VALUES(5, 'build', 'edit');
INSERT INTO `zt_groupPriv` VALUES(5, 'build', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'company', 'browse');
INSERT INTO `zt_groupPriv` VALUES(5, 'company', 'index');
INSERT INTO `zt_groupPriv` VALUES(5, 'file', 'download');
INSERT INTO `zt_groupPriv` VALUES(5, 'index', 'index');
INSERT INTO `zt_groupPriv` VALUES(5, 'misc', 'ping');
INSERT INTO `zt_groupPriv` VALUES(5, 'my', 'bug');
INSERT INTO `zt_groupPriv` VALUES(5, 'my', 'editProfile');
INSERT INTO `zt_groupPriv` VALUES(5, 'my', 'index');
INSERT INTO `zt_groupPriv` VALUES(5, 'my', 'profile');
INSERT INTO `zt_groupPriv` VALUES(5, 'my', 'project');
INSERT INTO `zt_groupPriv` VALUES(5, 'my', 'story');
INSERT INTO `zt_groupPriv` VALUES(5, 'my', 'task');
INSERT INTO `zt_groupPriv` VALUES(5, 'my', 'todo');
INSERT INTO `zt_groupPriv` VALUES(5, 'product', 'ajaxGetPlans');
INSERT INTO `zt_groupPriv` VALUES(5, 'product', 'ajaxGetProjects');
INSERT INTO `zt_groupPriv` VALUES(5, 'product', 'browse');
INSERT INTO `zt_groupPriv` VALUES(5, 'product', 'index');
INSERT INTO `zt_groupPriv` VALUES(5, 'product', 'roadmap');
INSERT INTO `zt_groupPriv` VALUES(5, 'productplan', 'browse');
INSERT INTO `zt_groupPriv` VALUES(5, 'productplan', 'linkStory');
INSERT INTO `zt_groupPriv` VALUES(5, 'productplan', 'unlinkStory');
INSERT INTO `zt_groupPriv` VALUES(5, 'productplan', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'browse');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'bug');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'build');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'burn');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'burnData');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'create');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'delete');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'edit');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'grouptask');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'importtask');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'index');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'linkStory');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'manageChilds');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'manageMembers');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'manageProducts');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'story');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'task');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'team');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'unlinkMember');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'unlinkStory');
INSERT INTO `zt_groupPriv` VALUES(5, 'project', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'qa', 'index');
INSERT INTO `zt_groupPriv` VALUES(5, 'release', 'browse');
INSERT INTO `zt_groupPriv` VALUES(5, 'release', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'search', 'buildForm');
INSERT INTO `zt_groupPriv` VALUES(5, 'search', 'buildQuery');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'activate');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'ajaxGetProductStories');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'ajaxGetProjectStories');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'change');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'close');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'create');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'edit');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'review');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'tasks');
INSERT INTO `zt_groupPriv` VALUES(5, 'story', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'task', 'ajaxGetProjectTasks');
INSERT INTO `zt_groupPriv` VALUES(5, 'task', 'ajaxGetUserTasks');
INSERT INTO `zt_groupPriv` VALUES(5, 'task', 'confirmStoryChange');
INSERT INTO `zt_groupPriv` VALUES(5, 'task', 'create');
INSERT INTO `zt_groupPriv` VALUES(5, 'task', 'delete');
INSERT INTO `zt_groupPriv` VALUES(5, 'task', 'edit');
INSERT INTO `zt_groupPriv` VALUES(5, 'task', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'testcase', 'browse');
INSERT INTO `zt_groupPriv` VALUES(5, 'testcase', 'edit');
INSERT INTO `zt_groupPriv` VALUES(5, 'testcase', 'index');
INSERT INTO `zt_groupPriv` VALUES(5, 'testcase', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'testtask', 'browse');
INSERT INTO `zt_groupPriv` VALUES(5, 'testtask', 'index');
INSERT INTO `zt_groupPriv` VALUES(5, 'testtask', 'results');
INSERT INTO `zt_groupPriv` VALUES(5, 'testtask', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'todo', 'create');
INSERT INTO `zt_groupPriv` VALUES(5, 'todo', 'delete');
INSERT INTO `zt_groupPriv` VALUES(5, 'todo', 'edit');
INSERT INTO `zt_groupPriv` VALUES(5, 'todo', 'import2Today');
INSERT INTO `zt_groupPriv` VALUES(5, 'todo', 'mark');
INSERT INTO `zt_groupPriv` VALUES(5, 'todo', 'view');
INSERT INTO `zt_groupPriv` VALUES(5, 'user', 'bug');
INSERT INTO `zt_groupPriv` VALUES(5, 'user', 'profile');
INSERT INTO `zt_groupPriv` VALUES(5, 'user', 'project');
INSERT INTO `zt_groupPriv` VALUES(5, 'user', 'task');
INSERT INTO `zt_groupPriv` VALUES(5, 'user', 'todo');
INSERT INTO `zt_groupPriv` VALUES(5, 'user', 'view');
