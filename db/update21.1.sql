ALTER TABLE `zt_module` MODIFY COLUMN short varchar(60);
ALTER TABLE `zt_doc` ADD `templateDesc` text NULL AFTER `templateType`;

CREATE TABLE IF NOT EXISTS `zt_system` (
  `id` MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL DEFAULT '',
  `product` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  `integrated` ENUM('0','1') NOT NULL DEFAULT '0',
  `latestRelease` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
  `latestDate` DATETIME NULL,
  `children` VARCHAR(255) NOT NULL DEFAULT '',
  `status` ENUM('active','inactive') NOT NULL DEFAULT 'active',
  `desc` mediumtext NULL,
  `createdBy` VARCHAR(30) NOT NULL DEFAULT '',
  `createdDate` DATETIME NULL,
  `editedBy` VARCHAR(30) NOT NULL DEFAULT '',
  `editedDate` DATETIME NULL,
  `deleted` ENUM('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `idx_product` ON `zt_system`(`product`);
CREATE INDEX `idx_status` ON `zt_system`(`status`);

ALTER TABLE `zt_release` ADD `system` MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_release` ADD `releases` VARCHAR(255) NOT NULL DEFAULT '' AFTER `system`;

CREATE INDEX `idx_system` ON `zt_release`(`system`);

ALTER TABLE `zt_review` ADD `toAuditBy` varchar(30) not NULL default '' AFTER `lastAuditedDate`;
ALTER TABLE `zt_review` ADD `toAuditDate` datetime NULL AFTER `toAuditBy`;

ALTER TABLE `zt_design` ADD `storyVersion` smallint(6) UNSIGNED NOT NULL DEFAULT '1' AFTER `story`;

ALTER TABLE zt_dataview MODIFY `fields` text NULL;
ALTER TABLE zt_dataview MODIFY `objects` text NULL;
ALTER TABLE zt_dataview MODIFY `mode` varchar(50) NOT NULL DEFAULT 'builder';
ALTER TABLE zt_dataview ADD `driver` enum('mysql','duckdb') NOT NULL DEFAULT 'mysql' AFTER `code`;

CREATE TABLE IF NOT EXISTS `zt_pivotspec` (
  `pivot` mediumint(8) NOT NULL,
  `version` varchar(10) NOT NULL,
  `driver` enum('mysql', 'duckdb') NOT NULL default 'mysql',
  `mode` varchar(10) NOT NULL default 'builder',
  `name` text NULL,
  `desc` text NULL,
  `sql` text NULL,
  `fields` text NULL,
  `langs` text NULL,
  `vars` text NULL,
  `objects` text NULL,
  `settings` text NULL,
  `filters` text NULL,
  `createdDate` datetime NULL
) ENGINE = InnoDB DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX `idx_pivot_version` ON `zt_pivotspec`(`pivot`, `version`);

ALTER TABLE `zt_pivot` ADD `version` varchar(10) NOT NULL DEFAULT '0' AFTER `builtin`;
ALTER TABLE `zt_pivot` CHANGE `mode` `mode` varchar(10) NOT NULL DEFAULT 'builder';
ALTER TABLE `zt_pivot` CHANGE `sql` `sql` text NULL;
ALTER TABLE `zt_pivot` CHANGE `fields` `fields` text NULL;
ALTER TABLE `zt_pivot` CHANGE `langs` `langs` text NULL;
ALTER TABLE `zt_pivot` CHANGE `vars` `vars` text NULL;
ALTER TABLE `zt_pivot` CHANGE `objects` `objects` text NULL;
ALTER TABLE `zt_pivot` CHANGE `settings` `settings` text NULL;
ALTER TABLE `zt_pivot` CHANGE `filters` `filters` text NULL;

CREATE TABLE IF NOT EXISTS `zt_mark` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `objectType` varchar(50) NOT NULL DEFAULT '',
  `objectID` mediumint(8) unsigned NOT NULL DEFAULT 0,
  `version` varchar(50) NOT NULL DEFAULT '',
  `account` char(30) NOT NULL DEFAULT '',
  `date` datetime NULL,
  `mark` varchar(50) NOT NULL DEFAULT '',
  `extra` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `idx_object` ON `zt_mark`(`objectType`,`objectID`);
CREATE INDEX `idx_account` ON `zt_mark`(`account`);
