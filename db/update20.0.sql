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

INSERT INTO `zt_metric` (`purpose`, `scope`, `object`, `stage`, `type`, `name`, `code`, `alias`, `unit`, `desc`, `definition`, `when`, `createdBy`, `createdDate`, `builtin`, `deleted`, `dateType`) VALUES
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的每周新增反馈数', 'count_of_weekly_created_feedback_in_product', '新增反馈数', 'count', '按产品统计的每周新增反馈数是指在一个周内收集到的用户反馈的数量。这个度量项可以帮助团队了解用户对产品的发展趋势和需求变化，并进行产品策略的调整和优化', '产品中创建时间为某个周的反馈的个数求和\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'week'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的处理中的反馈数', 'count_of_doing_feedback_in_product', '处理中反馈数', 'count', '按产品统计的处理中的反馈数表示产品中状态为处理中的反馈数量之和。该数值越大，说明团队并行处理的反馈越多，可以帮助团队了解当前的工作负载情况', '产品中所有反馈个数求和\r\n状态为处理中\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的已处理的反馈数', 'count_of_done_feedback_in_product', '已处理反馈数', 'count', '按产品统计的已处理的反馈数表示产品中状态为已处理的反馈数量之和。该数值越大，说明团队成员处理的反馈越多，有利于提高用户满意度', '产品中所有反馈个数求和\r\n状态为已处理\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的待完善的反馈数', 'count_of_clarify_feedback_in_product', '待完善反馈数', 'count', '按产品统计的待完善的反馈数表示产品中状态为待完善的反馈数量之和。该数值越大，说明有较多的反馈信息不清晰或比较复杂。需要反馈者更多的澄清和解释', '产品中所有反馈个数求和\r\n状态为待完善\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的待处理的反馈数', 'count_of_wait_feedback_in_product', '待处理反馈数', 'count', '按产品统计的待处理的反馈数表示产品中状态为待处理的反馈数量之和。该度量项可能暗示产品团队的反馈处理效率，待处理反馈数越多，可能会导致客户满意度降低', '产品中所有反馈个数求和\r\n状态为待处理\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的追问中的反馈数', 'count_of_asked_feedback_in_product', '追问中反馈数', 'count', '按产品统计的追问中的反馈数表示产品中状态为追问中的反馈数量之和。该度量项可能暗示着反馈的复杂性或对处理方案的疑惑，追问中的反馈数量越多，可能意味着团队需要更多时间和资源来回复并解决这些问题', '产品中所有反馈个数求和\r\n状态为追问中\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate'),
('scale', 'product', 'feedback', 'released', 'php', '按产品统计的未关闭的反馈数', 'count_of_unclosed_feedback_in_product', '未关闭反馈数', 'count', '按产品统计的未关闭的反馈数表示产品中状态为未关闭的反馈数量之和。这个度量项可以一定程度反映产品团队响应用户反馈的效率和及时处理用户问题的能力', '产品中所有反馈个数求和\r\n过滤状态为已关闭的反馈\r\n过滤已删除的反馈\r\n过滤已删除的产品', 'realtime', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate');
