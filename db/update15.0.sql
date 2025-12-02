-- DROP TABLE IF EXISTS `zt_storyreview`;
CREATE TABLE IF NOT EXISTS `zt_storyreview` (
  `story` mediumint(9) NOT NULL,
  `version` smallint(6) NOT NULL,
  `reviewer` varchar(30) NOT NULL,
  `result` varchar(30) NOT NULL,
  `reviewDate` datetime NOT NULL,
  UNIQUE KEY `story` (`story`,`version`,`reviewer`)
) ENGINE=MyISAM;

-- DROP TABLE IF EXISTS `zt_storyestimate`;
CREATE TABLE IF NOT EXISTS `zt_storyestimate` (
  `story` mediumint(9) NOT NULL,
  `round` smallint(6) NOT NULL,
  `estimate` text NOT NULL,
  `average` float(10,2) NOT NULL,
  `openedBy` varchar(30) NOT NULL,
  `openedDate` datetime NOT NULL,
  UNIQUE KEY `story` (`story`,`round`)
) ENGINE=MyISAM;

ALTER TABLE `zt_project` CHANGE `lifetime` `lifetime` char(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD `category` varchar(30) NOT NULL DEFAULT 'feature' AFTER `type`;
ALTER TABLE `zt_testtask` ADD `type` varchar(255) NOT NULL DEFAULT '' AFTER `build`;
ALTER TABLE `zt_testtask` ADD `testreport` mediumint(8) unsigned NOT NULL AFTER `status`;

UPDATE zt_project SET lifetime = '' WHERE lifetime = 'sprint';
