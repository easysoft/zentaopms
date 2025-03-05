ALTER TABLE `zt_feedback` ADD COLUMN `prevStatus` varchar(30) NOT NULL DEFAULT '' AFTER `subStatus`;
ALTER TABLE `zt_feedback` ADD COLUMN `prevAssignedTo` varchar(255) NOT NULL DEFAULT '' AFTER `assignedTo`;

ALTER TABLE `zt_doc` ADD COLUMN `editGroups` varchar(255) NOT NULL DEFAULT '' AFTER `users`;
ALTER TABLE `zt_doc` ADD COLUMN `editUsers` text NULL AFTER `editGroups`;
ALTER TABLE `zt_doc` ADD COLUMN `readGroups` varchar(255) NOT NULL DEFAULT '' AFTER `users`;
ALTER TABLE `zt_doc` ADD COLUMN `readUsers` text NULL AFTER `readGroups`;
