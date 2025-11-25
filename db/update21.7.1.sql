ALTER TABLE `zt_project`ADD `isTpl` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `project`;
ALTER TABLE `zt_task` ADD `isTpl` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `isParent`;

ALTER TABLE `zt_oauth` MODIFY `openID` varchar(100) NOT NULL DEFAULT '';
ALTER TABLE `zt_doclib` MODIFY `id` mediumint(8) unsigned NOT NULL auto_increment;
ALTER TABLE `zt_doclib` MODIFY `order` mediumint(8) unsigned NOT NULL DEFAULT '0';

ALTER TABLE `zt_repobranch` MODIFY `branch` varchar(100) NOT NULL DEFAULT '';

UPDATE `zt_workflowgroup` SET `name` = '瀑布式产品研发' WHERE `code` = 'waterfallproduct';
UPDATE `zt_workflowgroup` SET `name` = '瀑布式项目研发' WHERE `code` = 'waterfallproject';
UPDATE `zt_workflowgroup` SET `name` = '敏捷式产品研发' WHERE `code` = 'scrumproduct';
UPDATE `zt_workflowgroup` SET `name` = '敏捷式项目研发' WHERE `code` = 'scrumproject';

CREATE OR REPLACE VIEW `ztv_projectnotpl` AS SELECT * FROM `zt_project` WHERE `deleted` = '0' AND `isTpl` = 0;
CREATE OR REPLACE VIEW `ztv_tasknotpl`    AS SELECT * FROM `zt_task`    WHERE `deleted` = '0' AND `isTpl` = 0;

ALTER TABLE `zt_compile` ADD `branch` varchar(255) NOT NULL DEFAULT '' AFTER `status`;

ALTER TABLE `zt_doc`
ADD `templateDesc` text NULL AFTER `templateType`,
ADD `builtIn` enum('0','1') NOT NULL DEFAULT '0' AFTER `version`;

DROP TABLE IF EXISTS `zt_solution`;

CREATE TABLE IF NOT EXISTS `zt_actionproduct` (
  `action` mediumint(8) unsigned NOT NULL,
  `product` mediumint(8) unsigned NOT NULL
) ENGINE=InnoDB;
CREATE INDEX `action_product` ON `zt_actionproduct`(`action`, `product`);
CREATE INDEX `product` ON `zt_actionproduct`(`product`);

ALTER TABLE `zt_actionrecent` DROP INDEX `date`;
ALTER TABLE `zt_actionrecent` ADD INDEX `vision_date` (`vision`, `date`);
ALTER TABLE `zt_actionrecent` ADD INDEX `execution` (`execution`);

ALTER TABLE `zt_action` DROP INDEX `date`;
ALTER TABLE `zt_action` DROP INDEX `project`;
ALTER TABLE `zt_action` ADD INDEX `vision_date` (`vision`, `date`);
ALTER TABLE `zt_action` ADD INDEX `execution` (`execution`);
ALTER TABLE `zt_action` ADD INDEX `project` (`project`);

UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterLevel' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'level';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterCategory' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'category';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterMarket' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'market';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterCloseReason' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'closedReason';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterReviewResult' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'reviewedResult';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'charterReviewStatus' LIMIT 1) WHERE `module` = 'charter' AND `field` = 'reviewStatus';

CREATE OR REPLACE VIEW `ztv_executionsummary` AS SELECT `zt_task`.`execution` AS `execution`,SUM(IF((`zt_task`.`isParent` > '0'),`zt_task`.`estimate`,0)) AS `estimate`,SUM(IF((`zt_task`.`isParent` > '0'),`zt_task`.`consumed`,0)) AS `consumed`,SUM(IF(((`zt_task`.`status` <> 'cancel') AND (`zt_task`.`status` <> 'closed') AND (`zt_task`.`isParent` > '0')),`zt_task`.`left`,0)) AS `left`,COUNT(0) AS `number`,SUM(IF(((`zt_task`.`status` <> 'done') AND (`zt_task`.`status` <> 'closed')),1,0)) AS `undone`,SUM(IF(`zt_task`.`isParent` > '0',`zt_task`.`consumed`,0) + IF(((`zt_task`.`status` <> 'cancel') AND (`zt_task`.`status` <> 'closed') AND (`zt_task`.`isParent` > '0')),`zt_task`.`left`,0)) AS `totalReal` FROM `zt_task` WHERE (`zt_task`.`deleted` = '0') GROUP BY `zt_task`.`execution`;
CREATE OR REPLACE VIEW `ztv_projectsummary` AS SELECT `zt_task`.`project` AS `project`,SUM(IF((`zt_task`.`isParent` > '0'),`zt_task`.`estimate`,0)) AS `estimate`,SUM(IF((`zt_task`.`isParent` > '0'),`zt_task`.`consumed`,0)) AS `consumed`,SUM(IF(((`zt_task`.`status` <> 'cancel') AND (`zt_task`.`status` <> 'closed') AND (`zt_task`.`isParent` > '0')),`zt_task`.`left`,0)) AS `left`,COUNT(0) AS `number`,SUM(IF(((`zt_task`.`status` <> 'done') AND (`zt_task`.`status` <> 'closed')),1,0)) AS `undone`,SUM(IF(`zt_task`.`isParent` > '0',`zt_task`.`consumed`,0) + IF(((`zt_task`.`status` <> 'cancel') AND (`zt_task`.`status` <> 'closed') AND (`zt_task`.`isParent` > '0')),`zt_task`.`left`,0)) AS `totalReal` FROM `zt_task` WHERE (`zt_task`.`deleted` = '0') GROUP BY `zt_task`.`project`;

ALTER TABLE `zt_history` MODIFY `new` longtext NULL;
ALTER TABLE `zt_history` MODIFY `old` longtext NULL;
