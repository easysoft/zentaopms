-- DROP TABLE IF EXISTS `zt_repo`;
CREATE TABLE IF NOT EXISTS `zt_repo` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `path` varchar(255) NOT NULL,
  `prefix` varchar(100) NOT NULL,
  `encoding` varchar(20) NOT NULL,
  `SCM` varchar(10) NOT NULL,
  `client` varchar(100) NOT NULL,
  `commits` mediumint(8) unsigned NOT NULL,
  `account` varchar(30) NOT NULL,
  `password` varchar(30) NOT NULL,
  `encrypt` varchar(30) NOT NULL DEFAULT 'plain',
  `acl` text NOT NULL,
  `synced` tinyint(1) NOT NULL DEFAULT '0',
  `lastSync` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_repobranch`;
CREATE TABLE IF NOT EXISTS `zt_repobranch` (
  `repo` mediumint(8) unsigned NOT NULL,
  `revision` mediumint(8) unsigned NOT NULL,
  `branch` varchar(255) NOT NULL,
  UNIQUE KEY `repo_revision_branch` (`repo`,`revision`,`branch`),
  KEY `branch` (`branch`),
  KEY `revision` (`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_repohistory`;
CREATE TABLE IF NOT EXISTS `zt_repohistory` (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `repo` mediumint(9) NOT NULL,
  `revision` varchar(40) NOT NULL,
  `commit` mediumint(8) unsigned NOT NULL,
  `comment` text NOT NULL,
  `committer` varchar(100) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `repo` (`repo`),
  KEY `revision` (`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_repofiles`;
CREATE TABLE IF NOT EXISTS `zt_repofiles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repo` mediumint(8) unsigned NOT NULL,
  `revision` mediumint(8) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `parent` varchar(255) NOT NULL,
  `type` varchar(20) NOT NULL,
  `action` char(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `path` (`path`),
  KEY `parent` (`parent`),
  KEY `repo` (`repo`),
  KEY `revision` (`revision`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

ALTER TABLE `zt_bug` CHANGE `caseVersion` `caseVersion` smallint(6) NOT NULL DEFAULT 1 AFTER `case`;
ALTER TABLE `zt_bug` ADD `repo` mediumint(8) unsigned NOT NULL AFTER `result`;
ALTER TABLE `zt_bug` ADD `lines` varchar(10) COLLATE 'utf8_general_ci' NOT NULL AFTER `repo`;
ALTER TABLE `zt_bug` ADD `v1` varchar(40) COLLATE 'utf8_general_ci' NOT NULL AFTER `lines`;
ALTER TABLE `zt_bug` ADD `v2` varchar(40) COLLATE 'utf8_general_ci' NOT NULL AFTER `v1`;
ALTER TABLE `zt_bug` ADD `repoType` varchar(30) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' AFTER `v2`;
ALTER TABLE `zt_bug` ADD `entry` varchar(255) COLLATE 'utf8_general_ci' NOT NULL AFTER `repo`;
ALTER TABLE `zt_repobranch` ADD INDEX `revision` (`revision`);

DELETE FROM `zt_grouppriv` WHERE `module` = 'api' AND `method` = 'sql';
