ALTER TABLE `zt_feedback` ADD COLUMN `prevStatus` varchar(30) NOT NULL DEFAULT '' AFTER `subStatus`;
ALTER TABLE `zt_feedback` ADD COLUMN `prevAssignedTo` varchar(255) NOT NULL DEFAULT '' AFTER `assignedTo`;

ALTER TABLE `zt_doc` ADD COLUMN `editGroups` varchar(255) NOT NULL DEFAULT '' AFTER `users`;
ALTER TABLE `zt_doc` ADD COLUMN `editUsers` varchar(255) NULL AFTER `editGroups`;
