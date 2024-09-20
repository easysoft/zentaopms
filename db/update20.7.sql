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
ALTER TABLE `zt_workflowui` DROP INDEX `unique`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowui`(`group`,`module`,`action`,`name`);
