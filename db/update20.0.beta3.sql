INSERT INTO `zt_grouppriv` (`group`, `module`, `method`) VALUES 
(1,  'product', 'dashboard'),
(2,  'product', 'dashboard'),
(3,  'product', 'dashboard'),
(4,  'product', 'dashboard'),
(5,  'product', 'dashboard'),
(6,  'product', 'dashboard'),
(7,  'product', 'dashboard'),
(8,  'product', 'dashboard'),
(9,  'product', 'dashboard'),
(10, 'product', 'dashboard'),
(11, 'product', 'dashboard');

REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'program', '', 'unitList', 'CNY,USD');
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'program', '', 'mainCurrency', 'CNY');

ALTER TABLE `zt_project` DROP `storyConcept`;
ALTER TABLE `zt_product` DROP `storyConcept`;

ALTER TABLE `zt_user` CHANGE `avatar` `avatar` text NOT NULL AFTER `commiter`;

-- DROP TABLE IF EXISTS `zt_searchindex`;
CREATE TABLE IF NOT EXISTS `zt_searchindex` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` char(20) NOT NULL,
  `objectID` mediumint(9) NOT NULL,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `addedDate` datetime NOT NULL,
  `editedDate` datetime NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `object` (`objectType`,`objectID`),
  KEY `addedDate` (`addedDate`),
  FULLTEXT KEY `content` (`content`),
  FULLTEXT KEY `title` (`title`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- DROP TABLE IF EXISTS `zt_searchdict`;
CREATE TABLE IF NOT EXISTS `zt_searchdict` (
  `key` smallint(5) unsigned NOT NULL,
  `value` char(3) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) VALUES
(1, 'search', 'index'),
(2, 'search', 'index'),
(3, 'search', 'index'),
(4, 'search', 'index'),
(5, 'search', 'index'),
(6, 'search', 'index'),
(7, 'search', 'index'),
(8, 'search', 'index'),
(9, 'search', 'index'),
(10, 'search', 'index'),
(1, 'search', 'buildIndex');
