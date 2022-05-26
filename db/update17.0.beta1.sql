ALTER TABLE `zt_task` ADD `fromIssue` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `fromBug`;

ALTER TABLE `zt_kanbanspace` ADD COLUMN `activatedBy` char(30) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_kanbanspace` ADD COLUMN `activatedDate` datetime NOT NULL AFTER `activatedBy`;

ALTER TABLE `zt_kanban` ADD COLUMN `activatedBy` char(30) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_kanban` ADD COLUMN `activatedDate` datetime NOT NULL AFTER `activatedBy`;

ALTER TABLE `zt_task` ADD COLUMN `mode` varchar(10) NOT NULL AFTER `type`;
UPDATE `zt_task` SET mode = 'linear' WHERE `id` IN (SELECT root FROM `zt_team` WHERE type = 'task');

DELETE FROM `zt_workflowaction` WHERE `module`='program' AND `action`='view';

UPDATE `zt_workflow` SET `app`='execution' WHERE `module`='task' AND `vision`='rnd';
UPDATE `zt_workflow` SET `app`='execution' WHERE `module`='build' AND `vision`='rnd';
UPDATE `zt_workflow` SET `app`='execution' WHERE `module`='execution' AND `vision`='rnd';
UPDATE `zt_workflow` SET `app`='program'   WHERE `module`='program' AND `vision`='rnd';

REPLACE INTO `zt_workflowaction` (`module`, `action`, `method`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('execution','build','browse','所有版本','single','different','none','normal','browseandview','normal','direct',0,1,0,'','','','','','','','','','enable','rnd','','0000-00-00 00:00:00','','0000-00-00 00:00:00');
