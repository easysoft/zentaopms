ALTER TABLE `zt_metric` ADD COLUMN `alias` varchar(90) NOT NULL DEFAULT '' AFTER `code`;
CREATE INDEX `date` ON zt_metriclib (date);

UPDATE zt_project SET lifetime = 'long' where lifetime = 'waterfall';

ALTER TABLE `zt_story` ADD COLUMN `unlinkReason` ENUM('', 'omit', 'other') NOT NULL DEFAULT '';

UPDATE `zt_stage` SET `name` = '生命周期' WHERE `type` = 'lifecycle' AND `projectType` = 'ipd';

ALTER TABLE `zt_metriclib` ADD COLUMN `calcType` ENUM('cron', 'inference') NOT NULL DEFAULT 'cron';
ALTER TABLE `zt_metriclib` ADD COLUMN `calculatedBy` varchar(30) NOT NULL DEFAULT '';

UPDATE `zt_workflowfield` SET `control` = 'input', `options` = '' WHERE `module` = 'task' AND `field` = 'parent';

ALTER TABLE `zt_relationoftasks` DROP INDEX `relationoftasks`;
ALTER TABLE `zt_relationoftasks` ADD INDEX `relationoftasks`(`execution` ASC, `task` ASC);

UPDATE `zt_productplan` AS t1 JOIN `zt_action` AS t2 ON t2.`objectID` = t1.`id` AND t2.`action`='opened' AND t2.`objectType` = 'productplan' SET t1.`createdDate` = t2.`date`;
