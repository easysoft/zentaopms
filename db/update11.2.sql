update `zt_build` set `deleted`='1' where `project`='0' and `id` in (select `build` from `zt_release` where `deleted`='1');
ALTER TABLE `zt_productplan` ADD `parent` mediumint(9) NOT NULL DEFAULT '0' AFTER `branch`;
ALTER TABLE `zt_action` CHANGE `actor` `actor` varchar(100) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' AFTER `project`;
