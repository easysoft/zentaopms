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

INSERT INTO `ops_repohistory` (`id`, `repo`, `revision`, `commit`, `comment`, `committer`, `time`) VALUES
(1, 1, 'sha-a', 1, 'fix bug',    'admin', '2024-01-01 00:00:00'),
(2, 1, 'sha-b', 2, 'feat: init', 'admin', '2024-01-02 00:00:00'),
(3, 1, 'sha-c', 3, 'chore',      'admin', '2024-01-03 00:00:00');
