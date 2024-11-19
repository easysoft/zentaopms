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
