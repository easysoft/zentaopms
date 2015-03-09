DROP TABLE IF EXISTS `zt_cron`;
CREATE TABLE `zt_cron` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `m` varchar(20) NOT NULL,
  `h` varchar(20) NOT NULL,
  `dom` varchar(20) NOT NULL,
  `mon` varchar(20) NOT NULL,
  `dow` varchar(20) NOT NULL,
  `command` text NOT NULL,
  `remark` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `buildin` tinyint(1) NOT NULL DEFAULT '0',
  `status` varchar(20) NOT NULL,
  `lastTime` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

TRUNCATE `zt_cron`;
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('*',    '*',    '*',    '*',    '*',    '',     '监控定时任务', 'zentao',       1,      'normal',       '0000-00-00 00:00:00'),
('30',   '23',   '*',    '*',    '*',    'moduleName=project&methodName=computeburn',    '更新燃尽图',   'zentao',       1,      'normal', '0000-00-00 00:00:00'),
('0',    '1',    '*',    '*',    '*',    'moduleName=report&methodName=remind',  '每日任务提醒', 'zentao',       1,      'normal', '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=svn&methodName=run',        '同步SVN',      'zentao',       1,      'normal',       '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=git&methodName=run',        '同步GIT',      'zentao',       1,      'normal', '0000-00-00 00:00:00'),
('30',   '0',    '*',    '*',    '*',    'moduleName=backup&methodName=backup',  '备份数据和附件',       'zentao',       1,      'normal', '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=mail&methodName=asyncSend', '异步发信',     'zentao',       1,      'normal', '0000-00-00 00:00:00');


DROP TABLE IF EXISTS `zt_mailqueue`;
CREATE TABLE `zt_mailqueue` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `toList` varchar(255) NOT NULL,
  `ccList` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `body` text NOT NULL,
  `addedBy` char(30) NOT NULL,
  `addedDate` datetime NOT NULL,
  `sendTime` datetime NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'wait',
  `failReason` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
