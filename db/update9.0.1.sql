CREATE TABLE `zt_testsuite` (
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
CREATE TABLE `zt_suitecase` (
  `suite` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL,
  `case` mediumint(8) unsigned NOT NULL,
  `version` smallint(5) unsigned NOT NULL,
  UNIQUE KEY `suitecase` (`suite`,`case`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_case` ADD `lib` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `branch`;
ALTER TABLE `zt_case` ADD `fromLib` mediumint(8) unsigned NOT NULL AFTER `fromBug`;
ALTER TABLE `zt_case` ADD `reviewedBy` varchar(255) NOT NULL AFTER `openedDate`;
ALTER TABLE `zt_case` ADD `reviewedDate` date NOT NULL AFTER `reviewedBy`;
