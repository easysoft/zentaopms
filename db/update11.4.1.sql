ALTER TABLE `zt_entry` ADD `calledTime` int(10) unsigned NOT NULL DEFAULT '0' AFTER `createdDate`;
ALTER TABLE `zt_story` CHANGE `toBug` `toBug` mediumint(8) unsigned NOT NULL AFTER `closedReason`;
