ALTER TABLE `zt_testTask` ADD  `pri` TINYINT( 3 ) UNSIGNED NOT NULL DEFAULT  '0' AFTER  `owner`;
ALTER TABLE `zt_user` ADD `role` CHAR( 10 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '' AFTER `password`;
ALTER TABLE `zt_product` CHANGE `QM` `QD` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_project` CHANGE `QM` `QD` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '',
CHANGE `RM` `RD` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '';
ALTER TABLE `zt_product` CHANGE `RM` `RD` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

CREATE TABLE IF NOT EXISTS `zt_userContact` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `company` mediumint(8) unsigned NOT NULL,
  `account` char(30) NOT NULL,
  `listName` varchar(60) NOT NULL,
  `userList` text NOT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=MyISAM  DEFAULT CHARSET=utf8;
