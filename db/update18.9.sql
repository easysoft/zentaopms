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
