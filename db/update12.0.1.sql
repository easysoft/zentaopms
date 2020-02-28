ALTER TABLE `zt_story` CHANGE `type` `type` varchar(30) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'story' AFTER `keywords`;
UPDATE zt_story SET `type` = 'story' WHERE `type` = '';

-- DROP TABLE IF EXISTS `zt_jenkins`;
CREATE TABLE IF NOT EXISTS `zt_jenkins` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `account` varchar(30) DEFAULT NULL,
  `password` varchar(30) NOT NULL,
  `encrypt` varchar(30) NOT NULL DEFAULT 'plain',
  `token` varchar(255) DEFAULT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_integration`;
CREATE TABLE IF NOT EXISTS `zt_integration` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `repo` mediumint(8) unsigned NOT NULL,
  `jenkins` mediumint(8) unsigned NOT NULL,
  `jenkinsJob` varchar(500) NOT NULL,
  `triggerType` varchar(255) NOT NULL,
  `svnFolder` varchar(255) NOT NULL,
  `scheduleDay` varchar(255) DEFAULT NULL,
  `tagKeywords` varchar(255) DEFAULT NULL,
  `commentKeywords` varchar(255) DEFAULT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `lastExec` datetime DEFAULT NULL,
  `lastStatus` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_compile`;
CREATE TABLE IF NOT EXISTS `zt_compile` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `cijob` mediumint(8) unsigned NOT NULL,
  `queueItem` mediumint(8) NOT NULL,
  `status` varchar(255) NOT NULL,
  `logs` text,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('1',    '1',    '*',    '*',    '*',    'moduleName=ci&methodName=buildTodayJob', '创建周期性任务', 'zentao', 1, 'normal',   '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=ci&methodName=checkBuildStatus', '同步Jenkins任务状态', 'zentao', 1, 'normal',   '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=ci&methodName=exec', '执行Jenkins任务', 'zentao', 1, 'normal',   '0000-00-00 00:00:00');

ALTER TABLE `zt_integration`
CHANGE `jenkins` `jkHost` mediumint(8) unsigned NOT NULL AFTER `repo`,
CHANGE `jenkinsJob` `jkJob` varchar(500) COLLATE 'utf8_general_ci' NOT NULL AFTER `jkHost`,
CHANGE `svnFolder` `svnDir` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `triggerType`,
CHANGE `scheduleDay` `atDay` varchar(20) COLLATE 'utf8_general_ci' NULL AFTER `svnDir`,
ADD `atTime` varchar(10) COLLATE 'utf8_general_ci' NULL AFTER `atDay`,
DROP `tagKeywords`,
DROP `commentKeywords`,
ADD `comment` varchar(255) COLLATE 'utf8_general_ci' NULL AFTER `atTime`,
ADD `lastTag` varchar(255) COLLATE 'utf8_general_ci' NULL;
