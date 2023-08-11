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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_instance`;
CREATE TABLE IF NOT EXISTS `zt_instance` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `space` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `solution` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `name` char(50) DEFAULT '',
  `appID` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `appName` char(50) NOT NULL DEFAULT '',
  `appVersion` char(20) NOT NULL DEFAULT '',
  `chart` char(50) NOT NULL DEFAULT '',
  `logo` varchar(255) DEFAULT '',
  `version` char(50) NOT NULL DEFAULT '',
  `desc` text,
  `introduction` varchar(500) DEFAULT '',
  `source` char(20) NOT NULL DEFAULT '',
  `channel` char(20) DEFAULT '',
  `k8name` char(64) NOT NULL DEFAULT '',
  `status` char(20) NOT NULL DEFAULT '',
  `pinned` enum('0', '1') NOT NULL DEFAULT '0',
  `domain` char(255) NOT NULL DEFAULT '',
  `smtpSnippetName` char(30) NULL DEFAULT '',
  `ldapSnippetName` char(30) NULL DEFAULT '',
  `ldapSettings` text,
  `dbSettings` text,
  `autoBackup` tinyint(1) NOT NULL DEFAULT 0,
  `backupKeepDays` int unsigned NOT NULL DEFAULT 1,
  `autoRestore` tinyint(1) NOT NULL DEFAULT 0,
  `env` text,
  `createdBy` char(30) NOT NULL DEFAULT '',
  `createdAt` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `space` (`space`),
  KEY `k8name` (`k8name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

