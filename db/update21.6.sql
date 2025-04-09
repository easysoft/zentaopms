ALTER TABLE `zt_relationoftasks` ADD COLUMN `project` mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `zt_relationoftasks` MODIFY `execution` char(30) NOT NULL DEFAULT '' AFTER `project`;
