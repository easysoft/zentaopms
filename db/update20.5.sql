CREATE TABLE IF NOT EXISTS `zt_workflowui` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `module` varchar(30) NOT NULL,
  `action` varchar(50) NOT NULL,
  `name` varchar(30) NOT NULL,
  `conditions` text NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `module` ON `zt_workflowui` (`module`);
CREATE INDEX `action` ON `zt_workflowui` (`action`);

ALTER TABLE `zt_workflowlayout` ADD `ui` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `action`;
ALTER TABLE `zt_workflowrelationlayout` ADD `ui` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `action`;

ALTER TABLE `zt_approvalflow` CHANGE `type` `workflow` char(30) NOT NULL DEFAULT '';
UPDATE `zt_approvalflow` SET `workflow` = '';

DROP INDEX `unique` ON `zt_workflowlayout`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowlayout`(`module`,`action`,`ui`,`field`,`vision`);

DROP INDEX `unique` ON `zt_workflowrelationlayout`;
CREATE UNIQUE INDEX `unique` ON `zt_workflowrelationlayout`(`prev`, `next`, `action`,`ui`,`field`);

UPDATE `zt_workflowaction` SET `method`='create',         `type`='single' WHERE `module`='epic' AND `action`='create';
UPDATE `zt_workflowaction` SET `method`='batchcreate',    `type`='batch'  WHERE `module`='epic' AND `action`='batchcreate';
UPDATE `zt_workflowaction` SET `method`='batchoperate',   `type`='batch'  WHERE `module`='epic' AND `action`='batchedit';
UPDATE `zt_workflowaction` SET `method`='exporttemplate', `type`='single' WHERE `module`='epic' AND `action`='exporttemplate';
UPDATE `zt_workflowaction` SET `method`='import',         `type`='single' WHERE `module`='epic' AND `action`='import';
UPDATE `zt_workflowaction` SET `method`='showimport',     `type`='single' WHERE `module`='epic' AND `action`='showimport';
UPDATE `zt_workflowaction` SET `method`='edit',           `type`='single' WHERE `module`='epic' AND `action`='edit';
UPDATE `zt_workflowaction` SET `method`='view',           `type`='single' WHERE `module`='epic' AND `action`='view';
UPDATE `zt_workflowaction` SET `method`='delete',         `type`='single' WHERE `module`='epic' AND `action`='delete';
UPDATE `zt_workflowaction` SET `method`='operate',        `type`='single' WHERE `module`='epic' AND `action`='close';
UPDATE `zt_workflowaction` SET `method`='operate',        `type`='single' WHERE `module`='epic' AND `action`='activate';
UPDATE `zt_workflowaction` SET `method`='operate',        `type`='single' WHERE `module`='epic' AND `action`='assignTo';
UPDATE `zt_workflowaction` SET `method`='operate',        `type`='single' WHERE `module`='epic' AND `action`='review';
UPDATE `zt_workflowaction` SET `method`='operate',        `type`='single' WHERE `module`='epic' AND `action`='change';

UPDATE `zt_workflow` SET `table` = 'zt_story' WHERE `table` = '`zt_story`';
UPDATE `zt_workflowfield` SET `role` = 'buildin' WHERE `module` = 'epic';

ALTER TABLE `zt_basicmeas` CHANGE `unit` `unit` varchar(100) NOT NULL DEFAULT '' AFTER `code`;
