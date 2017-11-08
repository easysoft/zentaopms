-- DROP TABLE IF EXISTS `zt_entry`;
CREATE TABLE IF NOT EXISTS `zt_entry` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `key` varchar(32) NOT NULL,
  `ip` varchar(100) NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_webhook`;
CREATE TABLE IF NOT EXISTS `zt_webhook` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL DEFAULT 'default',
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `contentType` varchar(30) NOT NULL DEFAULT 'application/json',
  `sendType` enum('sync','async') NOT NULL DEFAULT 'sync',
  `products` text NOT NULL,
  `projects` text NOT NULL,
  `params` varchar(100) NOT NULL,
  `actions` text NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_webhookdatas`;
CREATE TABLE IF NOT EXISTS `zt_webhookdatas` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `webhook` mediumint(8) unsigned NOT NULL,
  `action` mediumint(8) unsigned NOT NULL,
  `data` text NOT NULL,
  `status` enum('wait', 'sended') NOT NULL DEFAULT 'wait',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY `id` (`id`),
  UNIQUE KEY `uniq` (`webhook`, `action`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_log`;
CREATE TABLE IF NOT EXISTS `zt_log` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(30) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `action` mediumint(8) unsigned NOT NULL,
  `date` datetime NOT NULL,
  `url` varchar(255) NOT NULL,
  `contentType` varchar(30) NOT NULL,
  `data` text NOT NULL,
  `result` text  NOT NULL,
  PRIMARY KEY `id` (`id`),
  KEY `objectType` (`objectType`),
  KEY `obejctID` (`objectID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_score`;
CREATE TABLE `zt_score` (
  `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL DEFAULT '',
  `method` varchar(30) NOT NULL,
  `desc` varchar(250) NOT NULL DEFAULT '',
  `before` int(11) NOT NULL DEFAULT '0',
  `score` int(11) NOT NULL DEFAULT '0',
  `after` int(11) NOT NULL DEFAULT '0',
  `time` datetime NOT NULL,
  `objectID` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `model` (`module`),
  KEY `method` (`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_product` ADD `line` mediumint(8) NOT NULL AFTER `code`;

ALTER TABLE `zt_projectstory` ADD `order` smallint(6) unsigned NOT NULL;

ALTER TABLE `zt_task` ADD `parent` mediumint(8) NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `zt_team` ADD `task` mediumint(8) NOT NULL DEFAULT '0' AFTER `project`;
ALTER TABLE `zt_team` ADD `estimate` DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT '0';
ALTER TABLE `zt_team` ADD `consumed` DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT '0' AFTER `estimate`;
ALTER TABLE `zt_team` ADD `left` DECIMAL(12,2) UNSIGNED NOT NULL DEFAULT '0' AFTER `consumed`;
ALTER TABLE `zt_team` ADD `order` TINYINT(3) NOT NULL DEFAULT '0' AFTER `left`;
ALTER TABLE `zt_team` DROP PRIMARY KEY;
ALTER TABLE `zt_team` ADD PRIMARY KEY (`project`, `task`, `account`);

ALTER TABLE `zt_user` ADD `score` INT(11) NOT NULL DEFAULT '0' AFTER `ranzhi`;
ALTER TABLE `zt_user` ADD `scoreLevel` INT(11) NOT NULL DEFAULT '0' AFTER `score`;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('*/5', '*', '*', '*', '*', 'moduleName=webhook&methodName=asyncSend', '异步发送Webhook', 'zentao', 1, 'normal', '0000-00-00 00:00:00');
