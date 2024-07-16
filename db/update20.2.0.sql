ALTER TABLE `zt_approvalnode` ADD `forwardBy` char(30) NOT NULL DEFAULT '' AFTER `extra`;
ALTER TABLE `zt_approvalnode` ADD `revertTo` char(30) NOT NULL DEFAULT '' AFTER `extra`;

UPDATE `zt_workflowaction` SET conditions = '' WHERE `action` IN ('approvalreview','approvalcancel','approvalsubmit');
