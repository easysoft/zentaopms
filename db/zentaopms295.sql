-- DROP TABLE IF EXISTS `zt_priv`;
CREATE TABLE IF NOT EXISTS `zt_priv` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `method` varchar(30) NOT NULL,
  `package` mediumint(8) unsigned NOT NULL,
  `edition` varchar(30) NOT NULL DEFAULT ',open,biz,max,',
  `vision` varchar(30) NOT NULL DEFAULT ',rnd,',
  `system` enum('0','1') NOT NULL DEFAULT '0',
  `order` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `priv` (`module`,`method`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_privmanager`;
CREATE TABLE IF NOT EXISTS `zt_privmanager` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `parent` mediumint(8) unsigned NOT NULL,
  `code` varchar(100) NOT NULL,
  `type` enum('view','module','package') NOT NULL DEFAULT 'package',
  `edition` varchar(30) NOT NULL DEFAULT ',open,biz,max,',
  `vision` varchar(30) NOT NULL DEFAULT ',rnd,',
  `order` mediumint(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_privlang`;
CREATE TABLE IF NOT EXISTS `zt_privlang` (
  `objectID` mediumint(8) unsigned NOT NULL,
  `objectType` enum('priv','manager') NOT NULL DEFAULT 'priv',
  `lang` varchar(30) NOT NULL,
  `key` varchar(100) NOT NULL,
  `value` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  UNIQUE KEY `objectlang` (`objectID`,`objectType`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_privrelation`;
CREATE TABLE IF NOT EXISTS `zt_privrelation` (
  `priv` mediumint(8) unsigned NOT NULL,
  `type` varchar(30) NOT NULL,
  `relationPriv` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `privrelation`(`priv`, `type`, `relationPriv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
