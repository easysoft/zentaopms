DELETE FROM `zt_block` WHERE `type` IN ('news', 'patch', 'plugin', 'puglicclass');

ALTER TABLE `zt_block` ADD `dashboard` varchar(20) NOT NULL DEFAULT '' AFTER `account`;
ALTER TABLE `zt_block` CHANGE `block` `code` varchar(30) NOT NULL DEFAULT '' AFTER `module`;
ALTER TABLE `zt_block` ADD `width` enum ('1', '2', '3') NOT NULL DEFAULT '1' AFTER `code`;
ALTER TABLE `zt_block` MODIFY `height` smallint(5) UNSIGNED NOT NULL DEFAULT 3 AFTER `width`;
ALTER TABLE `zt_block` ADD `left` enum('0', '1', '2') NOT NULL DEFAULT '0' AFTER `height`;
ALTER TABLE `zt_block` ADD `top` smallint(5) UNSIGNED NOT NULL DEFAULT 0 AFTER `left`;
ALTER TABLE `zt_block` MODIFY `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `hidden`;

UPDATE `zt_block` SET `dashboard` = CONCAT(`module`, `type`);
UPDATE `zt_block` SET `module` = IF(`source` != '', `source`, `code`);
DROP INDEX account_vision_module_type_order ON `zt_block`;

ALTER TABLE `zt_block` DROP COLUMN `source`;
ALTER TABLE `zt_block` DROP COLUMN `type`;
ALTER TABLE `zt_block` DROP COLUMN `grid`;
ALTER TABLE `zt_block` DROP COLUMN `order`;

ALTER TABLE `zt_todo`  CHANGE `idvalue` `objectID` mediumint(8) unsigned default '0' NOT NULL AFTER `type`;
ALTER TABLE `zt_todo` CHANGE `config` `config` VARCHAR(1000) NOT NULL  DEFAULT '';

ALTER TABLE `zt_project` ADD `stageBy` enum('project', 'product') NOT NULL DEFAULT 'product' AFTER `division`;
UPDATE `zt_project` SET `stageBy` = 'project' WHERE `division` = '0';
UPDATE `zt_project` SET `stageBy` = 'product' WHERE `division` = '1';
ALTER TABLE `zt_project` DROP `division`;

ALTER TABLE `zt_bug` CHANGE `linkBug` `relatedBug` varchar(255) NOT NULL DEFAULT '';

ALTER TABLE `zt_product` ADD COLUMN `groups` text NULL AFTER `acl`;


-- DROP TABLE IF EXISTS `zt_space`;
CREATE TABLE `zt_space` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `k8space` char(64) NOT NULL,
  `owner` char(30) NOT NULL,
  `default` tinyint(1) NOT NULL DEFAULT 0,
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_instance`;
CREATE TABLE IF NOT EXISTS `zt_instance` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `space` mediumint(8) unsigned NOT NULL,
  `solution` mediumint(8) unsigned NOT NULL,
  `name` char(50),
  `appID` mediumint(8) unsigned NOT NULL,
  `appName` char(50) NOT NULL,
  `appVersion` char(20) NOT NULL,
  `chart` char(50) NOT NULL,
  `logo` varchar(255),
  `version` char(50) NOT NULL,
  `desc` text,
  `introduction` varchar(500),
  `source` char(20) NOT NULL,
  `channel` char(20),
  `k8name` char(64) NOT NULL,
  `status` char(20) NOT NULL,
  `pinned` enum('0', '1') NOT NULL DEFAULT '0',
  `domain` char(255) NOT NULL,
  `smtpSnippetName` char(30) NULL,
  `ldapSnippetName` char(30) NULL,
  `ldapSettings` text,
  `dbSettings` text,
  `autoBackup` tinyint(1) NOT NULL DEFAULT 0,
  `backupKeepDays` int unsigned NOT NULL DEFAULT 1,
  `autoRestore` tinyint(1) NOT NULL DEFAULT 0,
  `env` text,
  `createdBy` char(30) NOT NULL,
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `space` (`space`),
  KEY `k8name` (`k8name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_solution`;
CREATE TABLE IF NOT EXISTS `zt_solution` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` char(50),
  `appID` mediumint(8) unsigned NOT NULL,
  `appName` char(50) NOT NULL,
  `appVersion` char(20) NOT NULL,
  `version` char(50) NOT NULL,
  `chart` char(50) NOT NULL,
  `cover` varchar(255),
  `desc` text,
  `introduction` varchar(500),
  `source` char(20) NOT NULL,
  `channel` char(20),
  `components` text,
  `status` char(20) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  `createdBy` char(30) NOT NULL,
  `createdAt` datetime NOT NULL,
  `updatedDate` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `zt_session` (
    `id` varchar(32) NOT NULL,
    `data` mediumtext,
    `timestamp` int(10) unsigned DEFAULT NULL,
    PRIMARY KEY (`id`),
    KEY `timestamp` (`timestamp`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
