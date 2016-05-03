ALTER TABLE `zt_module` ADD `short` varchar(30) COLLATE 'utf8_general_ci' NOT NULL AFTER `owner`;
ALTER TABLE `zt_usertpl` ADD `public` enum('0','1') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '0';
CREATE TABLE `zt_block` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `account` char(30) NOT NULL,
  `module` varchar(20) NOT NULL,
  `title` varchar(100) NOT NULL,
  `source` varchar(20) NOT NULL,
  `block` varchar(20) NOT NULL,
  `params` text NOT NULL,
  `order` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `grid` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `hidden` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `accountModuleOrder` (`account`,`module`,`order`),
  KEY `block` (`account`,`module`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_task` ADD `color` char(7) COLLATE 'utf8_general_ci' NOT NULL AFTER `status`;
ALTER TABLE `zt_story` ADD `color` char(7) COLLATE 'utf8_general_ci' NOT NULL AFTER `status`;
ALTER TABLE `zt_bug` ADD `color` char(7) COLLATE 'utf8_general_ci' NOT NULL AFTER `status`;
ALTER TABLE `zt_case` ADD `color` char(7) COLLATE 'utf8_general_ci' NOT NULL AFTER `status`;
