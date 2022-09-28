ALTER TABLE `zt_product` ADD `shadow` tinyint(1) unsigned NOT NULL AFTER `code`;
ALTER TABLE `zt_project` ADD `hasProduct` tinyint(1) unsigned NOT NULL DEFAULT 1 AFTER `code`;

ALTER TABLE `zt_product` MODIFY `name` varchar(110) NOT NULL;

ALTER TABLE `zt_repo` ADD `projectList` varchar(200) NOT NULL AFTER `product`;
