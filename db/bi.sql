ALTER TABLE `zt_chart` ADD `stage` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `group`;
CREATE TABLE `zt_pivot`  (
  `id` mediumint(9) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `dimension` int(8) NOT NULL DEFAULT 0,
  `type` varchar(20) NOT NULL,
  `group` mediumint(8) NOT NULL DEFAULT 0,
  `stage` enum('draft','published') NOT NULL DEFAULT 'draft',
  `dataset` varchar(30) NOT NULL,
  `desc` text NOT NULL,
  `settings` mediumtext NOT NULL,
  `filters` mediumtext NOT NULL,
  `fields` mediumtext NOT NULL,
  `sql` text NOT NULL,
  `builtin` tinyint(1) NOT NULL,
  `objects` mediumtext NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime(0) NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime(0) NOT NULL,
  `deleted` tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE = MyISAM  DEFAULT CHARSET=utf8;

ALTER TABLE `zt_screen` ADD `status` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `scheme`;
ALTER TABLE `zt_screen` ADD `builtin` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `status`;
UPDATE `zt_screen` SET builtin = '1', status = 'published';
