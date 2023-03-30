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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `zt_chart_back` SELECT * FROM `zt_chart`;

ALTER TABLE `zt_chart` MODIFY `fields` mediumtext NOT NULL;
ALTER TABLE `zt_chart` MODIFY `group` varchar(255) NOT NULL;
ALTER TABLE `zt_chart` ADD `stage` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `sql`;
ALTER TABLE `zt_chart` ADD `langs` text NOT NULL AFTER `fields`;
ALTER TABLE `zt_chart` ADD `step` tinyint(1) unsigned NOT NULL AFTER `filters`;

ALTER TABLE `zt_dataview` ADD `langs` text NOT NULL AFTER `fields`;

ALTER TABLE `zt_screen` ADD `status` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `scheme`;
ALTER TABLE `zt_screen` ADD `builtin` enum('0', '1') NOT NULL DEFAULT '0' AFTER `status`;

UPDATE `zt_screen` SET `builtin` = '1', `status` = 'published';

UPDATE `zt_grouppriv` SET `module` = 'dataview' WHERE `module` = 'dataset' AND `method` in ('create', 'browse', 'edit', 'delete');
UPDATE `zt_grouppriv` SET `module` = 'screen' WHERE `module` = 'dashboard';
UPDATE `zt_grouppriv` SET `module` = 'pivot', `method` = 'create') WHERE `module` = 'report' AND `method` = 'custom';
UPDATE `zt_grouppriv` SET `module` = 'pivot', `method` = 'delete') WHERE `module` = 'report' AND `method` = 'deleteReport';
UPDATE `zt_grouppriv` SET `module` = 'pivot', `method` = 'edit') WHERE `module` = 'report' AND `method` = 'editReport';
UPDATE `zt_grouppriv` SET `module` = 'pivot', `method` = 'preview') WHERE `module` = 'report' AND `method` = 'show';
UPDATE `zt_grouppriv` SET `module` = 'pivot', `method` = 'design') WHERE `module` = 'report' AND `method` = 'useReport';
UPDATE `zt_grouppriv` SET `module` = 'pivot', `method` = 'export') WHERE `module` = 'report' AND `method` = 'crystalExport';

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`) SELECT `group`, 'pivot', 'create' FROM `zt_grouppriv` WHERE `module` = 'report' AND `method` = 'saveReport';
DELETE FROM `zt_grouppriv` WHERE `module` = 'report' AND `method` = 'saveReport';
DELETE FROM `zt_grouppriv` WHERE `module` = 'dataset' AND `method` = 'view';

CREATE TABLE `zt_pivot`  (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dimension` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `group` varchar(255) NOT NULL,
  `name` text NOT NULL,
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
