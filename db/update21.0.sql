ALTER TABLE `zt_charter` ADD `reviewers` text DEFAULT NULL AFTER `reviewedDate`;
ALTER TABLE `zt_charter` ADD `reviewOpinion` text DEFAULT NULL AFTER `reviewers`;
ALTER TABLE `zt_charter` ADD `reviewResult` varchar(10) NOT NULL DEFAULT '' AFTER `reviewOpinion`;
ALTER TABLE `zt_charter` ADD `reviewStatus` varchar(30) NOT NULL DEFAULT 'wait' AFTER `reviewResult`;
ALTER TABLE `zt_charter` ADD `approval` mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `reviewStatus`;
ALTER TABLE `zt_charter` ADD `applicationInfo` text DEFAULT NULL AFTER `approval`;
