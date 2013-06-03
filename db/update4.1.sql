ALTER TABLE `zt_module` ADD `product` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `root`,
ADD `project` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `product`;
