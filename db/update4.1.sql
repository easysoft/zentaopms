ALTER TABLE `zt_project` ADD `openedVersion` varchar(20) COLLATE 'utf8_general_ci' NOT NULL AFTER `openedDate`;
ALTER TABLE `zt_product` ADD `createdVersion` varchar(20) COLLATE 'utf8_general_ci' NOT NULL AFTER `createdDate`;

ALTER TABLE  `zt_product` DROP  `order`;
ALTER TABLE  `zt_project` DROP  `order`;
