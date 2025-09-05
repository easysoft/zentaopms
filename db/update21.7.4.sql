ALTER TABLE `zt_case` CHANGE `order` `order` mediumint(8) unsigned NOT NULL DEFAULT '0';

CREATE INDEX `parent` ON `zt_kanbancolumn`(`parent`);
CREATE INDEX `group` ON `zt_kanbancolumn`(`group`);
CREATE INDEX `execution` ON `zt_kanbanlane`(`execution`);
CREATE INDEX `group` ON `zt_kanbanlane`(`group`);
CREATE INDEX `lane` ON `zt_kanbancell`(`lane`);
CREATE INDEX `feedback` ON `zt_bug`(`feedback`);
CREATE INDEX `feedback` ON `zt_story`(`feedback`);
CREATE INDEX `feedback` ON `zt_task`(`feedback`);
CREATE INDEX `feedback` ON `zt_ticket`(`feedback`);
CREATE INDEX `feedback` ON `zt_todo`(`feedback`);

CREATE OR REPLACE VIEW `ztv_executionsummary` AS SELECT `zt_task`.`execution` AS `execution`,SUM(IF((`zt_task`.`isParent` = '0'),`zt_task`.`estimate`,0)) AS `estimate`,SUM(IF((`zt_task`.`isParent` = '0'),`zt_task`.`consumed`,0)) AS `consumed`,SUM(IF(((`zt_task`.`status` <> 'cancel') AND (`zt_task`.`status` <> 'closed') AND (`zt_task`.`isParent` = '0')),`zt_task`.`left`,0)) AS `left`,COUNT(0) AS `number`,SUM(IF(((`zt_task`.`status` <> 'done') AND (`zt_task`.`status` <> 'closed')),1,0)) AS `undone`,SUM(IF(`zt_task`.`isParent` = '0',`zt_task`.`consumed`,0) + IF(((`zt_task`.`status` <> 'cancel') AND (`zt_task`.`status` <> 'closed') AND (`zt_task`.`isParent` = '0')),`zt_task`.`left`,0)) AS `totalReal` FROM `zt_task` WHERE (`zt_task`.`deleted` = '0') GROUP BY `zt_task`.`execution`;
CREATE OR REPLACE VIEW `ztv_projectsummary` AS SELECT `zt_task`.`project` AS `project`,SUM(IF((`zt_task`.`isParent` = '0'),`zt_task`.`estimate`,0)) AS `estimate`,SUM(IF((`zt_task`.`isParent` = '0'),`zt_task`.`consumed`,0)) AS `consumed`,SUM(IF(((`zt_task`.`status` <> 'cancel') AND (`zt_task`.`status` <> 'closed') AND (`zt_task`.`isParent` = '0')),`zt_task`.`left`,0)) AS `left`,COUNT(0) AS `number`,SUM(IF(((`zt_task`.`status` <> 'done') AND (`zt_task`.`status` <> 'closed')),1,0)) AS `undone`,SUM(IF(`zt_task`.`isParent` = '0',`zt_task`.`consumed`,0) + IF(((`zt_task`.`status` <> 'cancel') AND (`zt_task`.`status` <> 'closed') AND (`zt_task`.`isParent` = '0')),`zt_task`.`left`,0)) AS `totalReal` FROM `zt_task` WHERE (`zt_task`.`deleted` = '0') GROUP BY `zt_task`.`project`;
