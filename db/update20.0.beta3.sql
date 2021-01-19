REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) VALUES 
(1,  'product',      'dashboard'),
(1,  'projectstory', 'story'),
(1,  'projectstory', 'track'),
(1,  'projectstory', 'linkStory'),
(1,  'my',           'preference'),
(1,  'my',           'uploadAvatar'),
(1,  'user',         'cropAvatar'),
(2,  'product',      'dashboard'),
(2,  'projectstory', 'story'),
(2,  'projectstory', 'track'),
(2,  'my',           'preference'),
(2,  'my',           'uploadAvatar'),
(2,  'user',         'cropAvatar'),
(3,  'product',      'dashboard'),
(3,  'projectstory', 'story'),
(3,  'projectstory', 'track'),
(3,  'my',           'preference'),
(3,  'my',           'uploadAvatar'),
(3,  'user',         'cropAvatar'),
(4,  'product',      'dashboard'),
(4,  'projectstory', 'story'),
(4,  'projectstory', 'track'),
(4,  'my',           'preference'),
(4,  'my',           'uploadAvatar'),
(4,  'user',         'cropAvatar'),
(5,  'product',      'dashboard'),
(5,  'projectstory', 'story'),
(5,  'projectstory', 'track'),
(5,  'projectstory', 'linkStory'),
(5,  'my',           'preference'),
(5,  'my',           'uploadAvatar'),
(5,  'user',         'cropAvatar'),
(6,  'product',      'dashboard'),
(6,  'projectstory', 'story'),
(6,  'projectstory', 'track'),
(6,  'my',           'preference'),
(6,  'my',           'uploadAvatar'),
(6,  'user',         'cropAvatar'),
(7,  'product',      'dashboard'),
(7,  'projectstory', 'story'),
(7,  'projectstory', 'track'),
(7,  'projectstory', 'linkStory'),
(7,  'my',           'preference'),
(7,  'my',           'uploadAvatar'),
(7,  'user',         'cropAvatar'),
(8,  'product',      'dashboard'),
(8,  'projectstory', 'story'),
(8,  'projectstory', 'track'),
(8,  'my',           'preference'),
(8,  'my',           'uploadAvatar'),
(8,  'user',         'cropAvatar'),
(9,  'product',      'dashboard'),
(9,  'projectstory', 'story'),
(9,  'projectstory', 'track'),
(9,  'projectstory', 'linkStory'),
(9,  'my',           'preference'),
(9,  'my',           'uploadAvatar'),
(9,  'user',         'cropAvatar'),
(10, 'product',      'dashboard'),
(10, 'projectstory', 'story'),
(10, 'projectstory', 'track'),
(10, 'my',           'preference'),
(10, 'my',           'uploadAvatar'),
(10, 'user',         'cropAvatar'),
(11, 'product',      'dashboard'),
(11, 'projectstory', 'story'),
(11, 'projectstory', 'track'),
(11, 'my',           'preference'),
(11, 'my',           'uploadAvatar'),
(11, 'user',         'cropAvatar');

REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'program', '', 'unitList', 'CNY,USD');
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'program', '', 'mainCurrency', 'CNY');

ALTER TABLE `zt_project` DROP `storyConcept`;
ALTER TABLE `zt_product` DROP `storyConcept`;

ALTER TABLE `zt_user` CHANGE `avatar` `avatar` text NOT NULL AFTER `commiter`;
ALTER TABLE `zt_project` CHANGE `budgetUnit` `budgetUnit` char(30) NOT NULL  DEFAULT 'CNY' AFTER `budget`;

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
