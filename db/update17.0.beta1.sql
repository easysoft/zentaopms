ALTER TABLE `zt_kanbanspace` ADD COLUMN `activatedBy` char(30) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_kanbanspace` ADD COLUMN `activatedDate` datetime NOT NULL AFTER `activatedBy`;

ALTER TABLE `zt_kanban` ADD COLUMN `activatedBy` char(30) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_kanban` ADD COLUMN `activatedDate` datetime NOT NULL AFTER `activatedBy`;

ALTER TABLE `zt_task` ADD COLUMN `mode` varchar(10) NOT NULL AFTER `type`;
UPDATE `zt_task` SET mode = 'linear' WHERE `id` IN (SELECT root FROM `zt_team` WHERE type = 'task');
