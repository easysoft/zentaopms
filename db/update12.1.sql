
CREATE TABLE `zt_tag` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `repo` mediumint(9) NOT NULL,
  `name` varchar(40) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `zt_jenkins` (
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

CREATE TABLE `zt_cijob` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `repo` mediumint(8) unsigned NOT NULL,
  `jenkins` mediumint(8) unsigned NOT NULL,
  `jenkinsJob` varchar(500) NOT NULL,
  `triggerType` varchar(255) NOT NULL,
  `scheduleType` varchar(255) NOT NULL,
  `cronExpression` varchar(255) DEFAULT NULL,
  `scheduleDay` varchar(255) DEFAULT NULL,
  `scheduleTime` varchar(255) DEFAULT NULL,
  `scheduleInterval` mediumint(8) DEFAULT NULL,
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

CREATE TABLE `zt_cibuild` (
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
('*/5',  '*',    '*',    '*',    '*',    'moduleName=ci&methodName=checkBuildStatus', '同步Jenkins任务状态', 'zentao', -1, 'normal',   '0000-00-00 00:00:00');
