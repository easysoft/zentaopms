-- DROP TABLE IF EXISTS `zt_stage`;
CREATE TABLE IF NOT EXISTS `zt_stage` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `percent` varchar(255) NOT NULL,
  `type` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0', 
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_design`;
CREATE TABLE IF NOT EXISTS `zt_design` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` varchar(255) NOT NULL,
  `product` varchar(255) NOT NULL,
  `commit` text NOT NULL,
  `commitedBy` varchar(30) NOT NULL,
  `execution` mediumint(8) unsigned NOT NULL DEFAULT '0', 
  `name` varchar(255) NOT NULL,
  `status` varchar(30) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `assignedTo` varchar(30) NOT NULL,
  `assignedBy` varchar(30) NOT NULL,
  `assignedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0', 
  `story` char(30) NOT NULL,
  `desc` text NOT NULL,
  `version` smallint(6) NOT NULL,
  `type` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_designspec`;
CREATE TABLE IF NOT EXISTS `zt_designspec` (
  `design` mediumint(8) NOT NULL,
  `version` smallint(6) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `files` varchar(255) NOT NULL,
  UNIQUE KEY `design` (`design`,`version`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_weeklyreport`;
CREATE TABLE IF NOT EXISTS `zt_weeklyreport`(
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` mediumint(8) unsigned NOT NULL,
  `weekStart` date NOT NULL,
  `pv` float(9,2) NOT NULL,
  `ev` float(9,2) NOT NULL,
  `ac` float(9,2) NOT NULL,
  `sv` float(9,2) NOT NULL,
  `cv` float(9,2) NOT NULL,
  `staff` smallint(5) unsigned NOT NULL,
  `progress` varchar(255) NOT NULL,
  `workload` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `week` (`project`,`weekStart`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_holiday`;
CREATE TABLE IF NOT EXISTS `zt_holiday` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL DEFAULT '',
  `type` enum('holiday', 'working') NOT NULL DEFAULT 'holiday',
  `desc` text NOT NULL,
  `year` char(4) NOT NULL,
  `begin` date NOT NULL,
  `end` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `year` (`year`),
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
