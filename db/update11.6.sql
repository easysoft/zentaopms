ALTER TABLE `zt_translation`
CHANGE `refer` `referer` text COLLATE 'utf8_general_ci' NOT NULL AFTER `value`,
CHANGE `translationTime` `translatedTime` datetime NOT NULL AFTER `translator`,
CHANGE `reviewTime` `reviewedTime` datetime NOT NULL AFTER `reviewer`;

ALTER TABLE `zt_product` ADD `subStatus` varchar(30) NOT NULL AFTER `status`;
ALTER TABLE `zt_release` ADD `subStatus` varchar(30) NOT NULL AFTER `status`;
ALTER TABLE `zt_story` ADD `subStatus` varchar(30) NOT NULL AFTER `status`;
ALTER TABLE `zt_project` ADD `subStatus` varchar(30) NOT NULL AFTER `status`;
ALTER TABLE `zt_task` ADD `subStatus` varchar(30) NOT NULL AFTER `status`;
ALTER TABLE `zt_bug` ADD `subStatus` varchar(30) NOT NULL AFTER `status`;
ALTER TABLE `zt_case` ADD `subStatus` varchar(30) NOT NULL AFTER `status`;
ALTER TABLE `zt_testtask` ADD `subStatus` varchar(30) NOT NULL AFTER `status`;
