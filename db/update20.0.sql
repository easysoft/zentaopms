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

UPDATE `zt_grouppriv` SET module='researchtask', `method`='create'         WHERE module='marketresearch' AND `method`='createTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='edit'           WHERE module='marketresearch' AND `method`='editTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='close'          WHERE module='marketresearch' AND `method`='closeTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='start'          WHERE module='marketresearch' AND `method`='startTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='finish'         WHERE module='marketresearch' AND `method`='finishTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='delete'         WHERE module='marketresearch' AND `method`='deleteTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='cancel'         WHERE module='marketresearch' AND `method`='cancelTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='activate'       WHERE module='marketresearch' AND `method`='activateTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='assignTo'       WHERE module='marketresearch' AND `method`='taskAssignTo';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='view'           WHERE module='marketresearch' AND `method`='viewTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='batchCreate'    WHERE module='marketresearch' AND `method`='batchCreateTask';
UPDATE `zt_grouppriv` SET module='researchtask', `method`='recordWorkhour' WHERE module='marketresearch' AND `method`='recordTaskEstimate';

UPDATE `zt_grouppriv` SET module='marketresearch', `method`='task'   WHERE module='marketresearch' AND `method`='stage';
