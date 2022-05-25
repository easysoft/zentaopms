ALTER TABLE `zt_task` ADD `fromIssue` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `fromBug`;

ALTER TABLE `zt_kanbanspace` ADD COLUMN `activatedBy` char(30) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_kanbanspace` ADD COLUMN `activatedDate` datetime NOT NULL AFTER `activatedBy`;

ALTER TABLE `zt_kanban` ADD COLUMN `activatedBy` char(30) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_kanban` ADD COLUMN `activatedDate` datetime NOT NULL AFTER `activatedBy`;

DELETE FROM `zt_workflowaction` WHERE `module`='program' AND `action`='view';
DELETE FROM `zt_workflowaction` WHERE `module`='story'   AND `action`='browse';
DELETE FROM `zt_workflowaction` WHERE `module`='task'    AND `action`='browse';
DELETE FROM `zt_workflowaction` WHERE `module`='build'   AND `action`='browse';

UPDATE `zt_workflow` SET `app`='execution' WHERE `module`='task' AND `vision`='rnd';
UPDATE `zt_workflow` SET `app`='execution' WHERE `module`='build' AND `vision`='rnd';
UPDATE `zt_workflow` SET `app`='execution' WHERE `module`='execution' AND `vision`='rnd';
UPDATE `zt_workflow` SET `app`='program'   WHERE `module`='program' AND `vision`='rnd';
