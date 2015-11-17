ALTER TABLE `zt_action` CHANGE `extra` `extra` text COLLATE 'utf8_general_ci' NOT NULL AFTER `comment`;
ALTER TABLE `zt_release` ADD `leftBugs` text COLLATE 'utf8_general_ci' NOT NULL AFTER `bugs`;
ALTER TABLE `zt_release` ADD `status` varchar(20) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'normal' AFTER `desc`;
ALTER TABLE `zt_product` ADD `type` varchar(30) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'normal' AFTER `code`;

ALTER TABLE `zt_projectproduct` ADD `branch` mediumint(8) unsigned NOT NULL;
ALTER TABLE `zt_productplan` ADD `branch` mediumint(8) unsigned NOT NULL AFTER `product`;
ALTER TABLE `zt_build` ADD `branch` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `product`;
ALTER TABLE `zt_release` ADD `branch` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `product`;
ALTER TABLE `zt_bug` ADD `branch` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `product`;
ALTER TABLE `zt_case` ADD `branch` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `product`;
ALTER TABLE `zt_module` ADD `branch` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `root`;
ALTER TABLE `zt_story` ADD `branch` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `product`;

CREATE TABLE IF NOT EXISTS `zt_branch` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `product` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE TABLE IF NOT EXISTS `zt_storystage` (
  `story` mediumint(8) unsigned NOT NULL,
  `branch` mediumint(8) unsigned NOT NULL,
  `stage` varchar(50) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
ALTER TABLE `zt_story` ADD INDEX `product` (`product`, `module`, `type`, `pri`), DROP INDEX `product`;
ALTER TABLE `zt_story` CHANGE `plan` `plan` text COLLATE 'utf8_general_ci' NOT NULL AFTER `module`;
UPDATE `zt_story` SET `plan`='' WHERE `plan`='0';

ALTER TABLE `zt_release` DROP INDEX `name`;
ALTER TABLE `zt_user` ADD `ranzhi` char(30) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' AFTER `locked`;
