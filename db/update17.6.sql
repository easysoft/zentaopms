CREATE TABLE `zt_taskteam` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `task` mediumint(8) unsigned NOT NULL,
  `account` char(30) NOT NULL,
  `estimate` decimal(12,2) NOT NULL,
  `consumed` decimal(12,2) NOT NULL,
  `left` decimal(12,2) NOT NULL,
  `transfer` char(30) NOT NULL,
  `status` enum('wait','doing','done') NOT NULL DEFAULT 'wait',
  `order` tinyint(3) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `task` (`task`)
) ENGINE=InnoDB;
ALTER TABLE `zt_taskestimate` ADD `order` tinyint unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_effort` ADD `order` tinyint unsigned NOT NULL DEFAULT '0' AFTER `end`;
ALTER TABLE `zt_storyspec` ADD `files` text NOT NULL AFTER `verify`;
ALTER TABLE `zt_block` CHANGE `block` `block` varchar(30) NOT NULL AFTER `source`;
