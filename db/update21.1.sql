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
