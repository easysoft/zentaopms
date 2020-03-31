ALTER TABLE `zt_story` CHANGE `type` `type` varchar(30) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'story' AFTER `keywords`;
UPDATE zt_story SET `type` = 'story' WHERE `type` = '';

CREATE TABLE IF NOT EXISTS `zt_jenkins` (
  `id` smallint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `account` varchar(30) DEFAULT NULL,
  `password` varchar(30) NOT NULL,
  `token` varchar(255) DEFAULT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_job` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `repo` mediumint(8) unsigned NOT NULL,
  `jkHost` mediumint(8) unsigned NOT NULL,
  `jkJob` varchar(500) NOT NULL,
  `triggerType` varchar(255) NOT NULL,
  `svnDir` varchar(255) NOT NULL,
  `atDay` varchar(20) DEFAULT NULL,
  `atTime` varchar(10) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `lastExec` datetime DEFAULT NULL,
  `lastStatus` varchar(255) DEFAULT NULL,
  `lastTag` varchar(255) DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_compile` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `job` mediumint(8) unsigned NOT NULL,
  `queue` mediumint(8) NOT NULL,
  `status` varchar(255) NOT NULL,
  `logs` text,
  `atTime` varchar(10) NOT NULL,
  `tag` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `updateDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('1',    '0',    '*',    '*',    '*',    'moduleName=ci&methodName=initQueue', '创建周期性任务', 'zentao', 1, 'normal',   '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=ci&methodName=checkBuildStatus', '同步Jenkins任务状态', 'zentao', 1, 'normal',   '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=ci&methodName=exec', '执行Jenkins任务', 'zentao', 1, 'normal',   '0000-00-00 00:00:00');

ALTER TABLE `zt_webhook` MODIFY COLUMN `type` varchar(15) NOT NULL DEFAULT 'default';
UPDATE `zt_webhook` SET `type` = 'dinggroup' WHERE `type` = 'dingding';
UPDATE `zt_webhook` SET `type` = 'dinguser' WHERE `type` = 'dingapi';
UPDATE `zt_webhook` SET `type` = 'wechatgroup' WHERE `type` = 'weixin';
UPDATE `zt_grouppriv` SET `method` = 'edit' WHERE `module` = 'repo' AND `method` = 'settings';
UPDATE `zt_grouppriv` SET `method` = 'showsynccommit' WHERE `module` = 'repo' AND `method` = 'showsynccomment';
UPDATE `zt_grouppriv` SET `method` = 'showsynccommit' WHERE `module` = 'repo' AND `method` = 'showSyncComment';

ALTER TABLE `zt_repo` ADD `desc` text NOT NULL AFTER `lastSync`;
