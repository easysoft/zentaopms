ALTER TABLE `zt_approvalnode` CHANGE `date` `date` datetime NULL;

INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `datasource`, `view`, `keyField`, `valueField`, `buildin`, `vision`) VALUES
('lang', '反馈优先级', 'feedbackPri', 'feedbackPri', '', '', '', 1, 'rnd'),
('lang', '反馈优先级', 'litefeedbackclosedPri', 'feedbackPri', '', '', '', 1, 'lite');
