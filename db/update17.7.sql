ALTER TABLE `zt_chart` ADD `group` mediumint(8) unsigned NOT NULL default '0' AFTER `type`;
ALTER TABLE `zt_chart` MODIFY COLUMN `desc` text NOT NULL;
ALTER TABLE `zt_chart` ADD `editedBy` varchar(30) NOT NULL AFTER `createdDate`;
ALTER TABLE `zt_chart` ADD `editedDate` datetime NOT NULL AFTER `editedBy`;

INSERT INTO `zt_module` (`company`, `root`, `branch`, `name`, `parent`, `path`, `grade`, `order`, `type`, `owner`, `collector`, `short`, `deleted`) VALUES
(0, 0, 0, '产品', 0, ',0,', 1, 10, 'bi', '', '', '', '0'),
(0, 0, 0, '项目', 0, ',0,', 1, 20, 'bi', '', '', '', '0'),
(0, 0, 0, '测试', 0, ',0,', 1, 30, 'bi', '', '', '', '0'),
(0, 0, 0, '组织', 0, ',0,', 1, 40, 'bi', '', '', '', '0');

UPDATE `zt_chart` SET `group` = (SELECT `id` FROM `zt_module` WHERE `type` = 'bi' AND `name` = '产品' limit 1);
UPDATE `zt_module` SET `path` = CONCAT(',', `id`, ',') WHERE `type` = 'bi' AND `name` in ('产品', '项目', '测试', '组织');
