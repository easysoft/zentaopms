ALTER TABLE `zt_product` ADD `shadow` tinyint(1) unsigned NOT NULL AFTER `code`;
ALTER TABLE `zt_project` ADD `hasProduct` tinyint(1) unsigned NOT NULL DEFAULT 1 AFTER `code`;
ALTER table `zt_project` ADD `multiple` enum('0', '1') NOT NULL DEFAULT '1';
ALTER TABLE `zt_repo` ADD `projects` varchar(255) NOT NULL AFTER `product`;

CREATE OR REPLACE VIEW `ztv_normalproduct` AS SELECT * FROM `zt_product` WHERE `shadow` = 0;
