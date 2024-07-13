ALTER TABLE `zt_approvalnode` ADD `forwardTo` char(30) NOT NULL DEFAULT '' AFTER `extra`;
ALTER TABLE `zt_approvalnode` ADD `revertTo` char(30) NOT NULL DEFAULT '' AFTER `extra`;
