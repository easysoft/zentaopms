ALTER TABLE zt_opportunity ADD `desc` mediumtext NULL AFTER `from`;
ALTER TABLE zt_taskteam MODIFY `status` enum('wait','doing','done','cancel','closed') NOT NULL DEFAULT 'wait';

CREATE TABLE IF NOT EXISTS `zt_workflowgroup` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(10) NOT NULL DEFAULT '',
  `projectModel` varchar(10) NOT NULL DEFAULT '',
  `projectType` varchar(10) NOT NULL DEFAULT '',
  `name` varchar(30) NOT NULL DEFAULT '',
  `desc` text NULL,
  `disabledModules` varchar(255) NOT NULL DEFAULT '',
  `status` varchar(10) NOT NULL DEFAULT 'wait',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd',
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` datetime NULL,
  `editedBy` varchar(30) NOT NULL DEFAULT '',
  `editedDate` datetime NULL,
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `type` ON `zt_workflowgroup` (`type`);

ALTER TABLE `zt_workflow` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflow` ADD `role` varchar(10) NOT NULL DEFAULT 'buildin' AFTER `buildin`;
ALTER TABLE `zt_workflowfield` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflowaction` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflowlabel` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflowlayout` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflowui` ADD `group` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;

UPDATE `zt_workflow` SET `role` = 'custom' WHERE `buildin` = '0';

DROP INDEX `unique` ON `zt_workflow`;
CREATE UNIQUE INDEX `unique` ON `zt_workflow`(`group`,`app`,`module`,`vision`);
DROP INDEX `unique` ON `zt_workflowfield`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowfield`(`group`,`module`,`field`);
DROP INDEX `unique` ON `zt_workflowaction`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowaction`(`group`,`module`,`action`,`vision`);
DROP INDEX `unique` ON `zt_workflowlayout`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowlayout`(`group`,`module`,`action`,`ui`,`field`,`vision`);

ALTER TABLE `zt_product` ADD `workflowGroup` int(8) NOT NULL DEFAULT '0' AFTER `ticket`;
ALTER TABLE `zt_project` ADD `workflowGroup` int(8) NOT NULL DEFAULT '0' AFTER `hasProduct`;
ALTER TABLE `zt_workflowgroup` ADD `main` enum('0','1') NOT NULL DEFAULT '0' AFTER `vision`;
ALTER TABLE `zt_workflowgroup` ADD `code` varchar(30) NOT NULL DEFAULT '' AFTER `name`;

DELETE FROM `zt_workflowgroup` WHERE `main` = '1';
INSERT INTO `zt_workflowgroup` (`type`, `projectModel`, `projectType`, `name`, `code`, `status`, `vision`, `main`) VALUES
('product', '',          'project',  '默认流程',           'productproject',  'normal', 'rnd', '1'),
('project', 'scrum',     'product',  '产品型敏捷项目流程', 'scrumproduct',    'normal', 'rnd', '1'),
('project', 'scrum',     'project',  '项目型敏捷项目流程', 'scrumproject',    'normal', 'rnd', '1'),
('project', 'waterfall', 'product',  '产品型瀑布项目流程', 'waterfallproduct','normal', 'rnd', '1'),
('project', 'waterfall', 'project',  '项目型瀑布项目流程', 'waterfallproject','normal', 'rnd', '1');

ALTER TABLE `zt_doclib` ADD `orderBy` varchar(30) NOT NULL DEFAULT 'id_asc' AFTER `deleted`;

UPDATE `zt_grouppriv` SET `method` = 'quick' WHERE `module` = 'doc' AND `method` = 'myView';

ALTER TABLE `zt_history` ADD `oldValue` text NULL AFTER `old`;
ALTER TABLE `zt_history` ADD `newValue` text NULL AFTER `new`;
