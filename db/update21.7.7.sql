UPDATE `zt_risk` SET `pri` = 1 WHERE `pri` = 'high';
UPDATE `zt_risk` SET `pri` = 2 WHERE `pri` = 'middle';
UPDATE `zt_risk` SET `pri` = 3 WHERE `pri` = 'low';

UPDATE `zt_opportunity` SET `pri` = 1 WHERE `pri` = 'high';
UPDATE `zt_opportunity` SET `pri` = 2 WHERE `pri` = 'middle';
UPDATE `zt_opportunity` SET `pri` = 3 WHERE `pri` = 'low';

CREATE TABLE IF NOT EXISTS `zt_ai_knowledgelib` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `vision` varchar(10) NOT NULL DEFAULT 'rnd' COMMENT '所属界面',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '知识库类型，目前包括：我的知识库（my）、组织知识库（team）',
  `importType` varchar(20) NOT NULL DEFAULT '' COMMENT '知识库导入类型，目前包括：从文档库导入（doclib）、从资产库导入（assetlib）',
  `importID` int unsigned NOT NULL DEFAULT 0 COMMENT '知识库导入类型条目对应的导入对象在禅道中的 ID，对应 zt_doclib.id 或 zt_assetlib.id',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '知识库名称',
  `desc` text DEFAULT NULL COMMENT '知识库描述',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建者',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑者',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `published` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否已发布',
  `publishedDate` datetime DEFAULT NULL COMMENT '上次发布时间',
  `publishedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '上次发布者',
  `acl` varchar(10) NOT NULL DEFAULT 'open' COMMENT '权限控制',
  `groups` varchar(255) NOT NULL DEFAULT '' COMMENT '权限控制组',
  `users` text DEFAULT NULL COMMENT '权限控制用户',
  `externalID` varchar(255) NOT NULL DEFAULT '' COMMENT '知识库在外部服务中的 ID，在 ZAI 中对应 memory_id，如果没有 ID，表示未在外部服务中创建对应知识库',
  `syncedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '上次成功同步者',
  `syncedDate` datetime DEFAULT NULL COMMENT '上次成功同步时间，为空表示未同步',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `zt_ai_knowledgeitem` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '知识库内容条目在禅道中的 ID',
  `lib` int unsigned NOT NULL DEFAULT 0 COMMENT '知识库内容条目所属知识库，对应 zt_ai_knowledgelib.id',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT '知识内容类型，目前包括：自定义文本（text）、文件（file）、禅道对象（object）',
  `file` int unsigned NOT NULL DEFAULT 0 COMMENT '文件类型条目对应的文件在禅道中的 ID，对应 zt_file.id',
  `objectType` varchar(30) NOT NULL DEFAULT '' COMMENT '禅道对象类型条目对应的禅道对象在禅道中的类型，例如 bug',
  `objectID` int unsigned NOT NULL DEFAULT 0 COMMENT '禅道对象类型条目对应的禅道对象在禅道中的 ID',
  `objectData` text DEFAULT NULL COMMENT '禅道对象数据，JSON 格式',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
  `content` text DEFAULT NULL COMMENT '知识库内容条目内容，如果是自定义文本，对应的是文本内容，如果是文件则为空，如果是禅道对象，则为禅道对象转为 Markdown 的内容',
  `contentType` varchar(10) NOT NULL DEFAULT 'markdown' COMMENT '内容类型',
  `attrs` text DEFAULT NULL COMMENT '知识库内容条目属性，JSON 格式',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '知识库内容条目创建者',
  `createdDate` datetime DEFAULT NULL COMMENT '知识库内容条目创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '知识库内容条目编辑者',
  `editedDate` datetime DEFAULT NULL COMMENT '知识库内容条目编辑时间',
  `externalID` varchar(255) NOT NULL DEFAULT '' COMMENT '知识库在外部服务中的 ID，在 ZAI 中对应 memory_content_id，如果没有 ID，表示未在外部服务中创建对应的内容条目',
  `syncedDate` datetime DEFAULT NULL COMMENT '上次成功同步时间，为空表示未同步',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

UPDATE `zt_ai_prompt`
SET `desc` = '优化需求中标题、描述和验收标准等字段，使表述清晰准确。'
WHERE `name` = '需求润色' AND `createdBy` = 'system';
UPDATE `zt_ai_prompt`
SET `desc` = '为需求生成一个或多个对应的测试用例。'
WHERE `name` = '一键拆用例' AND `createdBy` = 'system';
UPDATE `zt_ai_prompt`
SET `desc` = '优化任务中标题、描述等字段的表述，使任务表述更清晰准确。'
WHERE `name` = '任务润色' AND `createdBy` = 'system';
UPDATE `zt_ai_prompt`
SET `desc` = '根据需求的标题、描述和验收标准等，将需求转化为对应的可实施的开发任务。'
WHERE `name` = '需求转任务' AND `createdBy` = 'system';
UPDATE `zt_ai_prompt`
SET `desc` = '优化需求中标题、描述和验收标准等字段，使表述清晰准确。'
WHERE `name` = 'Bug润色' AND `createdBy` = 'system';
UPDATE `zt_ai_prompt`
SET `desc` = '优化任务中标题、描述等字段的表述，使任务表述更清晰准确。'
WHERE `name` = '文档润色' AND `createdBy` = 'system';
UPDATE `zt_ai_prompt`
SET `desc` = '根据Bug数据转化为相应的研发需求。'
WHERE `name` = 'Bug转需求' AND `createdBy` = 'system';
UPDATE `zt_ai_prompt`
SET `desc` = '优化需求中标题、描述和验收标准等字段，使表述清晰准确。'
WHERE `name` = '拆分一个子计划' AND `createdBy` = 'system';

INSERT INTO `zt_ai_prompt` (`name`, `desc`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`, `status`) VALUES ('需求评审', '对需求进行结构完整性、逻辑一致性和标准符合性评审，并输出优化改进建议。', 0, 'story', ',story.title,story.spec,story.verify,story.product,story.module,story.pri,story.category,story.estimate,', 'empty.empty', '## 核心专业能力\n\n- 结构完整性分析（角色、动作、目标、约束要素）\n- 逻辑一致性分析（单条及多条需求之间的合理性）\n- 评审标准适配（INVEST、SMART或自定义标准）\n- 优先级归类评审建议（突出最关键问题，辅助次要优化）\n- 正式、清晰、专业的输出风格（无emoji，注重结构）\n\n## 工作风格\n\n- 一轮输出，结构化归类\n- 引导用户先聚焦最关键问题\n- 提供清晰、专业、可操作的完善方向\n- 语言正式、客观，保持体验流畅且不压迫\n\n\n## 评审交互流程\n\n1. 接收需求文本（来源于需求详情点击或输入）\n2. 进行结构与逻辑完整性检查\n3. 按重要性将评审建议归为两大类：\n- 核心优先改进项（必须优先处理）\n- 次要优化建议（在有时间或资源时进一步完善）\n4. 一次性输出完整评审结果，不进行多轮追问\n5. 引导用户先集中处理核心问题，如有余力再逐步优化次要问题\n\n## 输出结构要求\n\n- 统一使用正式Markdown结构，分清主次\n- 首先输出【评审总结】，概述整体需求状态\n- 然后分为两个部分输出：\n- 第一部分：核心优先改进项\n- 第二部分：次要优化建议\n- 每个建议应简明扼要，突出问题与优化方向\n- 不使用emoji或花哨符号，保持专业正式风格\n\n## 输出示范结构\n\n## 需求评审结果总结\n\n本次评审分析显示，需求整体情况如下：\n- 结构完整性：基本完整/存在缺失\n- 逻辑一致性：连贯/存在冲突\n- 标准符合性（如INVEST）：符合/部分符合/存在明显缺口\n\n## 核心优先改进项\n\n以下问题建议优先处理，以保证需求的可实现性与后续交付质量：\n\n1. （最重要问题简述）\n2. （次重要问题简述）\n3. （其他关键问题简述）\n\n## 次要优化建议\n\n在核心问题处理完毕后，可进一步关注以下细节优化：\n\n1. （次要问题简述）\n2. （细节补充建议）\n3. （未来增强方向提示）\n\n## 小结\n\n根据用户本次的改进项目，给出总结。', '## 附加控制策略\n- 若检测到需求长度或复杂度超出正常范围，可适度缩减次要优化建议，只列出最相关的补充方向。\n- 若累计对话Token数接近10000时，友好提示建议保存成果并新开对话，避免性能下降。', '你是一位资深的需求评审专家，专注于帮助项目管理团队提升需求条目的完整性、逻辑性与可实现性。', '你的职责是基于专业评审方法，在一次完整分析中，系统性提出归类清晰、重点突出的评审建议，引导用户高效完善需求。如果用户问询需求评审以外的问题，可以给与简单响应后，拉回需求评审中。', 'system', '2025-10-31 12:00:00', 'active');

UPDATE `zt_ai_prompt` SET `source` = REPLACE(`source`, 'programplans.planDuration,', '') WHERE `source` LIKE '%programplans.planDuration,%';

ALTER TABLE `zt_ai_miniprogram` ADD COLUMN `knowledgeLib` varchar(255) NOT NULL DEFAULT '' COMMENT '关联的知识库ID列表' AFTER `model`;
ALTER TABLE `zt_ai_prompt` ADD COLUMN `knowledgeLib` varchar(255) NOT NULL DEFAULT '' COMMENT '关联的知识库ID列表' AFTER `model`;

CREATE TABLE IF NOT EXISTS `zt_ai_promptfield` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `appID` int unsigned NOT NULL COMMENT '所属 Prompt 的 ID，对应 zt_ai_prompt.id',
  `name` varchar(30) NOT NULL DEFAULT '' COMMENT '字段名称',
  `type` varchar(20) NOT NULL DEFAULT 'text' COMMENT '字段类型',
  `placeholder` text DEFAULT NULL COMMENT '输入提示',
  `options` text DEFAULT NULL COMMENT '选项列表，逗号分隔',
  `required` tinyint unsigned NOT NULL DEFAULT 1 COMMENT '是否必填',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

UPDATE `zt_project` SET `budget` = 0 WHERE `budget` = '';
UPDATE `zt_project` SET `budget` = REPLACE(`budget`, '万', '0000');

ALTER TABLE `zt_actionproduct` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_burn` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_charterproduct` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_demandreview` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_demandspec` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_deployproduct` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_designspec` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_duckdbqueue` ADD COLUMN `id` bigint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_feedbackview` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_grouppriv` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_oauth` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_pivotdrill` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_pivotspec` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_planstory` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_projectadmin` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_projectcase` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_projectproduct` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_projectspec` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_projectstory` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_repobranch` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_riskissue` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_roadmapstory` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_searchdict` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_storyestimate` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_storygrade` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_storyreview` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_storyspec` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_storystage` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_suitecase` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_taskspec` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_trainrecords` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_usergroup` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;
ALTER TABLE `zt_workflowlinkdata` ADD COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY;