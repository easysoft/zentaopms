ALTER TABLE `zt_product` ADD `shadow` tinyint(1) unsigned NOT NULL AFTER `code`;
ALTER TABLE `zt_project` ADD `hasProduct` tinyint(1) unsigned NOT NULL AFTER `code`;
