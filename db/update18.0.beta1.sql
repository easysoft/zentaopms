ALTER TABLE `zt_story` ADD COLUMN `twins` varchar(255) NOT NULL AFTER `linkRequirements`;
ALTER TABLE `zt_productplan` MODIFY COLUMN `branch` varchar(255) NOT NULL DEFAULT '0' AFTER `product`;
ALTER TABLE `zt_effort` ADD `extra` text COLLATE 'utf8_general_ci' NOT NULL AFTER `end`;
ALTER TABLE `zt_build` MODIFY COLUMN `branch` varchar(255) NOT NULL DEFAULT '0' AFTER `product`;
ALTER TABLE `zt_feedback` ADD COLUMN `activatedBy` varchar(30) NOT NULL AFTER `assignedDate`;
ALTER TABLE `zt_feedback` ADD COLUMN `activatedDate` datetime NOT NULL AFTER `activatedBy`;
