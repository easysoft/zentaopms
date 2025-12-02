CREATE TABLE IF NOT EXISTS `zt_ai_model` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(20) NOT NULL,
  `vendor` varchar(20) NOT NULL,
  `credentials` text NOT NULL,
  `proxy` text DEFAULT NULL,
  `name` varchar(20) DEFAULT NULL,
  `desc` text DEFAULT NULL,
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) DEFAULT NULL,
  `editedDate` datetime DEFAULT NULL,
  `enabled` enum('0', '1') NOT NULL DEFAULT '1',
  `deleted` enum('0', '1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `zt_ai_miniprogram` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `category` varchar(30) NOT NULL,
  `desc` text DEFAULT NULL,
  `model` mediumint(8) unsigned DEFAULT NULL,
  `icon` varchar(30) NOT NULL DEFAULT 'writinghand-7',
  `createdBy` varchar(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `editedBy` varchar(30) NOT NULL,
  `editedDate` datetime NOT NULL,
  `published` enum('0','1') NOT NULL DEFAULT '0',
  `publishedDate` datetime DEFAULT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  `prompt` text NOT NULL,
  `builtIn` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

REPLACE INTO `zt_ai_miniprogram` (`id`, `name`, `category`, `desc`, `model`, `icon`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `published`, `publishedDate`, `deleted`, `prompt`, `builtIn`) VALUES
(1, '职业发展导航', 'personal', '职业发展导航是一个旨在帮助用户规划和实现职业目标的AI小程序，为用户提供个性化的建议。', 0, 'technologist-6', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '请帮我生成一份职业发展导航，我的教育背景为 <教育背景> ，职位信息为 <职位信息> ，工作经验描述如下： <工作经验> ，掌握的技能为 <掌握技能> ，为了实现 <职业目标> ，我想做一个 <规划时长> 的计划，我有更多感兴趣的领域为 <更多感兴趣的领域> ，有更多补充内容 <补充信息> ，来追求相关机会和进一步发展，控制在30字以内。', '1'),
(2, '工作汇报', 'work', '旨在帮助您轻松撰写和管理您的工作汇报。无论是每周、每月还是季度性的报告，我们提供了一个简单而高效的平台，让您能够清晰、有条理地记录和展示您的工作成果。', 0, 'technologist-2', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '我的基本信息如下: <身份描述> ，请根据我的工作情况生成一份工作汇报，需要包含 <汇报内容维度> ，汇报对象描述为 <汇报对象> ，另外还需要补充 <补充信息> 。下面是我的工作内容基本描述: <工作内容描述> ，控制在30字以内。', '1'),
(3, '市场分析报告', 'work', '市场分析报告小程序是一个旨在帮助用户根据互联网信息快速生成市场分析报告的AI小程序。', 0, 'chart-6', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '请帮我生成一份市场分析报告，我目标市场是 <目标市场> ,市场概况为 <市场概况> ，该领域的细分市场有 <细分市场> ,同时我希望能针对 <竞品名称> 展开竞品分析,竞品分析的维度是 <竞品分析维度> ,来帮助我快速的了解市场。', '1'),
(4, '健身计划', 'life', '健身计划小程序旨在帮助您制定和管理个性化的健身计划，让您更轻松、高效地实现健康和体能目标。', 0, 'cactus-5', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '我的基本信息如下： <性别> 、 <基本信息> ，我想做一份健身计划，达成 <目标设定> 的效果，对于目标我有如下更多补充:： <目标补充> 。我的初步想法是进行 <计划类型> 方面的类型，训练频次为 <训练频率> ，同时给身体足够的休息和恢复时间，要求如下 <休息与恢复> ，请根据我的上述情况生成一份健身计划，控制在30字以内。', '1'),
(5, '广告创意大师', 'creative', '广告创意大师是一个根据产品卖点，快速为用户提供广告文案的AI小程序。', 0, 'palette-3', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '假设我是一名 <角色> ,请结合产品卖点和目标用户，帮我为 <产品名称> 生成 <文案数量> 条计划投放在 <投放渠道> 的广告文案，且保证每条文案的字数不超过30字；产品的核心卖点是 <核心卖点> ，目标用户是 <目标用户> 。', '1'),
(6, '文章撰写助手', 'creative', '旨在为自媒体创作者提供一个强大而便捷的平台，助力您撰写优质的文章并实现更广泛的影响力。', 0, 'writinghand-3', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '生成一篇标题为 <文章主题> 的文章，文章内容概要为 <内容概要> ，计划发布到 <发布平台> ，需要注意控制 <规范要求> 。', '1'),
(7, '视频脚本创意工坊', 'creative', '视频脚本创意工房是一个帮助新手视频博主快速梳理视频创作思路，生成拍摄脚本的AI小程序。', 0, 'notebook-3', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '假设我是一个新手视频博主,现请以 <主题> 为题，创作一个时长不超过 <视频时长> 分钟， <视频风格> 风格的视频脚本。', '1'),
(8, '邮件起草助手', 'life', '邮件起草助手可以帮助用户更快速地起草邮件，减少繁琐的手动输入和编辑过程，帮助用户更好地表达和提升效率。', 0, 'computer-6', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '请为我提供一份邮件的起草建议和模板，根据 <邮件目的> 为目的，收件对象与我的关系为 <收件人关系> ，需要包含如下邮件内容： <邮件内容> ，使用 <邮件口吻> 的口吻，字数限制在 <字数限制> 之内，更多需要按照 <补充信息> 进行完善。', '1'),
(9, '新人介绍', 'personal', '新人介绍小程序是一个旨在帮助用户在入职当天，快速编写自我介绍的AI小程序。', 0, 'pencil-7', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '请帮我生成一份新人介绍，我的年龄是 <年龄>  ,籍贯是 <籍贯信息> ，毕业于 <毕业院校> ,工作经验为 <工作经验> ,日常爱好是 <爱好> ,请将字数控制在300字以内，来帮助我完成新人入职的自我介绍，', '1'),
(10, '调研问卷生成器', 'work', '调研问卷生成器是一个可以快速依据用户需要生成问卷内容的好帮手,旨在为用户提供更多的问题设计思路。', 0, 'search-6', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '请帮我生成一份调查问卷内容，我希望以 <调研主题> 为调研方向，问卷调研对象为 <调研对象> ，问题主题至少需要包括如下内容： <问题主题> ，期望题目类型包括 <题目类型> ，题目数量限制为 <题目数量> ，更多需要按照 <补充信息> 进行完善。', '1'),
(11, 'OKR目标达人', 'personal', 'OKR目标达人是一个旨在帮助用户规划和实现高效目标管理的AI小程序。', 0, 'pushpin-1', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '我是一名 <所处行业> 行业的 <职业名称>  ，主要负责的工作是 <工作内容> ，请帮我写一份 <时间范围> 的OKR，需要包括目标和关键结果，更多信息依据 <补充信息> 进行完善。', '1'),
(12, '学习规划师', 'life', '学习计划师是一个旨在帮助用户快速制定学习的AI小程序，为用户提供个性化的建议。', 0, 'notebook-7', 'system', NOW(), 'system', NOW(), '1', NOW(), '0', '我希望通过 <持续天数> 的学习计划来学习 <学习内容> 以达成 <目标设定> 的目标，在这个计划中需要包括以下 <学习内容> ，并为我提供对应的学习建议和指导，更多信息依据 <补充信息> 进行完善，使我能够在这段时间内取得最佳的学习效果。', '1');

CREATE TABLE IF NOT EXISTS `zt_ai_message` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `appID` mediumint(8) unsigned NOT NULL,
  `user` mediumint(8) unsigned NOT NULL,
  `type` enum('req', 'res', 'ntf') NOT NULL,
  `content` text NOT NULL,
  `createdDate` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS `zt_ai_miniprogramfield` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `appID` mediumint(8) unsigned NOT NULL,
  `name` varchar(30) NOT NULL,
  `type` enum('radio', 'checkbox', 'text', 'textarea') DEFAULT 'text',
  `placeholder` text DEFAULT NULL,
  `options` text DEFAULT NULL,
  `required` enum('0', '1') DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

INSERT INTO `zt_ai_miniprogramfield` (`appID`, `name`, `type`, `placeholder`, `options`, `required`) VALUES
(1, '教育背景', 'textarea', '学历/专业', NULL, '0'),
(1, '职位信息', 'text', '行业领域/职位描述', NULL, '0'),
(1, '工作经验', 'textarea', '简要概述工作成果、团队合作沟通经验等', NULL, '0'),
(1, '掌握技能', 'textarea', '关键技能、熟练程度', NULL, '0'),
(1, '职业目标', 'text', '请输入你的职业目标', NULL, '0'),
(1, '更多感兴趣的领域', 'textarea', '请输入更多你感兴趣的领域', NULL, '0'),
(1, '规划时长', 'radio', NULL, '1年,3-5年,10年', '1'),
(1, '补充信息', 'textarea', '请输入更多补充信息', NULL, '0'),
(2, '身份描述', 'textarea', '职位/角色/职责', NULL, '0'),
(2, '工作内容描述', 'textarea', '请输入工作内容', NULL, '1'),
(2, '汇报对象', 'textarea', '请输入汇报对象信息', NULL, '0'),
(2, '汇报内容维度', 'checkbox', NULL, '完成情况,遇到的问题,关键指标,团队协作,进一步改进', '0'),
(2, '补充信息', 'textarea', '请输入更多补充信息', NULL, '0'),
(3, '目标市场', 'text', '请概述目标市场的定义和范围', NULL, '0'),
(3, '市场概况', 'text', '可描述市场规模、增长率、发展趋势等信息', NULL, '0'),
(3, '细分市场', 'text', '请描述市场的主要细分领域', NULL, '0'),
(3, '竞品名称', 'text', '请描述列举竞品名称', NULL, '0'),
(3, '竞品分析维度', 'checkbox', NULL, '市场份额,市场定位,产品特点,服务特点,销售策略,营销策旅', '0'),
(4, '性别', 'radio', NULL, '男,女', '1'),
(4, '基本信息', 'textarea', '请输入身高、体重等基础信息', NULL, '0'),
(4, '目标设定', 'checkbox', NULL, '增肌,减脂,增强体能,特定方面的能力', '1'),
(4, '目标补充', 'textarea', '可以补充对目标的详细描述', NULL, '0'),
(4, '计划类型', 'checkbox', NULL, '重力训练,有氧运动,柔韧训练', '0'),
(4, '训练频率', 'text', '请输入训练频次', NULL, '0'),
(4, '休息与恢复', 'textarea', '请输入睡眠及饮食要求', NULL, '0'),
(5, '角色', 'text', '请设定角色', NULL, '0'),
(5, '产品名称', 'text', '请输入产品名称', NULL, '0'),
(5, '文案数量', 'radio', NULL, '1,2,3,4,5', '0'),
(5, '核心卖点', 'text', '请概述产品的核心卖点', NULL, '0'),
(5, '目标用户', 'text', '请概述产品的主要目标用户', NULL, '0'),
(5, '投放渠道', 'checkbox', NULL, '电视,广播,户外,网络,报刊,电影', '0'),
(6, '文章主题', 'textarea', '请输入文章主题', NULL, '1'),
(6, '内容概要', 'textarea', '请输入内容概要', NULL, '0'),
(6, '规范要求', 'textarea', '例如语气描述、字数限制等', NULL, '0'),
(6, '发布平台', 'text', '该文章计划发布的平台', NULL, '0'),
(7, '主题', 'text', '请描述视频主题', NULL, '0'),
(7, '视频风格', 'checkbox', NULL, '纪实,人文,风景,动画,广告', '0'),
(7, '视频时长', 'radio', NULL, '1,3,5,10', '0'),
(8,	'邮件目的',	'textarea',	'您此次发送邮件的目的',	NULL,	'1'),
(8,	'收件人关系',	'text',	'简述与收件人之间的关系',	NULL,	'1'),
(8,	'邮件内容',	'textarea',	'简述邮件内容',	NULL,	'1'),
(8,	'邮件口吻',	'textarea',	'请输入期望的邮件口吻',	NULL,	'0'),
(8,	'字数限制',	'text',	'请输入字数限制',	NULL,	'0'),
(8,	'补充信息',	'textarea',	'请输入更多补充信息',	NULL,	'0'),
(9, '年龄', 'text', '年龄', NULL, '0'),
(9, '籍贯信息', 'text', '省/市', NULL, '0'),
(9, '毕业院校', 'text', '毕业院校/专业', NULL, '0'),
(9, '工作经验', 'textarea', '请简要概述从事行业、工作年限、工作岗位等经验', NULL, '0'),
(9, '爱好', 'textarea', '请输入', NULL, '0'),
(10, '调研主题', 'textarea', '请简述您的调研主题',	NULL,	'1'),
(10, '调研对象', 'textarea', '简述调研对象',	NULL,	'1'),
(10, '问题主题', 'textarea', '简述您想要调研的主题问题',	NULL,	'0'),
(10, '题目类型', 'textarea', '请输入期望包括的题型',	NULL,	'0'),
(10, '题目数量', 'text', '请输入题目数量限制',	NULL,	'0'),
(10, '补充信息', 'textarea', '请输入更多补充信息',	NULL,	'0'),
(11, '所处行业', 'text', '请输入所处行业',	NULL,	'0'),
(11, '职业名称', 'text', '请输入职业名称',	NULL,	'1'),
(11, '工作内容', 'textarea', '请简述你的工作',	NULL,	'1'),
(11, '时间范围', 'checkbox', NULL,	'月度,季度,半年度,年度',	'0'),
(11, '补充信息', 'textarea', '请输入更多补充信息',	NULL,	'0'),
(12, '持续天数',	'text',	'请输入天数',	NULL,	'0'),
(12, '学习内容',	'textarea',	'简要概述要学习的内容方向',	NULL,	'1'),
(12, '目标设定',	'textarea',	'简要期望达成的目标',	NULL,	'0'),
(12, '计划内容',	'checkbox',	NULL,	'每日学习任务,适用的学习资源,学习方法,进度跟踪方式,激励机制',	'0'),
(12, '补充信息',	'textarea',	'请输入更多补充信息',	NULL,	'0');

CREATE TABLE IF NOT EXISTS `zt_ai_miniprogramstar` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `appID` mediumint(8) unsigned NOT NULL,
  `userID` mediumint(8) unsigned NOT NULL,
  `createdDate` datetime NOT NULL,
  UNIQUE (`appID`, `userID`),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

RENAME TABLE `zt_prompt` TO `zt_ai_prompt`;
RENAME TABLE `zt_promptrole` TO `zt_ai_promptrole`;

ALTER TABLE `zt_kanban` CHANGE `minColWidth` `minColWidth` smallint(4) NOT NULL DEFAULT '264';
ALTER TABLE `zt_project` CHANGE `minColWidth` `minColWidth` smallint(4) NOT NULL DEFAULT '264';
UPDATE `zt_kanban` SET `minColWidth` = '264' WHERE `minColWidth` <= '264';
UPDATE `zt_project` SET `minColWidth` = '264' WHERE `minColWidth` <= '264';
UPDATE `zt_project` SET `colWidth` = '264' WHERE `colWidth` <= '264';
UPDATE `zt_kanban` SET `colWidth` = '264' WHERE `colWidth` <= '264';
