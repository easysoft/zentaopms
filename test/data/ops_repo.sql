DROP TABLE IF EXISTS `ops_repo`;
CREATE TABLE `ops_repo` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `spaceID` int(10) unsigned NOT NULL DEFAULT '0',
  `product` varchar(255) NOT NULL DEFAULT '',
  `projects` varchar(255) NOT NULL DEFAULT '',
  `name` varchar(255) NOT NULL DEFAULT '',
  `path` varchar(255) NOT NULL DEFAULT '',
  `prefix` varchar(100) NOT NULL DEFAULT '',
  `encoding` varchar(20) NOT NULL DEFAULT '',
  `SCM` varchar(10) NOT NULL DEFAULT '',
  `scmType` varchar(10) NOT NULL DEFAULT '',
  `client` varchar(100) NOT NULL DEFAULT '',
  `apiPath` varchar(255) NOT NULL DEFAULT '',
  `serviceHost` varchar(50) NOT NULL DEFAULT '',
  `serviceProject` varchar(100) NOT NULL DEFAULT '',
  `commits` int(10) unsigned NOT NULL DEFAULT '0',
  `account` varchar(30) NOT NULL DEFAULT '',
  `password` varchar(30) NOT NULL DEFAULT '',
  `encrypt` varchar(30) NOT NULL DEFAULT 'plain',
  `acl` text,
  `synced` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `lastSync` datetime DEFAULT NULL,
  `lastCommit` datetime DEFAULT NULL,
  `desc` text,
  `extra` varchar(30) NOT NULL DEFAULT '',
  `preMerge` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `job` int(10) unsigned NOT NULL DEFAULT '0',
  `fileServerUrl` text,
  `fileServerAccount` varchar(40) NOT NULL DEFAULT '',
  `fileServerPassword` varchar(100) NOT NULL DEFAULT '',
  `deleted` tinyint(3) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `ops_repo` (`id`, `spaceID`, `name`, `SCM`, `scmType`, `client`, `apiPath`, `acl`, `deleted`) VALUES
(1, 1, 'Git仓库1',    'Git',    'git', 'http://gitfox.test', 'http://gitfox.test/api/v1/', 'private', 0),
(2, 1, 'Git仓库2',    'Git',    'git', 'http://gitfox.test', 'http://gitfox.test/api/v1/', 'private', 0),
(3, 1, 'SVN仓库',     'SVN',    'svn', 'http://svn.test',    'http://svn.test/',           'private', 0),
(4, 1, 'Gitlab仓库',  'Gitlab', 'git', 'http://gitlab.test', 'http://gitlab.test/api/v4/', 'private', 0);

DROP TABLE IF EXISTS `ops_repouser`;
CREATE TABLE `ops_repouser` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `repo` int(10) unsigned NOT NULL DEFAULT '0',
  `account` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`),
  KEY `repo` (`repo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `ops_repouser` (`id`, `repo`, `account`) VALUES
(1, 1, 'admin'),
(2, 2, 'admin'),
(3, 3, 'admin'),
(4, 4, 'admin');
