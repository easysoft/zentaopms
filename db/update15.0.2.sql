ALTER TABLE `zt_team` CHANGE `hours` `hours` float(3,1) unsigned NOT NULL DEFAULT '0.0' AFTER `days`;
ALTER TABLE `zt_group` CHANGE `role` `role` char(30) COLLATE 'utf8_general_ci' NOT NULL;
