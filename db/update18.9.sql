ALTER TABLE `zt_review` MODIFY `doc` varchar(255) DEFAULT '';
ALTER TABLE `zt_review` MODIFY `docVersion` varchar(255) DEFAULT '';

CREATE INDEX `metricCode` ON zt_metriclib (metricCode) USING BTREE;
CREATE INDEX `metricID` ON zt_metriclib (metricID) USING BTREE;

ALTER TABLE `zt_feedback` ADD COLUMN `keywords` varchar(255) NOT NULL DEFAULT '';
