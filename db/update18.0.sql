ALTER TABLE `zt_story` ADD COLUMN `siblings` varchar(255) NOT NULL AFTER `linkRequirements`;

ALTER TABLE `zt_productplan` MODIFY COLUMN `branch` varchar(255) NOT NULL;
