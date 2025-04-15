ALTER TABLE `zt_relationoftasks` ADD COLUMN `project` mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `zt_relationoftasks` MODIFY `execution` char(30) NOT NULL DEFAULT '' AFTER `project`;
UPDATE `zt_relationoftasks` SET `project` = (SELECT `project` FROM `zt_task` WHERE `id` = `zt_relationoftasks`.`task`);
