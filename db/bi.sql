ALTER TABLE `zt_chart` CHANGE `builtin` `builtin` enum('0','1') NOT NULL DEFAULT '0';
ALTER TABLE `zt_chart` ADD `stage` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `sql`;
ALTER TABLE `zt_chart` ADD `langs` text NOT NULL AFTER `fields`;
ALTER TABLE `zt_chart` ADD `step` tinyint(1) unsigned NOT NULL AFTER `filters`;
ALTER TABLE `zt_chart` DROP `dataset`;

ALTER TABLE `zt_screen` ADD `status` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `scheme`;
ALTER TABLE `zt_screen` ADD `builtin` enum('0', '1') NOT NULL DEFAULT '0' AFTER `status`;

UPDATE `zt_screen` SET `builtin` = '1', `status` = 'published';

ALTER TABLE `zt_dataview` ADD `langs` text NOT NULL AFTER `fields`;

CREATE TABLE `zt_pivot`  (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `dimension` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `group` mediumint(8) unsigned NOT NULL DEFAULT 0,
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
