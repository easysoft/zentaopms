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

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('lang', '工单类型',           'ticketType',     '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'ticketType', '', '', ''),
('lang', '工单优先级',         'ticketPri',      '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'ticketPri', '', '', ''),
('lang', '工单状态',           'ticketStatus',   '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'ticketStatus', '', '', ''),
('lang', '需求优先级',   'demandPri',      '1', 'or', 'admin', '1970-01-01 00:00:01', 'demandPri', '', '', ''),
('lang', '需求来源',     'demandSource',   '1', 'or', 'admin', '1970-01-01 00:00:01', 'demandSource', '', '', ''),
('lang', '需求类别',     'demandCategory', '1', 'or', 'admin', '1970-01-01 00:00:01', 'demandCategory', '', '', ''),
('lang', '需求状态',     'demandStatus',   '1', 'or', 'admin', '1970-01-01 00:00:01', 'demandStatus', '', '', ''),
('lang', '需求管理周期', 'demandDuration', '1', 'or', 'admin', '1970-01-01 00:00:01', 'demandDuration', '', '', ''),
('lang', '需求BSA',      'demandBSA',      '1', 'or', 'admin', '1970-01-01 00:00:01', 'demandBSA', '', '', '');

UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'tasks'), `control` = 'select' WHERE `module` = 'task' AND `field` = 'parent';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'ticketStatus'), `control` = 'select' WHERE `module` = 'ticket' AND `field` = 'status';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'ticketType') WHERE `module` = 'ticket' AND `field` = 'type';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'ticketPri')  WHERE `module` = 'ticket' AND `field` = 'pri';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandPri')      WHERE `module` = 'demand' AND `field` = 'pri';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandSource')   WHERE `module` = 'demand' AND `field` = 'source';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandCategory') WHERE `module` = 'demand' AND `field` = 'category';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandStatus')   WHERE `module` = 'demand' AND `field` = 'status';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandDuration') WHERE `module` = 'demand' AND `field` = 'duration';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'demandBSA')      WHERE `module` = 'demand' AND `field` = 'bsa';

UPDATE `zt_ai_miniprogram` SET `prompt` = '请帮我生成一份职业发展导航，我的教育背景为 <教育背景> ，职位信息为 <职位信息> ，工作经验描述如下： <工作经验> ，掌握的技能为 <掌握技能> ，为了实现 <职业目标> ，我想做一个 <规划时长> 的计划，我有更多感兴趣的领域为 <兴趣领域> ，有更多补充内容 <补充信息> ，来追求相关机会和进一步发展，控制在30字以内。' WHERE `id` = 1;
UPDATE `zt_ai_miniprogramfield` SET `name` = '兴趣领域' WHERE `appID` = 1 AND `name` = '更多感兴趣的领域';

ALTER TABLE `zt_chart` MODIFY COLUMN `type`        varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_chart` MODIFY COLUMN `dataset`     varchar(30) NULL;
ALTER TABLE `zt_chart` MODIFY COLUMN `desc`        text        NULL;
ALTER TABLE `zt_chart` MODIFY COLUMN `settings`    mediumtext  NULL;
ALTER TABLE `zt_chart` MODIFY COLUMN `filters`     mediumtext  NULL;
ALTER TABLE `zt_chart` MODIFY COLUMN `createdBy`   varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_chart` MODIFY COLUMN `createdDate` datetime    NULL;

ALTER TABLE `zt_pivot` MODIFY COLUMN `desc`     text       NULL;
ALTER TABLE `zt_pivot` MODIFY COLUMN `sql`      mediumtext NOT NULL DEFAULT '';
ALTER TABLE `zt_pivot` MODIFY COLUMN `vars`     mediumtext NULL;
ALTER TABLE `zt_pivot` MODIFY COLUMN `settings` mediumtext NULL;
ALTER TABLE `zt_pivot` MODIFY COLUMN `filters`  mediumtext NULL;

ALTER TABLE `zt_project` MODIFY `stageBy` enum('project', 'product') NOT NULL DEFAULT 'product';

ALTER TABLE `zt_bug` MODIFY COLUMN `v1` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_bug` MODIFY COLUMN `v2` varchar(255) NOT NULL DEFAULT '';
