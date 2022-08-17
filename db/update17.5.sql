CREATE TABLE `zt_taskteam` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `task` mediumint(8) unsigned NOT NULL,
  `account` char(30) NOT NULL,
  `estimate` decimal(12,2) NOT NULL,
  `consumed` decimal(12,2) NOT NULL,
  `left` decimal(12,2) NOT NULL,
  `order` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `zt_taskteam` ADD INDEX `task` (`task`);
