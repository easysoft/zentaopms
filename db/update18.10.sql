ALTER TABLE `zt_demandpool` ADD COLUMN `products` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_project` ADD COLUMN `parallel` mediumint(9) NOT NULL DEFAULT '0';
ALTER TABLE `zt_demand` CHANGE `product` `product` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD COLUMN `retractedReason` ENUM('', 'omit', 'other') NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD COLUMN `retractedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD COLUMN `retractedDate` datetime;

ALTER table `zt_metric` ADD `dateType` varchar(50) NOT NULL DEFAULT '';
ALTER table `zt_metric` ADD `lastCalcRows` int NOT NULL DEFAULT 0 AFTER `order`;
ALTER table `zt_metric` ADD `lastCalcTime` datetime DEFAULT NULL AFTER `lastCalcRows`;

INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `stage`, `type`, `name`, `code`, `unit`, `collector`, `desc`, `definition`, `when`, `createdBy`, `createdDate`, `builtin`,  `deleted`, `dateType`) VALUES
('scale', 'system', 'execution', 'released', 'php', '按系统统计的年度完成执行数', 'count_of_annual_finished_execution', 'count', NULL, '按系统统计的年度完成执行数是指在某年度已经完成的执行数。该度量项反映了团队或组织在某年的工作效率和完成能力。较高的年度完成执行数表示团队或组织在完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。', '所有的执行个数求和\r\n实际完成日期为某年\r\n过滤已删除的执行', 'realtime', 'system', '2023-08-22 08:00:00', '1', '0', 'year');
