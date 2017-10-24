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
  PRIMARY KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_webhook`;
CREATE TABLE IF NOT EXISTS `zt_webhook` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `url` varchar(255) NOT NULL,
  `requestType` enum('post', 'get') NOT NULL DEFAULT 'get',
  `params` text NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_task` ADD `parent` INT(11)  NULL  DEFAULT '0'  AFTER `deleted`;
ALTER TABLE `zt_team` ADD `task` INT(11)  NULL  DEFAULT '0'  AFTER `project`;
ALTER TABLE `zt_team` ADD `estimate` DECIMAL(12,2)  UNSIGNED  NOT NULL  DEFAULT '0';
ALTER TABLE `zt_team` ADD `consumed` DECIMAL(12,2)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `estimate`;
ALTER TABLE `zt_team` ADD `left` DECIMAL(12,2)  UNSIGNED  NOT NULL  DEFAULT '0'  AFTER `consumed`;
ALTER TABLE `zt_team` ADD `order` TINYINT(3)  NOT NULL  DEFAULT '0' AFTER `left`;
ALTER TABLE `zt_team` CHANGE `days` `days` SMALLINT(5)  UNSIGNED  NOT NULL  DEFAULT '0';

ALTER TABLE `zt_team` DROP PRIMARY KEY;
ALTER TABLE `zt_team` ADD PRIMARY KEY (`project`, `task`, `account`);

ALTER TABLE `zt_user` ADD `score` DECIMAL(12,1)  NOT NULL  DEFAULT '0'  AFTER `deleted`;
ALTER TABLE `zt_user` ADD `score_level` DECIMAL(12,1)  NOT NULL  DEFAULT '0'  AFTER `score`;

CREATE TABLE `zt_score` (
  `id` bigint(12) unsigned NOT NULL AUTO_INCREMENT,
  `userID` int(11) NOT NULL DEFAULT '0',
  `account` varchar(30) NOT NULL,
  `model` varchar(30) NOT NULL,
  `method` varchar(30) NOT NULL,
  `score` decimal(12,1) NOT NULL DEFAULT '0.0',
  `type` enum('repeat','one') NOT NULL DEFAULT 'repeat',
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `account` (`account`),
  KEY `model` (`model`),
  KEY `method` (`method`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;