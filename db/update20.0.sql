CREATE TABLE IF NOT EXISTS `zt_ai_assistant` (
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `name` varchar(30) NOT NULL,
    `modelId` mediumint(8) unsigned NOT NULL,
    `desc` text NOT NULL,
    `systemMessage` text NOT NULL,
    `greetings` text NOT NULL,
    `icon` varchar(30) DEFAULT 'coding-1' NOT NULL,
    `enabled` enum('0', '1') NOT NULL DEFAULT '1',
    `createdDate` datetime NOT NULL,
    `publishedDate` datetime DEFAULT NULL,
    `deleted` enum('0','1') NOT NULL DEFAULT '0',
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

ALTER TABLE `zt_kanbancell` MODIFY `cards` mediumtext NULL;
ALTER TABLE `zt_user` MODIFY `ip` varchar(255) NOT NULL DEFAULT '';

DELETE FROM `zt_config` WHERE `owner`='system' AND `module`='custom' AND `key`='productProject';

INSERT INTO `zt_metric` (`purpose`, `scope`, `object`, `stage`, `type`, `name`, `code`, `alias`, `unit`, `desc`, `definition`, `when`, `createdBy`, `createdDate`, `builtin`, `deleted`, `dateType`) VALUES
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的每周新增反馈数', 'count_of_weekly_created_feedback_in_product', '新增反馈数', 'count', '按产品统计的每周新增反馈数是指在一个周内收集到的用户反馈的数量。这个度量项可以帮助团队了解用户对产品的发展趋势和需求变化，并进行产品策略的调整和优化', '产品中创建时间为某个周的反馈的个数求和\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'week'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的处理中的反馈数', 'count_of_doing_feedback_in_product', '处理中反馈数', 'count', '按产品统计的处理中的反馈数表示产品中状态为处理中的反馈数量之和。该数值越大，说明团队并行处理的反馈越多，可以帮助团队了解当前的工作负载情况', '产品中所有反馈个数求和\r\n状态为处理中\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的已处理的反馈数', 'count_of_done_feedback_in_product', '已处理反馈数', 'count', '按产品统计的已处理的反馈数表示产品中状态为已处理的反馈数量之和。该数值越大，说明团队成员处理的反馈越多，有利于提高用户满意度', '产品中所有反馈个数求和\r\n状态为已处理\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的待完善的反馈数', 'count_of_clarify_feedback_in_product', '待完善反馈数', 'count', '按产品统计的待完善的反馈数表示产品中状态为待完善的反馈数量之和。该数值越大，说明有较多的反馈信息不清晰或比较复杂。需要反馈者更多的澄清和解释', '产品中所有反馈个数求和\r\n状态为待完善\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的待处理的反馈数', 'count_of_wait_feedback_in_product', '待处理反馈数', 'count', '按产品统计的待处理的反馈数表示产品中状态为待处理的反馈数量之和。该度量项可能暗示产品团队的反馈处理效率，待处理反馈数越多，可能会导致客户满意度降低', '产品中所有反馈个数求和\r\n状态为待处理\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的追问中的反馈数', 'count_of_asked_feedback_in_product', '追问中反馈数', 'count', '按产品统计的追问中的反馈数表示产品中状态为追问中的反馈数量之和。该度量项可能暗示着反馈的复杂性或对处理方案的疑惑，追问中的反馈数量越多，可能意味着团队需要更多时间和资源来回复并解决这些问题', '产品中所有反馈个数求和\r\n状态为追问中\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的未关闭的反馈数', 'count_of_unclosed_feedback_in_product', '未关闭反馈数', 'count', '按产品统计的未关闭的反馈数表示产品中状态为未关闭的反馈数量之和。这个度量项可以一定程度反映产品团队响应用户反馈的效率和及时处理用户问题的能力', '产品中所有反馈个数求和\r\n过滤状态为已关闭的反馈\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'ticket', 'released', 'php', '按产品统计的等待的工单数', 'count_of_wait_ticket_in_product', '等待工单数', 'count', '按产品统计的等待的工单数表示产品中状态为等待的工单数量之和。该数值越大，说明产品团队还有较多工单任务需要处理，可以一定程度反映客户问题的堆积。', '产品中所有工单个数求和，状态为等待的工单，过滤已删除的工单，过滤已删除的产品。', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'ticket', 'released', 'php', '按产品统计的处理中的工单数', 'count_of_doing_ticket_in_product', '处理中工单数', 'count', '按产品统计的处理中的工单数表示产品中状态为处理中的工单数量之和。该数值越大，说明产品团队正在处理的工单数量较多，可以一定程度上反映团队的工作负载。', '产品中所有工单个数求和，状态为处理中，过滤已删除的工单，过滤已删除的产品。', 'realtime', 'system', '2024-05-06 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'ticket', 'released', 'php', '按产品统计的已处理的工单数', 'count_of_done_ticket_in_product', '已处理工单数', 'count', '按产品统计的已处理的工单数表示产品中状态为已处理的工单数量之和。该数值越大，说明产品团队完成的工单数量越多，可以一定程度反映团队处理客户问题的效率。', '产品中所有工单个数求和，状态为已处理，过滤已删除的工单，过滤已删除的产品。', 'realtime', 'system', '2024-05-06 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'ticket', 'released', 'php', '按产品统计的未关闭的工单数', 'count_of_unclosed_ticket_in_product', '未关闭工单数', 'count', '按产品统计的未关闭的工单数表示产品中状态为未关闭的工单数量之和。该数值越大，说明产品团队还有一定工单任务需要进一步完成。', '产品中所有工单个数求和，过滤已关闭的工单，过滤已删除的工单，过滤已删除的产品。', 'realtime', 'system', '2024-05-06 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'ticket', 'released', 'php', '按产品统计的每周新增工单数', 'count_of_weekly_created_ticket_in_product', '新增工单数', 'count', '按产品统计的每周新增工单数表示产品中每周新创建的工单数量之和。较高的每周新增工单数可能暗示着产品近期发布的功能存在较多问题，需要及时处理。', '产品中所有工单个数求和，创建时间为某周，过滤已删除的工单，过滤已删除的产品。', 'realtime', 'system', '2024-05-06 08:00:00', '1', '0', 'week'),
('scale', 'project', 'requirement', 'released', 'php', '按项目统计的已关闭用户需求数', 'count_of_closed_requirement_in_project', '已关闭用户需求数', 'count', '按项目统计的已关闭用户需求数是指项目中状态为已关闭的用户需求的数量，反映了项目团队在满足用户期望和需求方面的已完成任务和计划。已关闭用户需求数量的增加表示项目团队已经成功完成了一定数量的用户需求工作，并取得了一定的成果。', '项目中用户需求个数求和\r\n过滤已删除的用户需求状态为已关闭\r\n 过滤已删除的项目', 'realtime', 'system', '2025-05-17 08:00:00', '1', '0', 'nodate'),
('scale', 'project', 'requirement', 'released', 'php', '按项目统计的用户需求总数', 'count_of_requirement_in_project', '用户需求总数', 'count', '按项目统计的用户需求总数是指项目中创建或关联的所有用户需求的数量，反映了项目的规模和复杂度，提供了关于用户需求管理、进度控制、资源规划、风险评估和质量控制的有用信息。', '项目中用户需求个数求和\r\n过滤已删除的用户需求\r\n过滤已删除的项目', 'realtime', 'system', '2025-05-17 08:00:00', '1', '0', 'nodate'),
('scale', 'user', 'ticket', 'released', 'php', '按人员统计的被指派的工单数', 'count_of_assigned_ticket_in_user', '被指派的工单数', 'count', '按人员统计的被指派的工单数表示每个人被指派的工单数量之和，反映了每个人员需要处理的工单数量的规模，该数值越大，说明需要处理的反馈任务越多', '所有工单个数求和\r\n指派给为某人\r\n过滤已删除的工单\r\n过滤已删除产品的工单', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'user', 'qa', 'released', 'php', '按人员统计的被指派的QA数', 'count_of_assigned_qa_in_user', '被指派的QA数', 'count', '按人员统计的被指派的QA数表示每个人被指派的质量保证问题之和，反映了每个人员需要处理的质量保证问题的规模。该数值越大，说明需要处理的质量保证问题越多', '所有待处理的QA个数求和（包含：待处理质量保证计划、待处理不符合项）\r\n指派给为某人\r\n质量保证计划状态为待检查、不符合项状态为待解决\r\n过滤已删除的质量保证计划和不符合项\r\n过滤已删除项目的质量保证计划和不符合项', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'user', 'risk', 'released', 'php', '按人员统计的被指派的风险数', 'count_of_assigned_risk_in_user', '被指派的风险数', 'count', '按人员统计的被指派的风险数表示每个人被指派的风险数量之和，反映了每个人员需要处理的风险数量的规模。该数值越大，说明需要投入越多的时间处理风险', '所有风险个数求和\r\n指派给为某人\r\n过滤已删除的风险\r\n过滤已关闭的风险\r\n过滤已删除项目的风险', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'user', 'issue', 'released', 'php', '按人员统计的被指派的问题数', 'count_of_assigned_issue_in_user', '被指派的问题数', 'count', '按人员统计的被指派的问题数表示每个人待处理的问题数量之和。反映了每个人员需要处理的问题数量的规模。该数值越大，项目存在问题越多，需要投入越多的时间处理问题。', '所有问题个数求和\r\n指派给为某人\r\n过滤已删除的问题\r\n过滤已删除项目的问题', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'user', 'requirement', 'released', 'php', '按人员统计的被指派的用户需求数', 'count_of_assigned_requirement_in_user', '被指派的用需数', 'count', '按人员统计的被指派的用户需求数表示每个人待处理的用户需求数量之和。反映了每个人员需要处理的用户需求数量的规模。该数值越大，说明需要投入越多的时间处理用户需求。', '所有用户需求个数求和\r\n指派给为某人\r\n过滤已删除的用户需求\r\n过滤已删除产品的用户需求', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'user', 'demand', 'released', 'php', '按人员统计的被指派的需求池需求数', 'count_of_assigned_demand_in_user', '被指派的需求池需求数', 'count', '按人员统计的被指派的需求池需求数表示每个人待处理的需求池需求数量之和。反映了每个人员需要处理的需求池需求数量的规模。该数值越大，说明需要投入越多的时间处理需求池需求', '所有需求池需求个数求和\r\n指派给为某人\r\n过滤已删除的需求池需求\r\n过滤状态为已关闭的需求池需求', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'project', 'requirement', 'released', 'php', '按项目统计的未关闭用户需求数', 'count_of_unclosed_requirement_in_project', '未关闭用户需求数', 'count', '按项目统计的未关闭用户需求数是指项目中尚未满足或处理的用户需求的数量，反映了项目团队在满足用户期望和需求方面的进行中任务和计划。未关闭用户需求数量的增加表示项目团队尚未完成的用户需求工作较多，需要进一步跟进和处理，以确保项目能够满足用户的期望', '复用：\r\n按项目统计的用户需求总数\r\n按项目统计的已关闭用户需求数\r\n公式：\r\n按项目统计的未关闭用户需求数=按项目统计的用户需求总数-按项目统计的已关闭用户需求数', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'project', 'requirement', 'released', 'php', '按项目统计的已完成用户需求数', 'count_of_finished_requirement_in_project', '已完成用户需求数', 'count', '按项目统计的已完成用户需求数是指状态为已关闭且关闭原因为已完成的用户需求的数量。反映了项目团队在满足用户期望和需求方面的已经实现的任务和计划。已完成用户需求数量的增加表示项目团队已经成功完成了一定数量的用户需求工作，并取得了一定的成果', '项目中用户需求的个数求和\r\n状态为已关闭\r\n关闭原因为已完成\r\n过滤已删除的用户需求\r\n过滤已删除的项目', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate');

UPDATE `zt_metric` SET `name` = '按人员统计的被指派的研发需求数', `alias` = '被指派的研发需求数' WHERE `code` = 'count_of_pending_story_in_user';
UPDATE `zt_metric` SET `desc` = '按人员统计的被指派的研发需求数表示每个人被指派的研发需求数量之和，反映了每个人员需要处理的研发需求数量的规模，该数值越大，说明需要投入越多的时间处理研发需求' WHERE `code` = 'count_of_pending_story_in_user';

ALTER TABLE `zt_chart` ADD `code` varchar(255) not NULL default '' AFTER `name`;
ALTER TABLE `zt_pivot` ADD `code` varchar(255) not NULL default '' AFTER `group`;

UPDATE `zt_kanbancard` SET `color` = '#937c5a' WHERE `color` = '#b10b0b';
UPDATE `zt_kanbancard` SET `color` = '#fc5959' WHERE `color` = '#cfa227';
UPDATE `zt_kanbancard` SET `color` = '#ff9f46' WHERE `color` = '#2a5f29';

INSERT INTO `zt_cron`(`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('0', '*/1', '*', '*', '*', 'moduleName=metric&methodName=updateDashboardMetricLib', '计算仪表盘数据', 'zentao', 1, 'normal', NUll);

UPDATE `zt_todo` SET `type` = 'custom' WHERE `type` = 'cycle' AND `cycle` = 0;
