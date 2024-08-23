CREATE TABLE IF NOT EXISTS `zt_workflowui` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `action` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL,
  `conditions` text NOT NULL,
  PRIMARY KEY `id` (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
CREATE INDEX `module`  ON `zt_workflowui` (`module`);
CREATE INDEX `action`  ON `zt_workflowui` (`action`);

ALTER TABLE `zt_workflowlayout` ADD `ui` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `action`;
ALTER TABLE `zt_workflowrelationlayout` ADD `ui` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `action`;

ALTER TABLE `zt_approvalflow` CHANGE `type` `workflow` char(30) NOT NULL DEFAULT '';
UPDATE `zt_approvalflow` SET `workflow` = '';

DROP INDEX `unique` ON `zt_workflowlayout`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowlayout`(`module`,`action`,`ui`,`field`,`vision`);

DROP INDEX `unique` ON `zt_workflowrelationlayout`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowrelationlayout`(`prev`, `next`, `action`,`ui`,`field`);
