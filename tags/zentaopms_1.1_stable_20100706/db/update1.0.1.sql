-- 2010-07-01 task table.
ALTER TABLE `zt_task` ADD `mailto` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `statusCustom`;

--2010-07-05 userquery table.
CREATE TABLE IF NOT EXISTS `zt_userQuery` (
  `id` mediumint(8) unsigned NOT NULL auto_increment,
  `company` mediumint(8) unsigned NOT NULL default '0',
  `account` char(30) NOT NULL,
  `module` varchar(30) NOT NULL,
  `title` varchar(90) NOT NULL,
  `form` text NOT NULL,
  `sql` text NOT NULL,
  `mode` varchar(10) NOT NULL,
  PRIMARY KEY  (`id`),
  KEY `company` (`company`),
  KEY `account` (`account`),
  KEY `module` (`module`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
