-- DROP TABLE IF EXISTS `zt_metric`;
CREATE TABLE IF NOT EXISTS `zt_metric` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `purpose` varchar(50) NOT NULL DEFAULT '',
  `scope` char(30) NOT NULL DEFAULT '',
  `object` char(30) NOT NULL DEFAULT '',
  `stage` enum('wait','released') NULL DEFAULT 'wait',
  `type` enum('php', 'sql') NULL DEFAULT 'php',
  `name` varchar(90) NOT NULL DEFAULT '',
  `code` varchar(90) NOT NULL DEFAULT '',
  `unit` varchar(10) NOT NULL DEFAULT '',
  `collector` text,
  `desc` text,
  `definition` text,
  `when` varchar(30) NOT NULL DEFAULT '',
  `event` varchar(30) NOT NULL DEFAULT '',
  `cronCFG` varchar(30) NOT NULL DEFAULT '',
  `time` varchar(30) NOT NULL DEFAULT '',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime DEFAULT NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime DEFAULT NULL,
  `implementedBy` varchar(30) NOT NULL DEFAULT '',
  `implementedDate` datetime DEFAULT NULL,
  `delistedBy` varchar(30) NOT NULL DEFAULT '',
  `delistedDate` datetime DEFAULT NULL,
  `builtin` enum('0', '1') NOT NULL DEFAULT '0',
  `fromID` mediumint unsigned NOT NULL DEFAULT 0,
  `order` mediumint unsigned NOT NULL DEFAULT '0',
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`)
) ENGINE=InnoDB;

-- DROP TABLE IF EXISTS `zt_metriclib`;
CREATE TABLE IF NOT EXISTS `zt_metriclib` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `metricID`   mediumint    NOT NULL DEFAULT 0,
  `metricCode` varchar(100) NOT NULL DEFAULT '',
  `system`     char(30)     NOT NULL DEFAULT '0',
  `program`    char(30)     NOT NULL DEFAULT '',
  `project`    char(30)     NOT NULL DEFAULT '',
  `product`    char(30)     NOT NULL DEFAULT '',
  `execution`  char(30)     NOT NULL DEFAULT '',
  `code`       char(30)     NOT NULL DEFAULT '',
  `pipeline`   char(30)     NOT NULL DEFAULT '',
  `user`       text,
  `dept`       char(30)     NOT NULL DEFAULT '',
  `year`       char(4)      NOT NULL DEFAULT '0',
  `month`      char(2)      NOT NULL DEFAULT '0',
  `week`       char(2)      NOT NULL DEFAULT '0',
  `day`        char(2)      NOT NULL DEFAULT '0',
  `value`      varchar(100) NOT NULL DEFAULT '0',
  `date`       datetime              DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

ALTER TABLE `zt_repo` ADD `lastCommit` DATETIME NULL DEFAULT NULL AFTER `lastSync`;
ALTER TABLE `zt_story` ADD `releasedDate` datetime DEFAULT NULL AFTER `reviewedDate`;
ALTER TABLE `zt_project` ADD `firstEnd` date DEFAULT NULL AFTER `end`;
ALTER TABLE `zt_product` ADD `closedDate` date DEFAULT NULL AFTER `createdVersion`;
ALTER TABLE `zt_productplan` ADD `finishedDate` datetime DEFAULT NULL AFTER `end`;
ALTER TABLE `zt_productplan` ADD `closedDate` datetime DEFAULT NULL AFTER `finishedDate`;

UPDATE `zt_case` SET `sort` = `id` WHERE `sort` = 0;
UPDATE `zt_scene` SET `sort` = `id` WHERE `sort` = 0;
UPDATE `zt_project` SET `firstEnd` = `end`;
UPDATE `zt_product` AS t1 JOIN `zt_action` AS t2 ON t2.`objectID` = t1.`id` AND t2.`action` = 'closed' AND t2.`objectType` = 'product' SET t1.`closedDate` = t2.`date`;
UPDATE `zt_productplan` AS t1 JOIN `zt_action` AS t2 ON t2.`objectID` = t1.`id` AND t2.`action` LIKE 'close%' AND t2.`objectType` = 'productplan' SET t1.`closedDate` = t2.`date`;
UPDATE `zt_productplan` AS t1 JOIN `zt_action` AS t2 ON t2.`objectID` = t1.`id` AND t2.`action` LIKE 'finish%' AND t2.`objectType` = 'productplan' SET t1.`finishedDate` = t2.`date`;

INSERT INTO `zt_cron`(`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('59', '23', '*', '*', '*', 'moduleName=metric&methodName=updateMetricLib', '计算度量数据', 'zentao', 1, 'normal', NUll);

-- DROP TABLE IF EXISTS `zt_market`;
CREATE TABLE IF NOT EXISTS `zt_market` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `industry` char(255) NOT NULL DEFAULT '',
  `scale` decimal(10, 2) NOT NULL DEFAULT '0',
  `maturity` char(255) NOT NULL DEFAULT '',
  `speed` varchar(255) NOT NULL DEFAULT '',
  `competition` char(255) NOT NULL DEFAULT '',
  `strategy` varchar(255) NOT NULL DEFAULT '',
  `ppm` varchar(20) NOT NULL DEFAULT '',
  `desc` mediumtext NULL,
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- DROP TABLE IF EXISTS `zt_marketreport`;
CREATE TABLE IF NOT EXISTS `zt_marketreport` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `market` mediumint(8) NOT NULL DEFAULT 0,
  `research` mediumint(8) NOT NULL DEFAULT 0,
  `maturity` varchar(30) NOT NULL DEFAULT '',
  `owner` varchar(30) NOT NULL DEFAULT '',
  `participants` char(255) NOT NULL DEFAULT '',
  `source` varchar(30) NOT NULL DEFAULT '',
  `desc` mediumtext NULL,
  `status` varchar(20)  NOT NULL DEFAULT '',
  `openedBy` varchar(30) NOT NULL DEFAULT '',
  `openedDate` datetime NULL,
  `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  `lastEditedDate` datetime NULL,
  `publishedBy` varchar(30) NOT NULL DEFAULT '',
  `publishedDate` datetime NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

ALTER TABLE `zt_project` ADD COLUMN `market` mediumint(8) NOT NULL DEFAULT 0;
ALTER TABLE `zt_project` ADD COLUMN `closedReason` varchar(20) NOT NULL DEFAULT '';
