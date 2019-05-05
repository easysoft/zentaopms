ALTER TABLE `zt_entry` ADD `calledTime` int(10) unsigned NOT NULL DEFAULT '0' AFTER `createdDate`;
ALTER TABLE `zt_story` CHANGE `toBug` `toBug` mediumint(8) unsigned NOT NULL AFTER `closedReason`;
UPDATE `zt_story` SET `stage` = 'closed' WHERE `status` = 'closed';
ALTER TABLE `zt_story` ADD `stagedBy` char(30) COLLATE 'utf8_general_ci' NOT NULL AFTER `stage`;
ALTER TABLE `zt_storystage` ADD `stagedBy` char(30) COLLATE 'utf8_general_ci' NOT NULL;
