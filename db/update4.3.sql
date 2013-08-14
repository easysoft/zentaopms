CREATE TABLE IF NOT EXISTS `zt_lang` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `lang` varchar(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `section` varchar(30) NOT NULL,
  `key` varchar(60) NOT NULL,
  `value` text NOT NULL,
  `system` enum('0','1') NOT NULL default '1',
  PRIMARY KEY  (`id`),
  UNIQUE KEY `lang` (`lang`,`module`,`section`,`key`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
