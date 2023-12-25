ALTER TABLE `zt_review` MODIFY `doc` varchar(255) DEFAULT '';
ALTER TABLE `zt_review` MODIFY `docVersion` varchar(255) DEFAULT '';
ALTER TABLE `zt_review` MODIFY `template` mediumint(8) NOT NULL DEFAULT '0';
ALTER TABLE `zt_review` MODIFY `createdDate` date NULL;
ALTER TABLE `zt_review` MODIFY `deadline` date NULL;
ALTER TABLE `zt_review` MODIFY `lastReviewedDate` date NULL;
ALTER TABLE `zt_review` MODIFY `lastAuditedDate` date NULL;
ALTER TABLE `zt_review` MODIFY `lastEditedDate` date NULL;

ALTER TABLE `zt_meeting` MODIFY `minutedDate` datetime NULL;
ALTER TABLE `zt_meeting` MODIFY `editedDate` datetime NULL;

ALTER TABLE `zt_story` MODIFY `submitedBy` varchar(30) DEFAULT '';
ALTER TABLE `zt_story` MODIFY `demand` mediumint(8) DEFAULT 0;
ALTER TABLE `zt_story` MODIFY `duration` char(30) DEFAULT '';
ALTER TABLE `zt_story` MODIFY `BSA` char(30) DEFAULT '';

CREATE INDEX `metricCode` ON zt_metriclib (metricCode) USING BTREE;
CREATE INDEX `metricID` ON zt_metriclib (metricID) USING BTREE;

ALTER TABLE `zt_feedback` ADD COLUMN `keywords` varchar(255) NOT NULL DEFAULT '';

DELETE FROM `zt_cron` WHERE command='moduleName=measurement&methodName=initCrontabQueue';
DELETE FROM `zt_cron` WHERE command='moduleName=measurement&methodName=execCrontabQueue';

ALTER TABLE `zt_burn` MODIFY `execution` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_burn` MODIFY `estimate` float NOT NULL DEFAULT '0';
ALTER TABLE `zt_burn` MODIFY `left` float NOT NULL DEFAULT '0';
ALTER TABLE `zt_burn` MODIFY `consumed` float NOT NULL DEFAULT '0';

ALTER TABLE `zt_task` MODIFY `design` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_task` MODIFY `designVersion` smallint(6) unsigned NOT NULL DEFAULT '1';
ALTER TABLE `zt_task` MODIFY `left` float unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_task` MODIFY `subStatus` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_task` MODIFY `color` char(7) NOT NULL DEFAULT '';
ALTER TABLE `zt_task` MODIFY `desc` mediumtext NULL;
ALTER TABLE `zt_task` MODIFY `version` smallint(6) NOT NULL DEFAULT '0';
ALTER TABLE `zt_task` MODIFY `assignedTo` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_task` MODIFY `project` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_task` MODIFY `execution` mediumint(8) unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_task` MODIFY `consumed` float unsigned NOT NULL DEFAULT '0';
ALTER TABLE `zt_task` MODIFY `openedBy` varchar(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_effort` MODIFY `extra` text NULL;
