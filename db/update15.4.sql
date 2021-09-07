CREATE TABLE `zt_mr` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `gitlabID` mediumint(8) unsigned NOT NULL,
  `sourceProject` int unsigned NOT NULL,
  `sourceBranch` varchar(100) NOT NULL,
  `targetProject` int unsigned NOT NULL,
  `targetBranch` varchar(100) NOT NULL,
  `mriid` int unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `assignee` varchar(255) NOT NULL,
  `reviewer` varchar(255) NOT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `deleted` tinyint(1) NOT NULL,
  `status` char(30) NOT NULL,
  `mergeStatus` char(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

