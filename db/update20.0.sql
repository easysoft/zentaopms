DELETE FROM `zt_block` WHERE `type` IN ('news', 'patch', 'plugin', 'puglicclass');

ALTER TABLE `zt_block` ADD `dashboard` varchar(20) NOT NULL DEFAULT '' AFTER `account`;
ALTER TABLE `zt_block` ADD `createdDate` date NULL AFTER `height`;
ALTER TABLE `zt_block` CHANGE `block` `code` varchar(30) NOT NULL DEFAULT '' AFTER `module`;
ALTER TABLE `zt_block` MODIFY `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `hidden`;

ALTER TABLE `zt_todo`  CHANGE `idvalue` `objectID` mediumint(8) unsigned default '0' NOT NULL AFTER `type`;
ALTER TABLE `zt_todo` CHANGE `config` `config` VARCHAR(1000) NOT NULL  DEFAULT '';

UPDATE `zt_block` SET `dashboard` = `module`;
UPDATE `zt_block` SET `module` = IF(`source` != '', `source`, `code`);
DROP INDEX account_vision_module_type_order ON `zt_block`;

ALTER TABLE `zt_block` DROP COLUMN `source`;
ALTER TABLE `zt_block` DROP COLUMN `type`;

ALTER TABLE `zt_project` ADD `stageBy` enum('project', 'product') NOT NULL DEFAULT 'product' AFTER `division`;
UPDATE `zt_project` SET `stageBy` = 'project' WHERE `division` = '0';
UPDATE `zt_project` SET `stageBy` = 'product' WHERE `division` = '1';
ALTER TABLE `zt_project` DROP `division`;

UPDATE `zt_grouppriv` SET `method` = 'sort' WHERE `module` = 'product' AND `method` = 'updateOrder';
