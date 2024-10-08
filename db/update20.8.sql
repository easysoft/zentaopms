CREATE TABLE IF NOT EXISTS `zt_workflowgroup` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL DEFAULT '',
  `projectModel` varchar(10) NOT NULL DEFAULT '',
  `projectType` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(30) NOT NULL DEFAULT '',
  `desc` text NULL,
  `disabledModules` text NULL,
  `status` varchar(10) NOT NULL DEFAULT 'wait',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE INDEX `type` ON `zt_workflowgroup` (`type`);

ALTER TABLE `zt_workflow` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflowaction` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflowlabel` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflowlayout` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflowui` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;

ALTER TABLE `zt_workflow` DROP INDEX `unique`;
CREATE UNIQUE INDEX `unique` ON `zt_workflow`(`group`,`app`,`module`,`vision`);
ALTER TABLE `zt_workflowaction` DROP INDEX `unique`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowaction`(`group`,`module`,`action`,`vision`);
ALTER TABLE `zt_workflowlayout` DROP INDEX `unique`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowlayout`(`group`,`module`,`action`,`ui`,`field`,`vision`);
