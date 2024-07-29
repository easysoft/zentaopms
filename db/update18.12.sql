INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `datasource`, `view`, `keyField`, `valueField`, `buildin`, `vision`) VALUES
('lang', '反馈优先级', 'feedbackPri', 'feedbackPri', '', '', '', 1, 'rnd'),
('lang', '反馈优先级', 'litefeedbackclosedPri', 'feedbackPri', '', '', '', 1, 'lite');

INSERT INTO `zt_workflowfield` (`module`, `field`, `type`, `length`, `name`, `control`, `expression`, `options`, `default`, `rules`, `placeholder`, `order`, `searchOrder`, `exportOrder`, `canExport`, `canSearch`, `isValue`, `readonly`, `buildin`, `role`) VALUES
('feedback', 'pri', 'tinyint', '3', '优先级', 'select', NULL, 'feedbackPri', '', '', '', 8, 0, 0, '0', '0', '0', '0', 1, 'buildin'),
('feedback', 'activatedBy', 'varchar', '30', '由谁激活', 'select', NULL, 'user', '', '', '', 31, 0, 0, '0', '0', '0', '1', 1, 'buildin'),
('feedback', 'activatedDate', 'datetime', '', '激活时间', 'datetime', NULL, '', '', '', '', 32, 0, 0, '0', '0', '0', '1', 1, 'buildin'),
('feedback', 'repeatFeedback', 'mediumint', '8', '重复反馈', 'input', NULL, '', '', '', '', 34, 0, 0, '0', '0', '0', '1', 1, 'buildin'),
('feedback', 'keywords', 'varchar', '255', '关键词', 'select', NULL, '', '', '', '', 36, 0, 0, '0', '0', '0', '1', 1, 'buildin');

UPDATE `zt_workflowdatasource` SET `datasource` = '{\"app\":\"system\",\"module\":\"tree\",\"method\":\"getOptionMenu\",\"methodDesc\":\"Create an option menu in html.\",\"params\":[{\"name\":\"rootID\",\"type\":\"int\",\"desc\":\"\",\"sessionKey\": \r\n \"feedbackProduct\",\"value\":\"0\"},{\"name\":\"type\",\"type\":\"string\",\"desc\":\"\",\"value\":\"feedback\"},{\"name\":\"startModule\",\"type\":\"int\",\"desc\":\"\",\"value\":\"0\"},{\"name\":\"branch\",\"type\":\"\",\"desc\":\"\",\"value\":\"0\"}]}' WHERE `id` = '42';
