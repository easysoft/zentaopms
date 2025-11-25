ALTER TABLE `zt_mr` ADD executionID mediumint(8) unsigned NOT NULL DEFAULT 0 AFTER `jobID`;
ALTER TABLE `zt_testtask` ADD COLUMN `members` text NULL;
UPDATE `zt_solutions` SET `deleted` = '0' WHERE `deleted` = '';

CREATE TABLE `zt_queue` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT,
  `cron` mediumint NOT NULL,
  `type` varchar(255) NOT NULL,
  `command` text NOT NULL,
  `status` enum('wait','doing','done') NOT NULL DEFAULT 'wait',
  `execId` int DEFAULT NULL,
  `createdDate` datetime NOT NULL,
  `deleted` enum('0','1') NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

UPDATE `zt_dimension` SET `desc` = '为管理层提供洞察力和决策支持，从而推动业务增长和发展' WHERE `id` = 1;
UPDATE `zt_dimension` SET `desc` = '识别项目管理流程中的关键步骤、瓶颈和性能指标，从而做出有针对性的改进措施，达到优化项目管理流程和降本增效的目的' WHERE `id` = 2;
UPDATE `zt_dimension` SET `desc` = '确保项目交付过程和成果符合预期的质量标准和要求，从而实现客户满意度、提高项目绩效、保障项目可持续性和促进持续改进' WHERE `id` = 3;

ALTER TABLE zt_metric DROP INDEX `code`;

/* Add installed date to config. */
REPLACE INTO `zt_config` ( `vision`, `owner`, `module`, `section`, `key`, `value` ) VALUES ('', 'system', 'common', 'global', 'installedDate', (SELECT LEFT( date, 10 ) AS date FROM zt_action WHERE LEFT ( date, 10 ) != '2012-06-05' AND LEFT ( date, 10 ) != '2021-04-28' AND date > '2009-03-14' ORDER BY id LIMIT 1));

REPLACE INTO `zt_chart`(`id`, `name`, `dimension`, `type`, `group`, `dataset`, `desc`, `settings`, `filters`, `step`, `fields`, `langs`, `sql`, `stage`, `builtin`, `objects`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `deleted`) VALUES (1030, '宏观数据-禅道使用时长', 1, 'card', '58', '', '', '{\"value\": {\"type\": \"value\", \"field\": \"period\", \"agg\": \"value\"}, \"title\": {\"type\": \"text\", \"name\": \"\"},\n\"type\": \"value\"\n}', '[]', 0, '', NULL, '	SELECT if(t2.`year` > 0, concat(t2.`year`, \'年\', t2.`day`, \'天\'), concat(t2.`day`, \'天\')) as period from (\r\nSELECT TIMESTAMPDIFF(YEAR,t1.firstDay,t1.today) AS `year`,DATEDIFF(DATE_SUB(t1.today,INTERVAL TIMESTAMPDIFF(YEAR,t1.firstDay,t1.today) YEAR), t1.firstDay) AS `day`  \r\nFROM (SELECT `value` AS firstDay, now() AS today FROM zt_config WHERE `owner` = \'system\' AND `key` = \'installedDate\') AS t1\r\n) t2', 'published', 1, '', 'system', '2022-12-07 14:59:41', '', '2022-12-07 14:59:41', 0);
REPLACE INTO `zt_chart`(`id`, `name`, `dimension`, `type`, `group`, `dataset`, `desc`, `settings`, `filters`, `step`, `fields`, `langs`, `sql`, `stage`, `builtin`, `objects`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `deleted`) VALUES (20015, '使用数据分析-上线时间', 1, 'card', '58', '0', ' ', '{\"value\": {\"type\": \"value\", \"field\": \"date\", \"agg\": \"value\"}, \"title\": {\"type\": \"text\", \"name\": \"\"}, \"type\": \"value\"}', '[]', 0, ' ', NULL, 'select `value` as date from zt_config where `owner` = \'system\' and `key` = \'installedDate\'', 'published', 1, ' ', 'system', '2023-08-16 15:32:10', 'admin', '2023-08-16 15:32:17', 0);

INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`, `status`) VALUES ('Bug转需求', 0, 'bug', ',bug.title,bug.steps,bug.severity,bug.pri,bug.status,bug.confirmed,bug.type,', 'story.create', '请将Bug转化为相应的研发需求。', '分条编写需求描述，分条编写验收标准，需求逻辑条理清晰。', '请你扮演一名资深的产品经理。', '负责产品战略、设计、开发、数据分析、用户体验、团队管理、沟通协调等方面，需要具备多种技能和能力，以实现产品目标和公司战略。', 'system', '2023-11-17 12:00:00', 'active');
INSERT INTO `zt_prompt` (`name`, `model`, `module`, `source`, `targetForm`, `purpose`, `elaboration`, `role`, `characterization`, `createdBy`, `createdDate`, `status`) VALUES ('拆分一个子计划', 0, 'productplan', ',productplan.title,productplan.desc,productplan.begin,productplan.end,', 'productplan.create', '根据给定计划名称、描述、计划开始时间和计划结束时间，将给定计划明确为其小范围的子计划。拆分出来的子计划可以更专注于给定计划中的某一类工作。	', '要求子计划的时间不能超出计划开始时间和计划结束时间，并且名称不能与原计划名称相同。润色子计划的描述。', '请你扮演一名资深的产品经理。', '负责产品计划、设计、用户体验等方面，需要具备多种技能和能力，以实现产品目标和公司战略。', 'system', '2023-11-17 12:00:00', 'active');

UPDATE `zt_cron` SET `type` = 'zentao' WHERE `command` = 'moduleName=metric&methodName=updateMetricLib';
