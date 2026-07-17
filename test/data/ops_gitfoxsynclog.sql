DROP TABLE IF EXISTS `zt_ops_gitfoxsynclog`;
CREATE TABLE `zt_ops_gitfoxsynclog` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repoID` int(10) unsigned NOT NULL DEFAULT '0',
  `times` int(10) unsigned NOT NULL DEFAULT '0',
  `lastSync` datetime DEFAULT NULL,
  `status` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `repoID` (`repoID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
