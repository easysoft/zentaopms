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

--20100139 调整taskcase表
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
  `runDate` datetime NOT NULL,
  `result` char(30) NOT NULL,
  `status` char(30) NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `task` (`task`,`case`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

--20100201 调整测试结果表。
DROP TABLE `zt_caseResult`;
DROP TABLE `zt_stepResult`;
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
