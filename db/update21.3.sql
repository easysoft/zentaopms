CREATE INDEX `objectType` ON `zt_stakeholder` (`objectType`);

CREATE TABLE IF NOT EXISTS `zt_autocache` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(30) NOT NULL DEFAULT '',
  `fields` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX `cache` ON `zt_autocache` (`code`, `fields`);
