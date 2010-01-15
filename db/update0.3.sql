-- 20100114: 重新修改build的结构。
DROP TABLE IF EXISTS `zt_build`;
CREATE TABLE IF NOT EXISTS `zt_build` (
  `id` mediumint(8) unsigned NOT NULL default '0',
  `product` mediumint(8) unsigned NOT NULL default '0',
  `project` mediumint(8) unsigned NOT NULL default '0',
  `name` char(30) NOT NULL default '',
  `scmPath` char(255) NOT NULL,
  `filePath` char(255) NOT NULL,
  `buildDate` int(10) unsigned NOT NULL default '0',
  `builder` char(30) NOT NULL default '',
  `desc` char(255) NOT NULL default '',
  UNIQUE KEY `name` (`name`)
  ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- 20100115: 重新修改release的结构。
DROP TABLE IF EXISTS `zt_release`;
CREATE TABLE IF NOT EXISTS `zt_release` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `product` mediumint(8) unsigned NOT NULL default '0',
  `build` mediumint(8) unsigned NOT NULL,
  `name` char(30) NOT NULL default '',
  `date` date NOT NULL,
  `desc` text NOT NULL,
  PRIMARY KEY  (`id`),
  UNIQUE KEY `name` (`name`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

-- 20100115: fix bug 14 
ALTER TABLE `zt_productPlan` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
