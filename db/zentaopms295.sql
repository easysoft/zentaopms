-- DROP TABLE IF EXISTS `zt_priv`;
CREATE TABLE IF NOT EXISTS `zt_priv` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `moduleName` varchar(30) NOT NULL,
  `methodName` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `package` mediumint(8) unsigned NOT NULL,
  `system` enum('0','1') NOT NULL DEFAULT '0',
  `order` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `priv` (`moduleName`,`methodName`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_privlang`;
CREATE TABLE IF NOT EXISTS `zt_privlang` (
  `priv` mediumint(8) unsigned NOT NULL,
  `lang` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  UNIQUE KEY `privlang` (`priv`,`lang`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_privpackage`;
CREATE TABLE IF NOT EXISTS `zt_privpackage` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `order` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
-- DROP TABLE IF EXISTS `zt_privrelation`;
CREATE TABLE IF NOT EXISTS `zt_privrelation` (
  `priv` mediumint(8) unsigned NOT NULL,
  `type` varchar(30) NOT NULL,
  `relationPriv` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `privrelation`(`priv`, `type`, `relationPriv`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
