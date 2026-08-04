INSERT INTO `zt_workflowdatasource` (`type`, `name`, `code`, `buildin`, `vision`, `createdBy`, `createdDate`, `datasource`, `view`, `keyField`, `valueField`) VALUES
('lang', '任务模式', 'taskMode',            '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'taskMode',      '', '', ''),
('lang', '需求类型', 'storyCategory',       '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'storyCategory', '', '', ''),
('sql',  '相关文档', 'taskDocs',            '1', 'or',  'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_doc WHERE `deleted`=\'0\'',   'view_datasource_taskDocs',  'id', 'title'),
('sql',  '所属场景', 'caseScene',           '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'SELECT `id`,`title` FROM zt_scene WHERE `deleted`=\'0\'', 'view_datasource_caseScene', 'id', 'title'),
('lang', '注入环节', 'bugInjection',        '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'bugInjection', '', '', ''),
('lang', '发现环节', 'bugIdentify',         '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'bugIdentify',  '', '', ''),
('lang', '关闭原因', 'ticketClosedReason',  '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'ticketClosedReason', '', '', ''),
('lang', '反馈优先级','feedbackPri',        '1', 'rnd', 'admin', '1970-01-01 00:00:01', 'feedbackPri',  '', '', '');

UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'taskMode'           LIMIT 1) WHERE `module` = 'task'     AND `field` = 'mode';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'storyCategory'      LIMIT 1) WHERE `module` = 'story'    AND `field` = 'category';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'taskDocs'           LIMIT 1) WHERE `module` = 'task'     AND `field` = 'docs';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'caseScene'          LIMIT 1) WHERE `module` = 'testcase' AND `field` = 'scene';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'bugInjection'       LIMIT 1) WHERE `module` = 'bug'      AND `field` = 'injection';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'bugIdentify'        LIMIT 1) WHERE `module` = 'bug'      AND `field` = 'identify';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'ticketClosedReason' LIMIT 1) WHERE `module` = 'ticket'   AND `field` = 'closedReason';
UPDATE `zt_workflowfield` SET `options` = (SELECT `id` FROM `zt_workflowdatasource` WHERE `code` = 'feedbackPri'        LIMIT 1) WHERE `module` = 'feedback' AND `field` = 'pri';

DROP VIEW IF EXISTS `view_datasource_taskDocs`;
DROP VIEW IF EXISTS `view_datasource_caseScene`;

CREATE VIEW `view_datasource_taskDocs`  AS SELECT `id`,`title` FROM `zt_doc`   WHERE `deleted` = '0';
CREATE VIEW `view_datasource_caseScene` AS SELECT `id`,`title` FROM `zt_scene` WHERE `deleted` = '0';

DELETE FROM zt_workflowfield WHERE `module`='ticket'   AND `field`='consumed';
DELETE FROM zt_workflowfield WHERE `module`='ticket'   AND `field`='deleted';
DELETE FROM zt_workflowfield WHERE `module`='feedback' AND `field`='faq';
DELETE FROM zt_workflowfield WHERE `module`='feedback' AND `field`='deleted';

UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'assignedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'realStarted';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'startedDate';
UPDATE `zt_workflowfield` SET `control` = 'date'     WHERE `module` = 'ticket'   AND `field` = 'deadline';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'openedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'activatedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'closedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'finishedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'editedDate';
UPDATE `zt_workflowfield` SET `control` = 'datetime' WHERE `module` = 'ticket'   AND `field` = 'resolvedDate';
UPDATE `zt_workflowfield` SET `control` = 'checkbox' WHERE `module` = 'feedback' AND `field` = 'public';

UPDATE `zt_workflowaction` SET `action` = 'assignTo' WHERE `module` = 'ticket'   AND `action` = 'assign';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'testcase' AND `action` = 'batchcreate';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'testcase' AND `action` = 'batchedit';
UPDATE `zt_workflowaction` SET `type`   = 'batch'    WHERE `module` = 'ticket'   AND `action` = 'batchedit';

ALTER TABLE `zt_workflowlayout` ADD `block`      text DEFAULT NULL AFTER `position`;
ALTER TABLE `zt_workflowlayout` ADD `foldable`   tinyint unsigned NOT NULL DEFAULT 0 AFTER `mobileShow`;
ALTER TABLE `zt_workflowlayout` ADD `wrapBefore` tinyint unsigned NOT NULL DEFAULT 0 AFTER `foldable`;
ALTER TABLE `zt_workflowlayout` ADD `wrapAfter`  tinyint unsigned NOT NULL DEFAULT 0 AFTER `wrapBefore`;

UPDATE `zt_workflowlayout` SET `action` = 'assignTo' WHERE `module` = 'ticket'   AND `action` = 'assign';
