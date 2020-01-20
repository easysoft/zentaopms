
ALTER TABLE `zt_repo`
ADD COLUMN `credentials` MEDIUMINT(8) NULL AFTER `password`;
ALTER TABLE `zt_repo`
ADD COLUMN `desc` TEXT NULL AFTER `lastSync`;

CREATE TABLE `zt_credentials` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL DEFAULT 'account',
  `name` varchar(50) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `publicKey` text,
  `privateKey` text,
  `passphrase` varchar(20) DEFAULT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `token` varchar(500) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8;

CREATE TABLE `zt_jenkins` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `serviceUrl` varchar(255) DEFAULT NULL,
  `token` varchar(255) DEFAULT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `credentials` mediumint(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

CREATE TABLE `zt_citask` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `repo` mediumint(8) unsigned NOT NULL,
  `jenkins` mediumint(8) unsigned NOT NULL,
  `jenkinsTask` varchar(500) NOT NULL,
  `buildType` varchar(255) NOT NULL,
  `triggerType` varchar(255) NOT NULL,
  `scheduleType` varchar(255) NOT NULL,
  `cronExpression` varchar(255) DEFAULT NULL,
  `scheduleDay` varchar(255) DEFAULT NULL,
  `scheduleTime` varchar(255) DEFAULT NULL,
  `scheduleInterval` mediumint(8) DEFAULT NULL,
  `tagKeywords` varchar(255) DEFAULT NULL,
  `commentKeywords` varchar(255) DEFAULT NULL,
  `desc` text,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8;

CREATE TABLE `zt_cibuild` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `citask` mediumint(8) unsigned NOT NULL,
  `status` varchar(255) NOT NULL,
  `logs` text,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('*/5',  '*',    '*',    '*',    '*',    'moduleName=ci&methodName=checkCibuild', '同步Jenkins任务状态', 'zentao', -1, 'normal',   '0000-00-00 00:00:00');
