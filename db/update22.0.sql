CREATE TABLE `zt_rule` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `type` enum('global','group') NOT NULL DEFAULT 'global',
  `workflowGroup` varchar(255) DEFAULT NULL,
  `objectType` varchar(30) NOT NULL,
  `action` varchar(30) NOT NULL,
  `conditions` mediumtext DEFAULT NULL,
  `actions` mediumtext DEFAULT NULL,
  `method` enum('sync','async') NOT NULL DEFAULT 'sync',
  `createdBy` varchar(30) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `lastEdtiedBy` varchar(30) DEFAULT NULL,
  `lastEditedDate` date DEFAULT NULL,
  `lastRunTime` datetime DEFAULT NULL,
  `lastRunResult` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `objectType`  ON `zt_rule` (`objectType`);
CREATE INDEX `action`  ON `zt_rule` (`action`);
