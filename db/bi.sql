/* Upgrade 18.0.beta2 will execute sql, but the installation does not. */
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
)

CREATE TABLE `zt_chart_back` SELECT * FROM `zt_chart`;

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
/* */

ALTER TABLE `zt_chart` MODIFY `group` varchar(255) NOT NULL;
ALTER TABLE `zt_chart` ADD `stage` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `sql`;
ALTER TABLE `zt_chart` ADD `langs` text NOT NULL AFTER `fields`;
ALTER TABLE `zt_chart` ADD `step` tinyint(1) unsigned NOT NULL AFTER `filters`;

ALTER TABLE `zt_dataview` ADD `langs` text NOT NULL AFTER `fields`;

ALTER TABLE `zt_screen` ADD `status` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `scheme`;
ALTER TABLE `zt_screen` ADD `builtin` enum('0', '1') NOT NULL DEFAULT '0' AFTER `status`;

UPDATE `zt_screen` SET `builtin` = '1', `status` = 'published';

REPLACE `zt_grouppriv` SET `module` = 'screen' where `module` = 'dashboard';
REPLACE `zt_grouppriv` SET `module` = 'pivot', `method` = 'create' where `module` = 'report' and (`method` = 'custom' or `method` = 'saveReport') ;
REPLACE `zt_grouppriv` SET `module` = 'pivot', `method` = 'delete' where `module` = 'report' and `method` = 'deleteReport';
REPLACE `zt_grouppriv` SET `module` = 'pivot', `method` = 'edit' where `module` = 'report' and `method` = 'editReport';
REPLACE `zt_grouppriv` SET `module` = 'pivot', `method` = 'preview' where `module` = 'report' and `method` = 'show';
REPLACE `zt_grouppriv` SET `module` = 'pivot', `method` = 'design' where `module` = 'report' and `method` = 'useReport';
REPLACE `zt_grouppriv` SET `module` = 'pivot', `method` = 'export' where `module` = 'report' and `method` = 'crystalExport';

CREATE TABLE `zt_pivot`  (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dimension` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `group` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `desc` text NOT NULL,
  `sql` mediumtext NOT NULL,
  `fields` mediumtext NOT NULL,
  `langs` mediumtext NOT NULL,
  `vars` mediumtext NOT NULL,
  `objects` mediumtext NULL,
  `settings` mediumtext NOT NULL,
  `filters` mediumtext NOT NULL,
  `step` tinyint(1) unsigned NOT NULL,
  `stage` enum('draft','published') NOT NULL DEFAULT 'draft',
  `builtin` enum('0', '1') NOT NULL DEFAULT '0',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY(`dimension`),
  KEY(`group`)
) ENGINE = InnoDB DEFAULT CHARSET=utf8;
