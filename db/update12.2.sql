ALTER TABLE `zt_job` ADD `frame` varchar(20) COLLATE 'utf8_general_ci' NOT NULL AFTER `repo`;
ALTER TABLE `zt_compile` ADD `testtask` mediumint unsigned NULL AFTER `atTime`;
ALTER TABLE `zt_case` ADD `auto` varchar(10) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'no' AFTER `type`;
ALTER TABLE `zt_case` ADD `frame` varchar(10) COLLATE 'utf8_general_ci' NOT NULL AFTER `auto`;
ALTER TABLE `zt_testresult` ADD `job` mediumint unsigned NOT NULL AFTER `version`;
ALTER TABLE `zt_testresult` ADD `compile` mediumint unsigned NOT NULL AFTER `job`;
ALTER TABLE `zt_job` ADD `product` mediumint(8) unsigned NOT NULL AFTER `repo`;
