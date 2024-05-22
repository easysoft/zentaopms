CREATE TABLE IF NOT EXISTS `zt_ai_assistant` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(30) NOT NULL,
    `modelId` mediumint(8) unsigned NOT NULL,
    `desc` text NOT NULL,
    `systemMessage` text NOT NULL,
    `greetings` text NOT NULL,
    `icon` varchar(30) DEFAULT 'coding-1' NOT NULL,
    `enabled` enum('0', '1') NOT NULL DEFAULT '1',
    `createdDate` datetime NOT NULL,
    `publishedDate` datetime DEFAULT NULL,
    `deleted` enum('0','1') NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zt_kanbancell` MODIFY `cards` mediumtext NULL;
ALTER TABLE `zt_user` MODIFY `ip` varchar(255) NOT NULL DEFAULT '';

DELETE FROM `zt_config` WHERE `owner`='system' AND `module`='custom' AND `key`='productProject';

ALTER TABLE `zt_chart` ADD `code` varchar(255) not NULL default '' AFTER `name`;
ALTER TABLE `zt_pivot` ADD `code` varchar(255) not NULL default '' AFTER `group`;

UPDATE `zt_kanbancard` SET `color` = '#937c5a' WHERE `color` = '#b10b0b';
UPDATE `zt_kanbancard` SET `color` = '#fc5959' WHERE `color` = '#cfa227';
UPDATE `zt_kanbancard` SET `color` = '#ff9f46' WHERE `color` = '#2a5f29';

INSERT INTO `zt_cron`(`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('0', '*/1', '*', '*', '*', 'moduleName=metric&methodName=updateDashboardMetricLib', '计算仪表盘数据', 'zentao', 1, 'normal', NUll);

UPDATE `zt_todo` SET `type` = 'custom' WHERE `type` = 'cycle' AND `cycle` = 0;
