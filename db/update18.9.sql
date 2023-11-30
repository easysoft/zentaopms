ALTER TABLE `zt_review` MODIFY `doc` varchar(255) DEFAULT '';
ALTER TABLE `zt_review` MODIFY `docVersion` varchar(255) DEFAULT '';

CREATE INDEX `metricCode` ON zt_metriclib (metricCode) USING BTREE;
