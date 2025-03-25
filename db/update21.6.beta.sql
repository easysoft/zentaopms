REPLACE INTO `zt_workflowaction` (`module`, `action`, `method`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `role`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('feedback', 'batchcreate', 'batchcreate', '批量创建', 'batch', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'enable', 'rnd', 'admin', '2025-03-24 14:50:45', '', NULL),
('feedback', 'exporttemplate', 'exporttemplate', '导出模板', 'single', 'different', 'none', 'modal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, '', 'enable', 'rnd', 'admin', '2025-03-24 14:50:45', '', NULL),
('feedback', 'import', 'import', '导入', 'single', 'different', 'none', 'modal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'enable', 'rnd', 'admin', '2025-03-24 14:50:45', '', NULL),
('feedback', 'showimport', 'showimport', '显示导入内容', 'single', 'different', 'none', 'modal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, 'enable', 'rnd', 'admin', '2025-03-24 14:50:45', '', NULL);

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('option', '颜色', 'color',     '1', 'rnd',  'admin', '1970-01-01 00:00:01', '{"#ef4444":"#ef4444","#f97316":"#f97316","#eab308":"#eab308","#84cc16":"#84cc16","#22c55e":"#22c55e","#14b8a6":"#14b8a6","#0ea5e9":"#0ea5e9","#6366f1":"#6366f1","#a855f7":"#a855f7","#d946ef":"#d946ef","#ec4899":"#ec4899"}', '', '', ''),
('option', '颜色', 'litecolor', '1', 'lite', 'admin', '1970-01-01 00:00:01', '{"#ef4444":"#ef4444","#f97316":"#f97316","#eab308":"#eab308","#84cc16":"#84cc16","#22c55e":"#22c55e","#14b8a6":"#14b8a6","#0ea5e9":"#0ea5e9","#6366f1":"#6366f1","#a855f7":"#a855f7","#d946ef":"#d946ef","#ec4899":"#ec4899"}', '', '', '');

UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'color' limit 1) WHERE `module` = 'testcase' AND `field` = 'color';
UPDATE `zt_workflowfield` SET `control` = 'select', `options` = (SELECT id FROM `zt_workflowdatasource` WHERE `code` = 'bugs'  limit 1) WHERE `module` = 'testcase' AND `field` = 'fromBug';
DELETE FROM `zt_workflowfield` WHERE `module` = 'testcase' AND `field` = 'order';
DELETE FROM `zt_workflowfield` WHERE `module` = 'testcase' AND `field` = 'frequency';
