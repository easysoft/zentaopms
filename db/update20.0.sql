UPDATE `zt_block` SET `module` = 'scrumProject' WHERE `module` = 'project' and type = 'scrum';
UPDATE `zt_block` SET `module` = 'kanbanProject' WHERE `module` = 'project' and type = 'kanban';
UPDATE `zt_block` SET `module` = 'waterfallProject' WHERE `module` = 'project' and type = 'waterfall';
DROP INDEX account_vision_module_type_order ON `zt_block`;
CREATE UNIQUE INDEX `account_vision_module_order` ON `zt_block`(`account`,`vision`,`module`,`order`);

ALTER TABLE `zt_block` CHANGE `module` `dashboard` varchar(20) NOT NULL DEFAULT '' AFTER `account`;
ALTER TABLE `zt_block` DROP `type`;
ALTER TABLE `zt_block` DROP `source`;
ALTER TABLE `zt_block` CHANGE `block` `code` varchar(30) NOT NULL DEFAULT '' AFTER `dashboard`;
ALTER TABLE `zt_block` MODIFY `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `hidden`;
