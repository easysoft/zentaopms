UPDATE `zt_workflowaction` SET `module` = 'story', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'browse';
UPDATE `zt_workflowlayout` SET `module` = 'story', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'browse';

UPDATE `zt_workflowaction` SET `module` = 'requirement', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'requirement';
UPDATE `zt_workflowlayout` SET `module` = 'requirement', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'requirement';

UPDATE `zt_workflowaction` SET `module` = 'epic', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'epic';
UPDATE `zt_workflowlayout` SET `module` = 'epic', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'epic';

UPDATE `zt_workflowaction` SET `module` = 'build', `action` = 'browse' WHERE `module` = 'execution' AND `action` = 'build';
UPDATE `zt_workflowlayout` SET `module` = 'build', `action` = 'browse' WHERE `module` = 'execution' AND `action` = 'build';

UPDATE `zt_workflowaction` SET `module` = 'task', `action` = 'browse' WHERE `module` = 'execution' AND `action` = 'task';
UPDATE `zt_workflowlayout` SET `module` = 'task', `action` = 'browse' WHERE `module` = 'execution' AND `action` = 'task';

ALTER TABLE `zt_relationoftasks` ADD COLUMN `project` mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `zt_relationoftasks` MODIFY `execution` char(30) NOT NULL DEFAULT '' AFTER `project`;
UPDATE `zt_relationoftasks` SET `project` = (SELECT `project` FROM `zt_task` WHERE `id` = `zt_relationoftasks`.`task`);

CREATE TABLE IF NOT EXISTS `zt_deliverable` (
  `id` int(8) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `module` varchar(30) NULL,
  `method` varchar(30) NULL,
  `model` text NULL,
  `type` enum('doc','file') NULL DEFAULT 'file',
  `desc` text NULL,
  `files` varchar(255) NULL,
  `createdBy` varchar(30) NULL,
  `createdDate` date NULL,
  `lastEditedBy` varchar(30) NULL,
  `lastEditedDate` date NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `zt_workflowgroup` ADD `objectID` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_workflowgroup` ADD COLUMN `deliverable` text NULL AFTER `editedDate`;
ALTER TABLE `zt_project` ADD COLUMN `deliverable` text NULL AFTER `maxColWidth`;