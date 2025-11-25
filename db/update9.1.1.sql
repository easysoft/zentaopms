ALTER TABLE `zt_block` ADD `height` smallint unsigned NOT NULL DEFAULT '0' AFTER `grid`;
ALTER TABLE `zt_product` CHANGE `whitelist` `whitelist` text NOT NULL AFTER `acl`;
ALTER TABLE `zt_project` CHANGE `whitelist` `whitelist` text NOT NULL AFTER `acl`;
