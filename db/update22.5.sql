CREATE TABLE IF NOT EXISTS `zt_rule` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL DEFAULT '',
  `scene` varchar(30) NOT NULL DEFAULT 'automation',
  `type` varchar(30) NOT NULL DEFAULT 'global',
  `workflowGroup` varchar(255) DEFAULT NULL,
  `objectType` varchar(30) NOT NULL DEFAULT '',
  `action` varchar(30) NOT NULL DEFAULT '',
  `alertLevel` varchar(10) NOT NULL DEFAULT 'notice',
  `triggerKind` varchar(30) NOT NULL DEFAULT 'object',
  `nodes` mediumtext DEFAULT NULL,
  `version` mediumint unsigned NOT NULL DEFAULT 0,
  `priority` mediumint unsigned NOT NULL DEFAULT 1,
  `runAs` varchar(30) NOT NULL DEFAULT '',
  `method` varchar(30) NOT NULL DEFAULT 'sync',
  `status` char(30) NOT NULL DEFAULT 'disable',
  `notifyUsers` text NULL,
  `notifyMethod` varchar(30) NULL,
  `createdBy` varchar(30) DEFAULT NULL,
  `createdDate` date DEFAULT NULL,
  `lastEditedBy` varchar(30) DEFAULT NULL,
  `lastEditedDate` date DEFAULT NULL,
  `lastRunTime` datetime DEFAULT NULL,
  `lastRunResult` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX `idx_rule_scene_status` ON `zt_rule` (`scene`, `status`);
CREATE INDEX `idx_rule_object_action` ON `zt_rule` (`objectType`, `action`);
CREATE INDEX `idx_rule_priority` ON `zt_rule` (`priority`, `id`);

CREATE TABLE IF NOT EXISTS `zt_rulequeue` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `rule` mediumint unsigned NOT NULL DEFAULT 0,
  `ruleVersion` mediumint unsigned NOT NULL DEFAULT 0,
  `fromObject` mediumtext DEFAULT NULL,
  `actions` longtext DEFAULT NULL,
  `status` char(30) NOT NULL DEFAULT 'wait',
  `runAs` varchar(30) NOT NULL DEFAULT '',
  `log` text DEFAULT NULL,
  `triggeredBy` varchar(30) DEFAULT NULL,
  `triggeredDate` datetime DEFAULT NULL,
  `executedTime` datetime DEFAULT NULL,
  `startedAt` datetime DEFAULT NULL,
  `finishedAt` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX `idx_rulequeue_status` ON `zt_rulequeue` (`status`);
CREATE INDEX `idx_rulequeue_rule` ON `zt_rulequeue` (`rule`);