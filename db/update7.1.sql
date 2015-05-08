ALTER TABLE `zt_product` ADD `order` mediumint unsigned NOT NULL AFTER `createdVersion`;
ALTER TABLE `zt_project` ADD `order` mediumint unsigned NOT NULL AFTER `whitelist`;
ALTER TABLE `zt_group` ADD `acl` text COLLATE 'utf8_general_ci' NOT NULL;
