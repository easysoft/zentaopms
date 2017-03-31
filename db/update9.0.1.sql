CREATE TABLE IF NOT EXISTS `zt_testsuite` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `type` varchar(20) NOT NULL,
  `addedBy` char(30) NOT NULL,
  `addedDate` datetime NOT NULL, 
  `lastEditedBy` char(30) NOT NULL,
  `lastEditedDate` datetime NOT NULL, 
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`) 
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `zt_suitecase` (
  `suite` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `suitecase` (`suite`,`case`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_case` ADD `lib` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `branch`;
ALTER TABLE `zt_case` ADD `fromCaseID` mediumint(8) unsigned NOT NULL AFTER `fromBug`;
ALTER TABLE `zt_case` ADD `reviewedBy` varchar(255) NOT NULL AFTER `openedDate`;
ALTER TABLE `zt_case` ADD `reviewedDate` date NOT NULL AFTER `reviewedBy`;
ALTER TABLE `zt_casestep` ADD `parent` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_casestep` ADD `type` varchar(10) NOT NULL DEFAULT 'step' AFTER `version`;
CREATE TABLE IF NOT EXISTS `zt_testreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `project` mediumint(8) unsigned NOT NULL,
  `tasks` varchar(255) NOT NULL,
  `builds` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  `owner` char(30) NOT NULL,
  `members` text NOT NULL,
  `stories` text NOT NULL,
  `bugs` text NOT NULL,
  `cases` text NOT NULL,
  `report` text NOT NULL,
  `objectType` varchar(20) NOT NULL,
  `objectID` mediumint(8) unsigned NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_bug` ADD `deadline` date NOT NULL AFTER `assignedDate`;
