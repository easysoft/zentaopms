ALTER TABLE `zt_webhook` CHANGE `projects` `executions` text NOT NULL;
ALTER TABLE `zt_webhook` CHANGE `executions` `executions` text NOT NULL;

ALTER TABLE `zt_repo` ADD `extra` char(30) COLLATE 'utf8_general_ci' NOT NULL AFTER `desc`;

ALTER TABLE `zt_user` ADD `pinyin` varchar(255) NOT NULL DEFAULT '' AFTER `realname`;
ALTER TABLE `zt_im_chat` ADD `lastMessage` int(11) unsigned NOT NULL DEFAULT 0 AFTER `lastActiveTime`;
ALTER TABLE `zt_im_conference` ADD `invitee` text NOT NULL AFTER `participants`;
ALTER TABLE `zt_im_message` CHANGE `id` `id` int(11) unsigned NOT NULL AUTO_INCREMENT;

CREATE TABLE IF NOT EXISTS `zt_im_message_backup` (
  `id` int(11) unsigned NOT NULL,
  `gid` char(40) NOT NULL DEFAULT '',
  `cgid` char(40) NOT NULL DEFAULT '',
  `user` varchar(30) NOT NULL DEFAULT '',
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `order` bigint(8) unsigned NOT NULL,
  `type` enum('normal', 'broadcast', 'notify') NOT NULL DEFAULT 'normal',
  `content` text NOT NULL DEFAULT '',
  `contentType` enum('text', 'plain', 'emotion', 'image', 'file', 'object', 'code') NOT NULL DEFAULT 'text',
  `data` text NOT NULL DEFAULT '',
  `deleted` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_im_message_index` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `tableName` char(64) NOT NULL,
  `start` int(11) unsigned NOT NULL,
  `end` int(11) unsigned NOT NULL,
  `startDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `endDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `chats` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `tableName` (`tableName`),
  KEY `start` (`start`),
  KEY `end` (`end`),
  KEY `startDate` (`startDate`),
  KEY `endDate` (`endDate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_im_chat_message_index` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gid` char(40) NOT NULL,
  `tableName` char(64) NOT NULL,
  `start` int(11) unsigned NOT NULL,
  `end` int(11) unsigned NOT NULL,
  `startDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `endDate` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `count` mediumint(8) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `chattable` (`gid`,`tableName`),
  KEY `start` (`start`),
  KEY `end` (`end`),
  KEY `startDate` (`startDate`),
  KEY `endDate` (`endDate`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_im_userdevice` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `user` mediumint(8) NOT NULL DEFAULT 0,
  `device` char(40) NOT NULL DEFAULT 'default',
  `lastLogin` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lastLogout` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `user` (`user`),
  KEY `lastLogin` (`lastLogin`),
  KEY `lastLogout` (`lastLogout`),
  UNIQUE KEY `userdevice` (`user`, `device`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `zt_im_userdevice` ADD `deviceID` char(40) NOT NULL DEFAULT '' AFTER `device`, ADD `token` char(64) NOT NULL DEFAULT '' AFTER `deviceID`, ADD `validUntil` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `token`;
ALTER TABLE `zt_im_conferenceaction` ADD `device` char(40) NOT NULL DEFAULT 'default' AFTER `date`;

ALTER TABLE `zt_im_chat` ADD `pinnedMessages` text NOT NULL DEFAULT '' AFTER `dismissDate`;
ALTER TABLE `zt_im_message` CHANGE `type` `type` enum('normal', 'broadcast', 'notify', 'bulletin') NOT NULL DEFAULT 'normal';

ALTER TABLE `zt_im_message` DROP COLUMN `order`;
ALTER TABLE `zt_im_message_backup` DROP COLUMN `order`;
