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
