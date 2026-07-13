INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('lang', '任务模式', 'taskMode',      '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'taskMode',      '', '', ''),
('lang', '需求类型', 'storyCategory', '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'storyCategory', '', '', '');

UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'taskMode'      LIMIT 1) WHERE `module` = 'task'  AND `field` = 'mode';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'storyCategory' LIMIT 1) WHERE `module` = 'story' AND `field` = 'category';

ALTER TABLE `zt_workflowlayout` ADD `foldable`   tinyint unsigned NOT NULL DEFAULT 0 AFTER `mobileShow`;
ALTER TABLE `zt_workflowlayout` ADD `wrapBefore` tinyint unsigned NOT NULL DEFAULT 0 AFTER `foldable`;
ALTER TABLE `zt_workflowlayout` ADD `wrapAfter`  tinyint unsigned NOT NULL DEFAULT 0 AFTER `wrapBefore`;
