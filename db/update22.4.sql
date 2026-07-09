INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('lang', '任务模式', 'taskMode', '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'taskMode', '', '', '');

UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'taskMode') WHERE `module` = 'task'   AND `field` = 'mode';