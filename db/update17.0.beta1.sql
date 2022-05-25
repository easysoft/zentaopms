ALTER TABLE `zt_task` ADD `fromIssue` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `fromBug`;

ALTER TABLE `zt_kanbanspace` ADD COLUMN `activatedBy` char(30) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_kanbanspace` ADD COLUMN `activatedDate` datetime NOT NULL AFTER `activatedBy`;

ALTER TABLE `zt_kanban` ADD COLUMN `activatedBy` char(30) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_kanban` ADD COLUMN `activatedDate` datetime NOT NULL AFTER `activatedBy`;
