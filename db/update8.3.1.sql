ALTER TABLE `zt_branch` ADD `order` smallint unsigned NOT NULL AFTER `name`;
ALTER TABLE `zt_module` ADD `deleted` enum('0','1') COLLATE 'utf8_general_ci' NOT NULL DEFAULT '0';
