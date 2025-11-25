ALTER TABLE `zt_compile` CHANGE `status` `status` varchar(100) NOT NULL DEFAULT '';
ALTER TABLE `zt_measqueue` CHANGE `status` `status` varchar(100) NOT NULL DEFAULT '';

REPLACE INTO `zt_workflowaction` (`module`, `action`, `method`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `role`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('feedback', 'batchcreate', 'batchcreate', '批量创建', 'batch', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'enable', 'rnd', 'admin', '2025-03-24 14:50:45', '', NULL),
('feedback', 'exporttemplate', 'exporttemplate', '导出模板', 'single', 'different', 'none', 'modal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, '', 'enable', 'rnd', 'admin', '2025-03-24 14:50:45', '', NULL),
('feedback', 'import', 'import', '导入', 'single', 'different', 'none', 'modal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'enable', 'rnd', 'admin', '2025-03-24 14:50:45', '', NULL),
('feedback', 'showimport', 'showimport', '显示导入内容', 'single', 'different', 'none', 'modal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'enable', 'rnd', 'admin', '2025-03-24 14:50:45', '', NULL);

DELETE FROM `zt_workflowdatasource` WHERE `code` IN ('color', 'litecolor');
INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('option', '颜色', 'color',     '1', 'rnd',  'admin', '1970-01-01 00:00:01', '{"#ef4444":"#ef4444","#f97316":"#f97316","#eab308":"#eab308","#84cc16":"#84cc16","#22c55e":"#22c55e","#14b8a6":"#14b8a6","#0ea5e9":"#0ea5e9","#6366f1":"#6366f1","#a855f7":"#a855f7","#d946ef":"#d946ef","#ec4899":"#ec4899"}', '', '', ''),
('option', '颜色', 'litecolor', '1', 'lite', 'admin', '1970-01-01 00:00:01', '{"#ef4444":"#ef4444","#f97316":"#f97316","#eab308":"#eab308","#84cc16":"#84cc16","#22c55e":"#22c55e","#14b8a6":"#14b8a6","#0ea5e9":"#0ea5e9","#6366f1":"#6366f1","#a855f7":"#a855f7","#d946ef":"#d946ef","#ec4899":"#ec4899"}', '', '', '');

UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'color'     limit 1) WHERE `module` = 'testcase'    AND `field` = 'color';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'bugs'      limit 1) WHERE `module` = 'testcase'    AND `field` = 'fromBug';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'color'     limit 1) WHERE `module` = 'story'       AND `field` = 'color';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'bugs'      limit 1) WHERE `module` = 'story'       AND `field` = 'fromBug';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'feedbacks' limit 1) WHERE `module` = 'story'       AND `field` = 'feedback';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'stories'   limit 1) WHERE `module` = 'story'       AND `field` = 'duplicateStory';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'color'     limit 1) WHERE `module` = 'requirement' AND `field` = 'color';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'feedbacks' limit 1) WHERE `module` = 'requirement' AND `field` = 'feedback';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'stories'   limit 1) WHERE `module` = 'requirement' AND `field` = 'duplicateStory';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'color'     limit 1) WHERE `module` = 'epic'        AND `field` = 'color';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'feedbacks' limit 1) WHERE `module` = 'epic'        AND `field` = 'feedback';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'stories'   limit 1) WHERE `module` = 'epic'        AND `field` = 'duplicateStory';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'color'     limit 1) WHERE `module` = 'bug'         AND `field` = 'color';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'color'     limit 1) WHERE `module` = 'task'        AND `field` = 'color';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'bugs'      limit 1) WHERE `module` = 'task'        AND `field` = 'fromBug';
UPDATE `zt_workflowfield` SET `name` = REPLACE(`name`, '版本', '代码') WHERE `module` = 'task' AND `field` = 'repo';
DELETE FROM `zt_workflowfield` WHERE `module` = 'testcase'    AND `field` in ('order', 'frequency');
DELETE FROM `zt_workflowfield` WHERE `module` = 'product'     AND `field` in ('order', 'createdVersion');
DELETE FROM `zt_workflowfield` WHERE `module` = 'story'       AND `field` in ('childStories', 'linkStories');
DELETE FROM `zt_workflowfield` WHERE `module` = 'requirement' AND `field` in ('childStories', 'linkStories', 'toBug', 'fromBug');
DELETE FROM `zt_workflowfield` WHERE `module` = 'epic'        AND `field` in ('childStories', 'linkStories', 'toBug', 'fromBug');
DELETE FROM `zt_workflowfield` WHERE `module` = 'bug'         AND `field` = 'storyVersion';
DELETE FROM `zt_workflowfield` WHERE `module` = 'task'        AND `field` in ('storyVersion', 'designVersion', 'v1', 'v2', 'vision');
DELETE FROM `zt_workflowfield` WHERE `module` = 'project'     AND `field` = 'project';
DELETE FROM `zt_workflowfield` WHERE `module` in ('project', 'execution') AND `field` in ('budgetUnit', 'output', 'path', 'grade', 'version', 'parentVersion', 'openedVersion', 'order', 'vision', 'team');

UPDATE `zt_workflowfield` SET `control` = 'multi-select' WHERE `module` = 'testcase' AND `field` = 'stage';
UPDATE `zt_workflowfield` SET `control` = 'multi-select' WHERE `module` = 'bug' AND `field` = 'os';
UPDATE `zt_workflowfield` SET `control` = 'multi-select' WHERE `module` = 'bug' AND `field` = 'browser';
UPDATE `zt_workflowfield` SET `control` = 'select'       WHERE `module` = 'project' AND `field` = 'model';
UPDATE `zt_workflowfield` SET `control` = 'select'       WHERE `module` = 'project' AND `field` = 'type';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = "{\"extend\":\"\\u7ee7\\u627f\",\"reset\":\"\\u91cd\\u65b0\\u5b9a\\u4e49\"}" WHERE `module` = 'project' AND `field` = 'auth';

UPDATE `zt_workflowaction` SET `layout` = 'side' WHERE `module` = 'ticket' AND `action` = 'edit';

DELETE FROM `zt_block` WHERE `dashboard` = 'my' AND `module` = 'product' AND `code` IN ('overview', 'statistic') AND `vision` = 'lite';
DELETE FROM `zt_block` WHERE `dashboard` = 'my' AND `module` = 'qa' AND `code` = 'statistic' AND `vision` = 'lite';

-- DROP TABLE IF EXISTS `zt_charterproduct`;
CREATE TABLE IF NOT EXISTS `zt_charterproduct` (
  `charter` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `product` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `branch` mediumint(8) unsigned NOT NULL DEFAULT '0',
  `plan` varchar(255) NOT NULL DEFAULT '',
  `roadmap` varchar(255) NOT NULL DEFAULT ''
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `charter_product` ON `zt_charterproduct` (`charter`, `product`, `branch`);
