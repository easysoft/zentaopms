ALTER TABLE `zt_feedback` ADD COLUMN `prevStatus` varchar(30) NOT NULL DEFAULT '' AFTER `subStatus`;
ALTER TABLE `zt_feedback` ADD COLUMN `prevAssignedTo` varchar(255) NOT NULL DEFAULT '' AFTER `assignedTo`;

ALTER TABLE `zt_doc` ADD COLUMN `readGroups` varchar(255) NOT NULL DEFAULT '' AFTER `users`;
ALTER TABLE `zt_doc` ADD COLUMN `readUsers` text NULL AFTER `readGroups`;

ALTER TABLE `zt_workflowgroup` MODIFY `name` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_workflowgroup` ADD COLUMN `exclusive` enum('0','1') NOT NULL DEFAULT '0' AFTER `main`;
ALTER TABLE `zt_doclib` ADD COLUMN `archived` enum('0','1') NOT NULL DEFAULT '0';
