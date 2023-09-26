-- DROP TABLE IF EXISTS `zt_market`;
CREATE TABLE IF NOT EXISTS `zt_market` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `industry` char(255) NOT NULL DEFAULT '',
  `scale` decimal(10, 2) NOT NULL DEFAULT '0',
  `maturity` char(255) NOT NULL DEFAULT '',
  `speed` varchar(255) NOT NULL DEFAULT '',
  `competition` char(255) NOT NULL DEFAULT '',
  `strategy` varchar(255) NOT NULL DEFAULT '',
  `desc` mediumtext NULL,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_marketreport`;
CREATE TABLE IF NOT EXISTS `zt_marketreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `market` mediumint(8) NOT NULL DEFAULT 0,
  `research` mediumint(8) NOT NULL DEFAULT 0,
  `maturity` varchar(30) NOT NULL DEFAULT '',
  `owner` varchar(30) NOT NULL DEFAULT '',
  `participants` char(255) NOT NULL DEFAULT '',
  `source` varchar(30) NOT NULL DEFAULT '',
  `desc` mediumtext NULL,
  `status` varchar(20)  NOT NULL DEFAULT '',
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NULL,
  `publishedBy` varchar(30) NOT NULL DEFAULT '',
  `publishedDate` datetime NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zt_project` ADD COLUMN `market` mediumint(8) NOT NULL DEFAULT 0;
ALTER TABLE `zt_project` ADD COLUMN `closedReason` varchar(20) NOT NULL DEFAULT '';

ALTER TABLE `zt_market` ADD COLUMN `ppm` varchar(20) NOT NULL DEFAULT '';
