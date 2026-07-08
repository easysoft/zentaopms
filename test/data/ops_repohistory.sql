DROP TABLE IF EXISTS `ops_repohistory`;
CREATE TABLE `ops_repohistory` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repo` int(10) unsigned NOT NULL DEFAULT '0',
  `revision` varchar(40) NOT NULL DEFAULT '',
  `commit` int(10) unsigned NOT NULL DEFAULT '0',
  `comment` text,
  `committer` varchar(100) NOT NULL DEFAULT '',
  `time` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `repo` (`repo`),
  KEY `revision` (`revision`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
