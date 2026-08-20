ALTER TABLE `zt_deliverablestage` ADD COLUMN `type` varchar(30) NOT NULL DEFAULT 'execution' AFTER `deliverable`;
UPDATE `zt_deliverablestage` SET `type` = 'project' WHERE `stage` = 'project';
ALTER TABLE `zt_deliverablestage` DROP INDEX `unique`;
ALTER TABLE `zt_deliverablestage` ADD UNIQUE INDEX `unique` (`deliverable`,`type`,`stage`);

ALTER TABLE `zt_projectdeliverable` CHANGE `submitFrom` `sourceID` int unsigned NOT NULL DEFAULT 0 COMMENT '来源对象';
ALTER TABLE `zt_projectdeliverable` ADD COLUMN `sourceType` varchar(20) NOT NULL DEFAULT 'project' COMMENT '来源类型' AFTER `project`;
UPDATE `zt_projectdeliverable` t1 INNER JOIN `zt_project` t2 ON t1.`sourceID` = t2.id AND t2.`type` != 'project' SET t1.`sourceType` = 'execution';
