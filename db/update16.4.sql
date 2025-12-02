ALTER TABLE `zt_userquery` ADD `common` enum('0','1') NOT NULL DEFAULT '0';

ALTER TABLE `zt_project` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `order`;
ALTER TABLE `zt_product` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `order`;
ALTER TABLE `zt_task` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `deleted`;
ALTER TABLE `zt_projectstory` ADD INDEX `story` (`story`);
ALTER TABLE `zt_group` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `project`;
ALTER TABLE `zt_lang` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `system`;
ALTER TABLE `zt_lang` DROP INDEX `lang`, ADD UNIQUE `lang` (`lang`,`module`,`section`,`key`,`vision`);
ALTER TABLE `zt_doc` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `id`;
ALTER TABLE `zt_doclib` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `type`;
ALTER TABLE `zt_action` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `read`;
ALTER TABLE `zt_searchindex` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `id`;
ALTER TABLE `zt_config` ADD `vision` varchar(10) NOT NULL DEFAULT '' AFTER `id`;
ALTER TABLE `zt_config` DROP INDEX `unique`, ADD UNIQUE `unique` (`vision`,`owner`,`module`,`section`,`key`);
ALTER TABLE `zt_todo` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `deleted`;
ALTER TABLE `zt_block` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `account`;
ALTER TABLE `zt_block` DROP INDEX `account_module_type_order`, ADD UNIQUE `account_vision_module_type_order` (`account`, `vision`, `module`, `type`, `order`);
ALTER TABLE `zt_effort` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `id`;
ALTER TABLE `zt_story` ADD `vision` varchar(10) NOT NULL DEFAULT 'rnd' AFTER `id`;

ALTER TABLE `zt_story` ADD `activatedDate` datetime NOT NULL AFTER `closedReason`;
ALTER TABLE `zt_task` MODIFY `activatedDate` datetime NOT NULL AFTER `lastEditedDate`;

ALTER TABLE `zt_kanbancard` ADD `progress` float unsigned NOT NULL DEFAULT '0' AFTER `estimate`;
UPDATE `zt_kanbancard` SET `progress`='100' WHERE `status` = 'done' and `fromtype` = '';

ALTER TABLE `zt_user` ADD `visions` varchar(20) NOT NULL DEFAULT 'rnd,lite' AFTER `visits`;
UPDATE `zt_user` SET `visions`='rnd,lite';
UPDATE `zt_group` SET `vision`='lite', `project`='0' WHERE `role` = 'feedback';

DELETE FROM `zt_group` WHERE `vision` = 'lite' AND `role` IN ('liteAdmin','liteProject','liteTeam');

INSERT INTO `zt_group` (`vision`, `name`, `role`, `desc`) VALUES('lite', '管理员', 'liteAdmin', '运营管理界面用户分组');
INSERT INTO `zt_group` (`vision`, `name`, `role`, `desc`) VALUES('lite', '项目管理', 'liteProject', '运营管理界面用户分组');
INSERT INTO `zt_group` (`vision`, `name`, `role`, `desc`) VALUES('lite', '团队成员', 'liteTeam', '运营管理界面用户分组');

ALTER TABLE `zt_productplan` ADD `closedReason` varchar(20) NOT NULL AFTER `order`;

update zt_config set `value` = concat(`value`, ',visions') where module = 'user' and `key` = 'requiredFields' and section in ('create', 'edit');

ALTER TABLE `zt_kanban` CHANGE `order` `order` mediumint NOT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `zt_kanbancolumn` CHANGE `group` `group` mediumint NOT NULL DEFAULT '0' AFTER `region`;
ALTER TABLE `zt_kanbancard` CHANGE `order` `order` mediumint NOT NULL DEFAULT '0' AFTER `whitelist`;
ALTER TABLE `zt_kanbanregion` CHANGE `order` `order` mediumint NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_kanbanspace` CHANGE `order` `order` mediumint NOT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `zt_projectstory` CHANGE `branch` `branch` mediumint unsigned NOT NULL AFTER `product`;

ALTER TABLE `zt_apistruct` CHANGE `desc` `desc` text NOT NULL DEFAULT '' AFTER `type`;

ALTER TABLE `zt_mr` ADD `squash` ENUM('0','1') NOT NULL DEFAULT '0' AFTER `removeSourceBranch`;
