CREATE TABLE IF NOT EXISTS `zt_dimension` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(90) NOT NULL,
  `code` varchar(45) NOT NULL,
  `desc` text NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL default '0',
  PRIMARY KEY (`id`),
  KEY `code` (`code`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `zt_dataview` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `group` mediumint(8) unsigned NOT NULL,
  `name` varchar(155) NOT NULL,
  `code` varchar(50) NOT NULL,
  `view` varchar(57) NOT NULL,
  `sql` text NOT NULL,
  `fields` mediumtext NOT NULL,
  `objects` mediumtext NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `zt_screen` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dimension` mediumint(8) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` mediumtext NOT NULL,
  `cover` mediumtext NOT NULL,
  `scheme` mediumtext NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` char(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

ALTER TABLE `zt_chart` ADD `dimension` mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `name`;
ALTER TABLE `zt_chart` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `type`;
ALTER TABLE `zt_chart` ADD `fields` mediumtext NOT NULL AFTER `filters`;
ALTER TABLE `zt_chart` ADD `sql` text NOT NULL AFTER `fields`;
ALTER TABLE `zt_chart` ADD `builtin` tinyint(1) unsigned NOT NULL AFTER `sql`;
ALTER TABLE `zt_chart` ADD `objects` mediumtext NOT NULL AFTER `builtin`;
ALTER TABLE `zt_chart` ADD `editedBy` varchar(30) NOT NULL AFTER `createdDate`;
ALTER TABLE `zt_chart` ADD `editedDate` datetime NOT NULL AFTER `editedBy`;
ALTER TABLE `zt_chart` MODIFY COLUMN `desc` text NOT NULL;

ALTER TABLE `zt_dashboard` ADD `dimension` int(8) NOT NULL default 0 AFTER `name`;
ALTER TABLE `zt_report`    ADD `dimension` int(8) NOT NULL default 0 AFTER `name`;

UPDATE `zt_chart`     SET `dimension` = 1 WHERE `dimension` = 0;
UPDATE `zt_report`    SET `dimension` = 1 WHERE `dimension` = 0;
UPDATE `zt_dashboard` SET `dimension` = 1 WHERE `dimension` = 0;

UPDATE `zt_grouppriv` SET `module` = 'dataview' WHERE `module` = 'dataset' AND `method` in ('create', 'browse', 'edit', 'delete');
DELETE FROM `zt_grouppriv` WHERE `module` = 'dataset' AND `method` = 'view';
