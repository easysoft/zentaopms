ALTER TABLE `zt_job` ADD `frame` varchar(20) COLLATE 'utf8_general_ci' NOT NULL AFTER `repo`;
ALTER TABLE `zt_compile` ADD `testtask` mediumint unsigned NULL AFTER `atTime`;
