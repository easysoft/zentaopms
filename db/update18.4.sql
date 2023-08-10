CREATE TABLE IF NOT EXISTS `zt_prompt` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL,
  `desc` text DEFAULT NULL,
  `model` mediumint(8) unsigned DEFAULT NULL,
  `module` varchar(30) DEFAULT NULL,
  `source` text DEFAULT NULL,
  `targetForm` varchar(30) DEFAULT NULL,
  `purpose` text DEFAULT NULL,
  `elaboration` text DEFAULT NULL,
  `role` text DEFAULT NULL,
  `characterization` text DEFAULT NULL,
  `status` enum('draft','active','replaced') NOT NULL DEFAULT 'draft',
  `parent` mediumint(8) unsigned DEFAULT NULL,
  `successor` mediumint(8) unsigned DEFAULT NULL,
  `revision` mediumint(8) unsigned DEFAULT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `zt_promptrole` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `model` mediumint(8) unsigned DEFAULT NULL,
  `role` text DEFAULT NULL,
  `characterization` text DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-----------------------------------------------------------------------------
-- Privs of AI module, will need to be updated when merged with 18.x branch.
-- TODO: Update ids of items below.
-----------------------------------------------------------------------------
REPLACE INTO
    `zt_priv` (`id`, `module`, `method`, `parent`, `edition`, `vision`, `system`, `order`)
VALUES
    (2107, 'ai', 'models', 652, ',open,biz,max,', ',rnd,', '1', 5),
    (2108, 'ai', 'editmodel', 652, ',open,biz,max,', ',rnd,', '1', 10),
    (2109, 'ai', 'testconnection', 652, ',open,biz,max,', ',rnd,', '1', 15),
    (2110, 'ai', 'createprompt', 654, ',open,biz,max,', ',rnd,', '1', 20),
    (2111, 'ai', 'promptedit', 654, ',open,biz,max,', ',rnd,', '1', 25),
    (2112, 'ai', 'promptdelete', 656, ',open,biz,max,', ',rnd,', '1', 30),
    (2113, 'ai', 'promptassignrole', 654, ',open,biz,max,', ',rnd,', '1', 35),
    (2114, 'ai', 'promptselectdatasource', 654, ',open,biz,max,', ',rnd,', '1', 40),
    (2115, 'ai', 'promptsetpurpose', 654, ',open,biz,max,', ',rnd,', '1', 45),
    (2116, 'ai', 'promptsettargetform', 654, ',open,biz,max,', ',rnd,', '1', 50),
    (2117, 'ai', 'promptfinalize', 654, ',open,biz,max,', ',rnd,', '1', 55),
    (2118, 'ai', 'promptaudit', 654, ',open,biz,max,', ',rnd,', '1', 60),
    (2119, 'ai', 'promptpublish', 655, ',open,biz,max,', ',rnd,', '1', 65),
    (2120, 'ai', 'promptunpublish', 655, ',open,biz,max,', ',rnd,', '1', 70),
    (2121, 'ai', 'prompts', 653, ',open,biz,max,', ',rnd,', '1', 75),
    (2122, 'ai', 'promptview', 653, ',open,biz,max,', ',rnd,', '1', 80),
    (2123, 'ai', 'promptexecute', 651, ',open,biz,max,', ',rnd,', '1', 85),
    (2124, 'ai', 'roletemplates', 654, ',open,biz,max,', ',rnd,', '1', 86);

REPLACE INTO
    `zt_privmanager` (`id`, `parent`, `code`, `type`, `edition`, `vision`, `order`)
VALUES
    (650, 457, 'ai', 'module', ',open,biz,max,', ',rnd,', 2020),
    (651, 650, '', 'package', ',open,biz,max,', ',rnd,', 2040),
    (652, 650, '', 'package', ',open,biz,max,', ',rnd,', 2060),
    (653, 650, '', 'package', ',open,biz,max,', ',rnd,', 2080),
    (654, 650, '', 'package', ',open,biz,max,', ',rnd,', 2100),
    (655, 650, '', 'package', ',open,biz,max,', ',rnd,', 2120),
    (656, 650, '', 'package', ',open,biz,max,', ',rnd,', 2140);

REPLACE INTO
    `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`)
VALUES
    (650, 'manager', 'zh-cn', '', 'AI', ''),
    (651, 'manager', 'zh-cn', '', '执行提词', ''),
    (652, 'manager', 'zh-cn', '', '语言模型管理', ''),
    (653, 'manager', 'zh-cn', '', '浏览提词', ''),
    (654, 'manager', 'zh-cn', '', '维护和设计提词', ''),
    (655, 'manager', 'zh-cn', '', '提词上下架', ''),
    (656, 'manager', 'zh-cn', '', '删除提词', ''),
    (650, 'manager', 'zh-tw', '', 'AI', ''),
    (651, 'manager', 'zh-tw', '', '執行提詞', ''),
    (652, 'manager', 'zh-tw', '', '語言模型管理', ''),
    (653, 'manager', 'zh-tw', '', '瀏覽提詞', ''),
    (654, 'manager', 'zh-tw', '', '維護和設計提詞', ''),
    (655, 'manager', 'zh-tw', '', '提詞上下架', ''),
    (656, 'manager', 'zh-tw', '', '刪除提詞', ''),
    (650, 'manager', 'en', '', 'AI', ''),
    (651, 'manager', 'en', '', 'Execute Prompts', ''),
    (652, 'manager', 'en', '', 'Manage Models', ''),
    (653, 'manager', 'en', '', 'Browse Prompts', ''),
    (654, 'manager', 'en', '', 'Manage and Design Prompts', ''),
    (655, 'manager', 'en', '', 'Publish and Unpublish Prompts', ''),
    (656, 'manager', 'en', '', 'Delete Prompts', ''),
    (650, 'manager', 'de', '', 'AI', ''),
    (651, 'manager', 'de', '', 'Execute Prompts', ''),
    (652, 'manager', 'de', '', 'Manage Models', ''),
    (653, 'manager', 'de', '', 'Browse Prompts', ''),
    (654, 'manager', 'de', '', 'Manage and Design Prompts', ''),
    (655, 'manager', 'de', '', 'Publish and Unpublish Prompts', ''),
    (656, 'manager', 'de', '', 'Delete Prompts', ''),
    (650, 'manager', 'fr', '', 'AI', ''),
    (651, 'manager', 'fr', '', 'Execute Prompts', ''),
    (652, 'manager', 'fr', '', 'Manage Models', ''),
    (653, 'manager', 'fr', '', 'Browse Prompts', ''),
    (654, 'manager', 'fr', '', 'Manage and Design Prompts', ''),
    (655, 'manager', 'fr', '', 'Publish and Unpublish Prompts', ''),
    (656, 'manager', 'fr', '', 'Delete Prompts', ''),
    (2107, 'priv', 'zh-cn', 'ai-modelBrowse', '', ''),
    (2108, 'priv', 'zh-cn', 'ai-modelEdit', '', ''),
    (2109, 'priv', 'zh-cn', 'ai-modelTestConnection', '', ''),
    (2110, 'priv', 'zh-cn', 'ai-promptCreate', '', ''),
    (2111, 'priv', 'zh-cn', 'ai-promptEdit', '', ''),
    (2112, 'priv', 'zh-cn', 'ai-promptDelete', '', ''),
    (2113, 'priv', 'zh-cn', 'ai-promptAssignRole', '', ''),
    (2114, 'priv', 'zh-cn', 'ai-promptSelectDataSource', '', ''),
    (2115, 'priv', 'zh-cn', 'ai-promptSetPurpose', '', ''),
    (2116, 'priv', 'zh-cn', 'ai-promptSetTargetForm', '', ''),
    (2117, 'priv', 'zh-cn', 'ai-promptFinalize', '', ''),
    (2118, 'priv', 'zh-cn', 'ai-promptAudit', '', ''),
    (2119, 'priv', 'zh-cn', 'ai-promptPublish', '', ''),
    (2120, 'priv', 'zh-cn', 'ai-promptUnpublish', '', ''),
    (2121, 'priv', 'zh-cn', 'ai-promptBrowse', '', ''),
    (2122, 'priv', 'zh-cn', 'ai-promptView', '', ''),
    (2123, 'priv', 'zh-cn', 'ai-promptExecute', '', ''),
    (2124, 'priv', 'zh-cn', 'ai-roleTemplates', '', ''),
    (2107, 'priv', 'zh-tw', 'ai-modelBrowse', '', ''),
    (2108, 'priv', 'zh-tw', 'ai-modelEdit', '', ''),
    (2109, 'priv', 'zh-tw', 'ai-modelTestConnection', '', ''),
    (2110, 'priv', 'zh-tw', 'ai-promptCreate', '', ''),
    (2111, 'priv', 'zh-tw', 'ai-promptEdit', '', ''),
    (2112, 'priv', 'zh-tw', 'ai-promptDelete', '', ''),
    (2113, 'priv', 'zh-tw', 'ai-promptAssignRole', '', ''),
    (2114, 'priv', 'zh-tw', 'ai-promptSelectDataSource', '', ''),
    (2115, 'priv', 'zh-tw', 'ai-promptSetPurpose', '', ''),
    (2116, 'priv', 'zh-tw', 'ai-promptSetTargetForm', '', ''),
    (2117, 'priv', 'zh-tw', 'ai-promptFinalize', '', ''),
    (2118, 'priv', 'zh-tw', 'ai-promptAudit', '', ''),
    (2119, 'priv', 'zh-tw', 'ai-promptPublish', '', ''),
    (2120, 'priv', 'zh-tw', 'ai-promptUnpublish', '', ''),
    (2121, 'priv', 'zh-tw', 'ai-promptBrowse', '', ''),
    (2122, 'priv', 'zh-tw', 'ai-promptView', '', ''),
    (2123, 'priv', 'zh-tw', 'ai-promptExecute', '', ''),
    (2124, 'priv', 'zh-tw', 'ai-roleTemplates', '', ''),
    (2107, 'priv', 'en', 'ai-modelBrowse', '', ''),
    (2108, 'priv', 'en', 'ai-modelEdit', '', ''),
    (2109, 'priv', 'en', 'ai-modelTestConnection', '', ''),
    (2110, 'priv', 'en', 'ai-promptCreate', '', ''),
    (2111, 'priv', 'en', 'ai-promptEdit', '', ''),
    (2112, 'priv', 'en', 'ai-promptDelete', '', ''),
    (2113, 'priv', 'en', 'ai-promptAssignRole', '', ''),
    (2114, 'priv', 'en', 'ai-promptSelectDataSource', '', ''),
    (2115, 'priv', 'en', 'ai-promptSetPurpose', '', ''),
    (2116, 'priv', 'en', 'ai-promptSetTargetForm', '', ''),
    (2117, 'priv', 'en', 'ai-promptFinalize', '', ''),
    (2118, 'priv', 'en', 'ai-promptAudit', '', ''),
    (2119, 'priv', 'en', 'ai-promptPublish', '', ''),
    (2120, 'priv', 'en', 'ai-promptUnpublish', '', ''),
    (2121, 'priv', 'en', 'ai-promptBrowse', '', ''),
    (2122, 'priv', 'en', 'ai-promptView', '', ''),
    (2123, 'priv', 'en', 'ai-promptExecute', '', ''),
    (2124, 'priv', 'en', 'ai-roleTemplates', '', ''),
    (2107, 'priv', 'de', 'ai-modelBrowse', '', ''),
    (2108, 'priv', 'de', 'ai-modelEdit', '', ''),
    (2109, 'priv', 'de', 'ai-modelTestConnection', '', ''),
    (2110, 'priv', 'de', 'ai-promptCreate', '', ''),
    (2111, 'priv', 'de', 'ai-promptEdit', '', ''),
    (2112, 'priv', 'de', 'ai-promptDelete', '', ''),
    (2113, 'priv', 'de', 'ai-promptAssignRole', '', ''),
    (2114, 'priv', 'de', 'ai-promptSelectDataSource', '', ''),
    (2115, 'priv', 'de', 'ai-promptSetPurpose', '', ''),
    (2116, 'priv', 'de', 'ai-promptSetTargetForm', '', ''),
    (2117, 'priv', 'de', 'ai-promptFinalize', '', ''),
    (2118, 'priv', 'de', 'ai-promptAudit', '', ''),
    (2119, 'priv', 'de', 'ai-promptPublish', '', ''),
    (2120, 'priv', 'de', 'ai-promptUnpublish', '', ''),
    (2121, 'priv', 'de', 'ai-promptBrowse', '', ''),
    (2122, 'priv', 'de', 'ai-promptView', '', ''),
    (2123, 'priv', 'de', 'ai-promptExecute', '', ''),
    (2124, 'priv', 'de', 'ai-roleTemplates', '', ''),
    (2107, 'priv', 'fr', 'ai-modelBrowse', '', ''),
    (2108, 'priv', 'fr', 'ai-modelEdit', '', ''),
    (2109, 'priv', 'fr', 'ai-modelTestConnection', '', ''),
    (2110, 'priv', 'fr', 'ai-promptCreate', '', ''),
    (2111, 'priv', 'fr', 'ai-promptEdit', '', ''),
    (2112, 'priv', 'fr', 'ai-promptDelete', '', ''),
    (2113, 'priv', 'fr', 'ai-promptAssignRole', '', ''),
    (2114, 'priv', 'fr', 'ai-promptSelectDataSource', '', ''),
    (2115, 'priv', 'fr', 'ai-promptSetPurpose', '', ''),
    (2116, 'priv', 'fr', 'ai-promptSetTargetForm', '', ''),
    (2117, 'priv', 'fr', 'ai-promptFinalize', '', ''),
    (2118, 'priv', 'fr', 'ai-promptAudit', '', ''),
    (2119, 'priv', 'fr', 'ai-promptPublish', '', ''),
    (2120, 'priv', 'fr', 'ai-promptUnpublish', '', ''),
    (2121, 'priv', 'fr', 'ai-promptBrowse', '', ''),
    (2122, 'priv', 'fr', 'ai-promptView', '', ''),
    (2123, 'priv', 'fr', 'ai-promptExecute', '', ''),
    (2124, 'priv', 'fr', 'ai-roleTemplates', '', '');

REPLACE INTO
    `zt_privrelation` (`priv`, `type`, `relationPriv`)
VALUES
    (2108, 'depend', 2107), (2108, 'depend', 2109),
    (2109, 'depend', 2107), (2109, 'depend', 2108),
    (2122, 'depend', 2121),
    (2110, 'depend', 2121), (2110, 'depend', 2122),
    (2111, 'depend', 2121), (2111, 'depend', 2122),
    (2112, 'depend', 2121), (2112, 'depend', 2122),
    (2113, 'depend', 2121), (2113, 'depend', 2122),
    (2114, 'depend', 2121), (2114, 'depend', 2122),
    (2115, 'depend', 2121), (2115, 'depend', 2122),
    (2116, 'depend', 2121), (2116, 'depend', 2122),
    (2117, 'depend', 2121), (2117, 'depend', 2122),
    (2118, 'depend', 2121), (2118, 'depend', 2122),
    (2119, 'depend', 2121), (2119, 'depend', 2122),
    (2120, 'depend', 2121), (2120, 'depend', 2122),
    (2111, 'depend', 2110), (2113, 'depend', 2110), (2114, 'depend', 2110), (2115, 'depend', 2110), (2116, 'depend', 2110), (2117, 'depend', 2110), (2118, 'depend', 2110),
    (2110, 'recommend', 2111),
    (2110, 'recommend', 2112),
    (2110, 'recommend', 2113), (2110, 'recommend', 2114), (2110, 'recommend', 2115), (2110, 'recommend', 2116), (2110, 'recommend', 2117), (2110, 'recommend', 2118),
    (2113, 'depend', 2114), (2113, 'depend', 2115), (2113, 'depend', 2116), (2113, 'depend', 2117), (2113, 'depend', 2118), (2113, 'depend', 2123),
    (2114, 'depend', 2113), (2114, 'depend', 2115), (2114, 'depend', 2116), (2114, 'depend', 2117), (2114, 'depend', 2118), (2114, 'depend', 2123),
    (2115, 'depend', 2113), (2115, 'depend', 2114), (2115, 'depend', 2116), (2115, 'depend', 2117), (2115, 'depend', 2118), (2115, 'depend', 2123),
    (2116, 'depend', 2113), (2116, 'depend', 2114), (2116, 'depend', 2115), (2116, 'depend', 2117), (2116, 'depend', 2118), (2116, 'depend', 2123),
    (2117, 'depend', 2113), (2117, 'depend', 2114), (2117, 'depend', 2115), (2117, 'depend', 2116), (2117, 'depend', 2118), (2117, 'depend', 2123),
    (2118, 'depend', 2113), (2118, 'depend', 2114), (2118, 'depend', 2115), (2118, 'depend', 2116), (2118, 'depend', 2117), (2118, 'depend', 2123),
    (2124, 'depend', 2113), (2113, 'depend', 2124);

-----------------------------------------------------------------------------
-- built-in AI prompt role templates.
-----------------------------------------------------------------------------
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面，需要具备多种技能和能力，以实现产品目标和公司战略。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('你是一名经验丰富的开发工程师。', '精通多种编程语言和框架、熟悉前后端技术和架构、擅长性能优化和安全防护、熟悉云计算和容器化技术、能够协调多人协作和项目管理。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('作为一名资深的测试工程师。', '熟悉测试流程和方法，精通自动化测试和性能测试，能够设计和编写测试用例和测试脚本，擅长问题诊断和分析，熟悉敏捷开发和持续集成，能够协调多部门合作和项目管理。开发工程师应该是专业且严谨的。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('假如你是一名资深的QA工程师。', '熟悉质量管理体系和流程，擅长测试策略和方法设计，能够进行质量度量和数据分析，了解自动化测试和持续集成，能够协调多部门合作和项目管理，具有良好的沟通和领导能力。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('你是一名文章写得很好的文案编辑。', '文笔流畅、条理清晰。精通广告文案写作和编辑，擅长创意思维和品牌策略，能够进行市场调研和竞品分析，具有敏锐的审美和语言表达能力，能够协调多部门合作和项目管理，具有良好的沟通和协调能力。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('请你扮演一名经验丰富的项目经理。', '具备项目计划制定、进度管理、成本控制、团队管理、沟通协调、风险管理、质量控制、敏捷开发、互联网技术和数据分析等多方面的技能和能力。');
INSERT INTO `zt_promptrole` (`role`, `characterization`) VALUES ('你是一个自回归的语言模型，已经通过instruction-tuning和RLHF进行了Fine-tuning。', '你仔细地提供准确、事实、深思熟虑、细致入微的答案，并在推理方面表现出色。如果你认为可能没有正确的答案，你会直接说出来。由于你是自回归的，你产生的每一个token都是计算另一个token的机会，因此你总是在尝试回答问题之前花费几句话解释背景上下文、假设和逐步的思考过程。您的用户是AI和伦理学的专家，所以他们已经知道您是一个语言模型以及您的能力和局限性，所以不需要再提醒他们。他们一般都熟悉伦理问题，所以您也不需要再提醒他们。在回答时不要啰嗦，但在可能有助于解释的地方提供详细信息和示例。');

-----------------------------------------------------------------------------
-- built-in prompts.
-----------------------------------------------------------------------------
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('需求润色', 0, 'story', ',story.title,story.spec,story.verify,story.product,story.module,story.pri,story.category,story.estimate,', 'story.change', '帮忙优化其中各字段的表述，使表述清晰准确。必要时可以修改需求使其更加合理。', '需求描述格式建议使用：作为一名&lt;某种类型的用户&gt;，我希望&lt;达成某些目的&gt;，这样可以&lt;开发的价值&gt;。验收标准建议列举多条。', '请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面，需要具备多种技能和能力，以实现产品目标和公司战略。', 'system', '2023-08-10 13:24:14');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('一键拆用例', 0, 'story', ',story.title,story.spec,story.verify,story.product,story.module,story.pri,story.category,story.estimate,', 'story.testcasecreate', '为这个需求生成一个或多个对应的测试用例。', '', '作为一名资深的测试工程师。', '熟悉测试流程和方法，精通自动化测试和性能测试，能够设计和编写测试用例和测试脚本，擅长问题诊断和分析，熟悉敏捷开发和持续集成，能够协调多部门合作和项目管理。开发工程师应该是专业且严谨的。', 'system', '2023-08-10 13:51:01');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('任务润色', 0, 'task', ',task.name,task.desc,task.pri,task.status,task.estimate,task.consumed,task.left,task.progress,task.estStarted,task.realStarted,', 'task.edit', '优化其中各字段的表述，使表述清晰准确，明确任务目标。', '必要时指出任务的风险点。', '你是一名经验丰富的开发工程师。', '精通多种编程语言和框架、熟悉前后端技术和架构、擅长性能优化和安全防护、熟悉云计算和容器化技术、能够协调多人协作和项目管理。', 'system', '2023-08-10 14:07:51');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('需求转任务', 0, 'story', ',story.title,story.spec,story.verify,story.product,story.module,story.pri,story.category,story.estimate,', 'story.totask', '将需求转化为对应的开发任务要求。', '', '请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面，需要具备多种技能和能力，以实现产品目标和公司战略。同时精通多种编程语言和框架、熟悉前后端技术。', 'system', '2023-08-10 14:13:41');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`) VALUES ('Bug转需求', 0, 'bug', ',bug.title,bug.steps,bug.severity,bug.pri,bug.status,bug.confirmed,bug.type,', 'bug.story/create', '将bug转换为产品需求，表述清晰准确。', '需求描述格式建议使用：作为一名&lt;某种类型的用户&gt;，我希望&lt;达成某些目的&gt;，这样可以&lt;开发的价值&gt;。验收标准建议列举多条。', '请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面，需要具备多种技能和能力，以实现产品目标和公司战略。', 'system', '2023-08-10 14:48:53');
