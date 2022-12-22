REPLACE INTO `zt_workflow` (`parent`, `child`, `type`, `navigator`, `app`, `position`, `module`, `table`, `name`, `flowchart`, `js`, `css`, `order`, `buildin`, `administrator`, `desc`, `version`, `status`, `approval`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `titleField`, `contentField`) VALUES
('', '', 'flow', 'secondary', 'ticket', '', 'ticket', 'zt_ticket', '工单', '', '', '', 0, 1, '', '', '1.0', 'normal', 'disabled', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', '', ''),
('', '', 'flow', 'secondary', 'ticket', '', 'ticket', 'zt_ticket', '工单', '', '', '', 0, 1, '', '', '1.0', 'normal', 'disabled', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', '', '');

REPLACE INTO `zt_workflowfield` (`module`, `field`, `type`, `length`, `name`, `control`, `expression`, `options`, `default`, `rules`, `placeholder`, `canExport`, `canSearch`, `isValue`, `order`, `searchOrder`, `exportOrder`, `buildin`, `role`, `desc`, `readonly`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('ticket', 'id', 'mediumint', '8', '编号', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'product', 'mediumint', '8', '所属产品', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'module', 'mediumint', '8', '所属模块', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'title', 'varchar', '255', '标题', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'type', 'varchar', '30', '类型', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'desc', 'text', '', '描述', 'textarea', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'openedBuild', 'varchar', '255', '影响版本', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'feedback', 'mediumint', '8', '来源反馈', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'assignedTo', 'varchar', '255', '指派给', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'assignedDate', 'datetime', '', '指派日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'realStarted', 'datetime', '', '实际开始', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'startedBy', 'varchar', '255', '由谁开始', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'startedDate', 'datetime', '', '开始日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'deadline', 'date', '', '截止日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'pri', 'tinyint', '3', '优先级', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'estimate', 'float', '', '最初预计', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'left', 'float', '', '预计剩余', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'status', 'varchar', '255', '状态', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'subStatus', 'varchar', '30', '子状态', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 0, 'buildin', '', '0', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'openedBy', 'varchar', '30', '由谁创建', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'openedDate', 'datetime', '', '创建日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'activatedCount', 'int', '10', '激活次数', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'activatedBy', 'varchar', '30', '由谁激活', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'activatedDate', 'datetime', '', '激活日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'closedBy', 'varchar', '30', '由谁关闭', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'closedDate', 'datetime', '', '关闭日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'closedReason', 'varchar', '30', '关闭原因', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'finishedBy', 'varchar', '30', '由谁完成', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'finishedDate', 'datetime', '', '完成日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'resolvedBy', 'varchar', '30', '由谁解决', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'resolvedDate', 'datetime', '', '解决日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'resolution', 'varchar', '1000', '解决方案', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'editedBy', 'varchar', '30', '由谁编辑', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'editedDate', 'datetime', '', '编辑日期', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'keywords', 'varchar', '255', '关键词', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'repeatTicket', 'mediumint', '8', '重复工单', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'mailto', 'varchar', '255', '抄送给', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'consumed', 'float', '', '消耗', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('ticket', 'deleted', 'enum', '', '是否删除', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00');

REPLACE INTO `zt_workflowaction` (`module`, `action`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `role`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `method`) VALUES
('ticket', 'browse', '浏览工单', 'batch', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'browse'),
('ticket', 'create', '创建工单', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'create'),
('ticket', 'edit', '编辑工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'edit'),
('ticket', 'start', '开始工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'finish', '完成工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'close', '关闭工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'view', '工单详情', 'single', 'different', 'none', 'normal', 'browse', 'side', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'view'),
('ticket', 'browse', '浏览工单', 'batch', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'browse'),
('ticket', 'create', '创建工单', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'create'),
('ticket', 'edit', '编辑工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'edit'),
('ticket', 'start', '开始工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'finish', '完成工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'close', '关闭工单', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'operate'),
('ticket', 'view', '工单详情', 'single', 'different', 'none', 'normal', 'browse', 'side', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'lite', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'view');

UPDATE `zt_workflowfield` SET `name` = '类型'  WHERE `module` = 'feedback' and `field` = 'type';
UPDATE `zt_workflowfield` SET `name` = '创建者'  WHERE `module` = 'feedback' and `field` = 'openedBy';
UPDATE `zt_workflowfield` SET `name` = '创建时间'  WHERE `module` = 'feedback' and `field` = 'openedDate';
UPDATE `zt_workflowfield` SET `name` = '反馈邮箱'  WHERE `module` = 'feedback' and `field` = 'notifyEmail';
UPDATE `zt_workflowfield` SET `name` = '最后操作'  WHERE `module` = 'feedback' and `field` = 'editedBy';
UPDATE `zt_workflowfield` SET `name` = '最后操作时间'  WHERE `module` = 'feedback' and `field` = 'editedDate';

INSERT INTO `zt_workflowfield` (`module`, `field`, `type`, `length`, `name`, `control`, `expression`, `options`, `default`, `rules`, `placeholder`, `canExport`, `canSearch`, `isValue`, `order`, `searchOrder`, `exportOrder`, `buildin`, `role`, `desc`, `readonly`, `createdBy`, `createdDate`, `editedBy`, `editedDate`) VALUES
('feedback', 'pri', 'tinyint', '3', '优先级', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('feedback', 'source', 'varchar', '30', '来源公司', 'input', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('feedback', 'repeatFeedback', 'varchar', '30', '重复反馈', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('feedback', 'activatedBy', 'varchar', '30', '由谁激活', 'select', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00'),
('feedback', 'activatedDate', 'datetime', '', '激活时间', 'datetime', '', '', '', '', '', '0', '0', '0', 1, 0, 0, 1, 'buildin', '', '1', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00');

INSERT INTO `zt_workflowaction` (`module`, `action`, `name`, `type`, `batchMode`, `extensionType`, `open`, `position`, `layout`, `show`, `order`, `buildin`, `role`, `virtual`, `conditions`, `verifications`, `hooks`, `linkages`, `js`, `css`, `toList`, `blocks`, `desc`, `status`, `vision`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `method`) VALUES
('feedback', 'activate', '激活反馈', 'single', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'activate'),
('feedback', 'import', '导入', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'import'),
('feedback', 'exporttemplate', '导出模板', 'single', 'different', 'none', 'normal', 'browse', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'exporttemplate'),
('feedback', 'batchclose', '批量关闭’’', 'batch', 'different', 'none', 'normal', 'browseandview', 'normal', 'direct', 0, 1, 'buildin', 0, '', '', '', '', '', '', '', '', '', 'enable', 'rnd', 'admin', '2022-12-19 14:13:30', '', '0000-00-00 00:00:00', 'batchclose');
