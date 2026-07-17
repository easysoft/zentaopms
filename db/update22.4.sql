INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('lang', '任务模式', 'taskMode',      '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'taskMode',      '', '', ''),
('lang', '需求类型', 'storyCategory', '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'storyCategory', '', '', ''),
('sql',  '相关文档', 'taskDocs',      '1', 'or',  'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_doc WHERE `deleted`=\'0\'',       'view_datasource_taskDocs',  'id', 'title'),
('sql',  '所属场景', 'caseScene',     '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_scene WHERE `deleted`=\'0\'',     'view_datasource_caseScene', 'id', 'title'),
('lang', '注入环节', 'bugInjection',  '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'bugInjection', '', '', ''),
('lang', '发现环节', 'bugIdentify',   '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'bugIdentify',  '', '', '');

UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'taskMode'      LIMIT 1) WHERE `module` = 'task'  AND `field` = 'mode';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'storyCategory' LIMIT 1) WHERE `module` = 'story' AND `field` = 'category';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'taskDocs'      LIMIT 1) WHERE `module` = 'task'  AND `field` = 'docs';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'caseScene'     LIMIT 1) WHERE `module` = 'testcase' AND `field` = 'scene';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'bugInjection'  LIMIT 1) WHERE `module` = 'bug'  AND `field` = 'injection';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'bugIdentify'   LIMIT 1) WHERE `module` = 'bug'  AND `field` = 'identify';

ALTER TABLE `zt_workflowlayout` ADD `foldable`   tinyint unsigned NOT NULL DEFAULT 0 AFTER `mobileShow`;
ALTER TABLE `zt_workflowlayout` ADD `wrapBefore` tinyint unsigned NOT NULL DEFAULT 0 AFTER `foldable`;
ALTER TABLE `zt_workflowlayout` ADD `wrapAfter`  tinyint unsigned NOT NULL DEFAULT 0 AFTER `wrapBefore`;

DROP VIEW IF EXISTS `view_datasource_taskDocs`;
DROP VIEW IF EXISTS `view_datasource_caseScene`;

CREATE VIEW `view_datasource_taskDocs`  AS SELECT `id`,`title` FROM `zt_doc`        WHERE `deleted` = '0';
CREATE VIEW `view_datasource_caseScene` AS SELECT `id`,`title` FROM `zt_scene`      WHERE `deleted` = '0';
