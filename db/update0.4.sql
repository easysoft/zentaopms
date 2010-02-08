-- 20100128 修改user表中ip字段的默认值，解决install失败的问题。
ALTER TABLE `zt_user` CHANGE `ip` `ip` CHAR( 15 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';

-- 20100128: 调整casestep表。
ALTER TABLE `zt_caseStep` CHANGE `caseVersion` `version` SMALLINT( 3 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zt_caseStep` CHANGE `step` `desc` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `zt_caseStep` CHANGE `expect` `expect` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `zt_caseStep` ADD INDEX ( `case` , `version` ) ;

-- 20100128 转换case中的step字段
update zt_case set version = 1 where version = 0;
insert into zt_caseStep select '', id,version,steps, '' from zt_case;
ALTER TABLE `zt_case` DROP `steps`;

--20100139 adjust the test case.
DROP TABLE `zt_testPlan`;
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
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

DROP TABLE `zt_planCase`;
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
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--20100201 adjust the test result table.
DROP TABLE `zt_caseResult`;
DROP TABLE `zt_resultStep`;
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
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- 20100204 adjust the story.
ALTER TABLE `zt_story` DROP `attatchment`;
ALTER TABLE `zt_story` CHANGE `version` `version` SMALLINT NOT NULL DEFAULT '1';
ALTER TABLE `zt_story` ADD `closedReason` VARCHAR( 30 ) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_story` ADD `stage` VARCHAR( 30 ) NOT NULL AFTER `status`;
ALTER TABLE `zt_story` ADD `reviewedBy` VARCHAR( 30 ) NOT NULL AFTER `lastEditedDate`;
ALTER TABLE `zt_story` ADD `reviewedDate` DATETIME NOT NULL AFTER `reviewedBy`;
UPDATE zt_story SET version = 1 WHERE version = 0;
UPDATE zt_story SET status = 'closed', closedReason = 'done', stage='released' WHERE status = 'done';
UPDATE zt_story SET status = 'active' WHERE status = 'wait' OR status = 'doing';
ALTER TABLE `zt_story` CHANGE `bug` `fromBug` MEDIUMINT( 8 ) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zt_story` ADD `toBug` MEDIUMINT NOT NULL AFTER `closedReason`;
ALTER TABLE `zt_story` ADD `childStories` VARCHAR( 255 ) NOT NULL AFTER `toBug` ,
ADD `linkStories` VARCHAR( 255 ) NOT NULL AFTER `childStories`;
ALTER TABLE `zt_story` ADD `duplicateStory` MEDIUMINT UNSIGNED NOT NULL AFTER `linkStories`;

CREATE TABLE IF NOT EXISTS `zt_storySpec` (
  `story` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `title` VARCHAR( 90 ) NOT NULL,
  `spec` text NOT NULL,
  UNIQUE KEY `story` (`story`,`version`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;
INSERT INTO zt_storySpec select id,version,title,spec FROM zt_story;
ALTER TABLE `zt_story` DROP `spec`;
ALTER TABLE `zt_file` ADD `extra` VARCHAR( 255 ) NOT NULL ;
update `zt_file` set extra = 1 where objectType = 'story';

ALTER TABLE `zt_bug` ADD `storyVersion` SMALLINT NOT NULL DEFAULT '1' AFTER `story`;
ALTER TABLE `zt_bug` ADD `caseVersion` SMALLINT NOT NULL DEFAULT '1' AFTER `case`;
ALTER TABLE `zt_bug` DROP `field1` ,
DROP `field2` ,
DROP `feild3` ;

ALTER TABLE `zt_case` DROP `field1` ,
DROP `field2` ,
DROP `feidl3` ;
ALTER TABLE `zt_case` ADD `storyVersion` SMALLINT NOT NULL DEFAULT '1' AFTER `story`;
ALTER TABLE `zt_projectStory` ADD `version` SMALLINT NOT NULL DEFAULT '1';
ALTER TABLE `zt_task` ADD `storyVersion` SMALLINT NOT NULL DEFAULT '1' AFTER `story`;

-- delete releation.
DROP TABLE `zt_releation`;

-- 20100208 adjust action table.
ALTER TABLE `zt_action` ADD `extra` VARCHAR( 255 ) NOT NULL AFTER `id`;
UPDATE zt_action SET extra = substr( ACTION , 13 ) , ACTION = 'Resolved' WHERE ACTION LIKE 'Resolved%';
