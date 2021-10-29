-- DROP TABLE IF EXISTS `zt_kanbanlane`;
CREATE TABLE IF NOT EXISTS `zt_kanbanlane` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `execution` mediumint(8) NOT NULL DEFAULT '0',
  `type` char(30) NOT NULL,
  `extra` char(30) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `color` char(30) NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_kanbancolumn`;
CREATE TABLE IF NOT EXISTS `zt_kanbancolumn` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `lane` mediumint(8) NOT NULL DEFAULT '0',
  `parent` mediumint(8) NOT NULL DEFAULT '0',
  `type` char(30) NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT '',
  `color` char(30) NOT NULL,
  `limit` smallint(6) NOT NULL DEFAULT '-1',
  `order` mediumint(8) NOT NULL DEFAULT '0',
  `cards` text NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
