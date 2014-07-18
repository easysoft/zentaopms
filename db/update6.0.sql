ALTER TABLE `zt_story` CHANGE `status` `status` enum('','changed','active','draft','closed') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' AFTER `estimate`;
ALTER TABLE `zt_story` CHANGE `stage` `stage` enum('','wait','planned','projected','developing','developed','testing','tested','verified','released') COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'wait' AFTER `status`;
ALTER TABLE `zt_testtask` CHANGE `status` `status` enum('blocked','doing','wait','done') COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'wait' AFTER `report`;
ALTER TABLE `zt_todo` CHANGE `status` `status` enum('wait','doing','done') COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'wait' AFTER `desc`;
ALTER TABLE `zt_team` CHANGE `hours` `hours` float(2,1) unsigned NOT NULL DEFAULT '0' AFTER `days`;
ALTER TABLE `zt_bug` ADD `testtask` mediumint(8) unsigned NOT NULL AFTER `result`;
