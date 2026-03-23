RENAME TABLE `zt_ai_prompt` TO `zt_ai_agent`;
RENAME TABLE `zt_ai_promptrole` TO `zt_ai_agentrole`;
RENAME TABLE `zt_ai_promptfield` TO `zt_ai_agentfield`;

ALTER TABLE `zt_ai_agent`
ADD COLUMN `code` varchar(30) NOT NULL DEFAULT '' COMMENT '内部 code'
AFTER `id`;

UPDATE `zt_ai_agent`
SET `code` = 'zt_story_polishing'
WHERE `name` = '需求润色' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_story_to_testcase'
WHERE `name` = '一键拆用例' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_task_polishing'
WHERE `name` = '任务润色' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_story_to_task'
WHERE `name` = '需求转任务' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_bug_polishing'
WHERE `name` = 'Bug润色' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_doc_polishing'
WHERE `name` = '文档润色' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_bug_to_story'
WHERE `name` = 'Bug转需求' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_split_productplan'
WHERE `name` = '拆分一个子计划' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_story_review'
WHERE `name` = '需求评审' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_create_doc'
WHERE `name` = '编写开发设计文档智能体' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_story_prototype'
WHERE `name` = '绘制需求原型图智能体' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_release_newsletter'
WHERE `name` = '编写发布新闻稿' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_project_proposal'
WHERE `name` = '编写立项报告' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_project_finalreport'
WHERE `name` = '编写结项报告' AND `createdBy` = 'system';
UPDATE `zt_ai_agent`
SET `code` = 'zt_automatic_test'
WHERE `name` = '编写自动化测试脚本' AND `createdBy` = 'system';

UPDATE `zt_workflowaction` SET `method` = `action` WHERE `action` IN ('browse', 'create', 'batchcreate', 'edit', 'view', 'delete', 'link', 'unlink', 'export', 'exporttemplate', 'import', 'showimport', 'report');

UPDATE `zt_chart` SET `sql` = 'SELECT\r\n  YEAR(t4.date) AS `year`,\r\n  t1.id,\r\n  t1.name AS project,\r\n  ROUND(\r\n    SUM(t4.consumed),\r\n    2\r\n  ) AS consumed\r\nFROM\r\n  zt_project AS t1\r\n  LEFT JOIN zt_project AS t2 ON t1.id = t2.project\r\n  AND t2.deleted = \'0\'\r\n  AND t2.type IN (\'sprint\', \'stage\', \'kanban\')\r\n  LEFT JOIN zt_task AS t3 ON t2.id = t3.execution\r\n  AND t3.deleted = \'0\'\r\n  AND t3.status != \'cancel\'\r\n  LEFT JOIN zt_effort AS t4 ON t3.id = t4.objectID\r\n  AND t4.deleted = \'0\'\r\n  AND t4.objectType = \'task\'\r\nWHERE\r\n  t1.deleted = \'0\'\r\n  AND t1.type = \'project\'\r\n  AND t4.id IS NOT NULL\r\nGROUP BY\r\n  `year`,\r\n  t1.id,\r\n  t1.name\r\nORDER BY\r\n  `year`,\r\n  consumed DESC' WHERE `id` = '1098';
UPDATE `zt_chart` SET `sql` = 'SELECT\r\n  YEAR(t5.date) AS `year`,\r\n  t1.id,\r\n  t1.name AS program,\r\n  ROUND(\r\n    SUM(t5.consumed),\r\n    2\r\n  ) AS consumed\r\nFROM\r\n  zt_project AS t1\r\n  LEFT JOIN zt_project AS t2 ON FIND_IN_SET(t1.id, t2.path)\r\n  AND t2.deleted = \'0\'\r\n  AND t2.type = \'project\'\r\n  LEFT JOIN zt_project AS t3 ON t2.id = t3.project\r\n  AND t3.deleted = \'0\'\r\n  AND t3.type IN (\'sprint\', \'stage\', \'kanban\')\r\n  LEFT JOIN zt_task AS t4 ON t3.id = t4.execution\r\n  AND t4.deleted = \'0\'\r\n  AND t4.status != \'cancel\'\r\n  LEFT JOIN zt_effort AS t5 ON t4.id = t5.objectID\r\n  AND t5.deleted = \'0\'\r\n  AND t5.objectType = \'task\'\r\nWHERE\r\n  t1.deleted = \'0\'\r\n  AND t1.type = \'program\'\r\n  AND t1.grade = 1\r\n  AND t5.id IS NOT NULL\r\nGROUP BY\r\n  `year`,\r\n  t1.id,\r\n  program\r\nORDER BY\r\n  `year`,\r\n  consumed DESC' WHERE `id` = '1087';

UPDATE `zt_workflowfield` SET `name` = '反馈名称' WHERE `module` = 'feedback' AND `field` = 'title' AND `name` = '标题';
UPDATE `zt_workflowfield` SET `name` = '工单名称' WHERE `module` = 'ticket' AND `field` = 'title' AND `name` = '标题';
UPDATE `zt_workflowfield` SET `name` = 'Feedback Title' WHERE `module` = 'feedback' AND `field` = 'title' AND `name` = 'Title';
UPDATE `zt_workflowfield` SET `name` = 'Ticket Title' WHERE `module` = 'ticket' AND `field` = 'title' AND `name` = 'Title';
