ALTER TABLE `zt_demandpool` ADD COLUMN `products` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_project` ADD COLUMN `parallel` mediumint(9) NOT NULL DEFAULT '0';
ALTER TABLE `zt_demand` CHANGE `product` `product` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD COLUMN `retractedReason` ENUM('', 'omit', 'other') NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD COLUMN `retractedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_story` ADD COLUMN `retractedDate` datetime;

ALTER table `zt_metric` ADD `dateType` varchar(50) NOT NULL DEFAULT '';
ALTER table `zt_metric` ADD `lastCalcRows` mediumint NOT NULL DEFAULT 0 AFTER `order`;
ALTER table `zt_metric` ADD `lastCalcTime` datetime DEFAULT NULL AFTER `lastCalcRows`;

INSERT INTO `zt_metric`(`purpose`, `scope`, `object`, `stage`, `type`, `name`, `code`, `unit`, `collector`, `desc`, `definition`, `when`, `createdBy`, `createdDate`, `builtin`,  `deleted`, `dateType`) VALUES
('scale', 'system', 'execution', 'released', 'php', '按系统统计的年度完成执行数', 'count_of_annual_finished_execution', 'count', NULL, '按系统统计的年度完成执行数是指在某年度已经完成的执行数。该度量项反映了团队或组织在某年的工作效率和完成能力。较高的年度完成执行数表示团队或组织在完成任务方面表现出较高的效率，反之则可能需要审查工作流程和资源分配情况，以提高执行效率。', '所有的执行个数求和\r\n实际完成日期为某年\r\n过滤已删除的执行', 'realtime', 'system', '2023-08-22 08:00:00', '1', '0', 'year');

UPDATE `zt_metric` SET `createdBy` = 'system' WHERE `createdBy` = '';
UPDATE `zt_metric` SET `createdDate` = '2023-08-22 08:00:00' WHERE `createdDate` IS NULL;

UPDATE `zt_metric` SET `definition` = '所有测试单中的用例个数求和（不去重）\r\n指派给某人\r\n过滤已删除的用例\r\n过滤已删除的测试单中的用例\r\n过滤已关闭的测试单中的用例\r\n' WHERE `code` = 'count_of_assigned_case_in_user';
UPDATE `zt_metric` SET `definition` = '所有任务个数求和\r\n指派给为某人\r\n过滤已关闭的任务\r\n过滤已取消的任务\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务\r\n过滤多人任务中某人任务状态为已完成的任务\r\n过滤任务关联的执行和项目都为挂起状态时的任务' WHERE `code` = 'count_of_assigned_task_in_user';
UPDATE `zt_metric` SET `definition` = '所有研发需求个数求和\r\n过滤已删除的研发需求\r\n过滤状态为已关闭的研发需求\r\n过滤已删除产品下的研发需求\r\n过滤已删除的无产品项目下的研发需求' WHERE `code` = 'count_of_pending_story_in_user';
UPDATE `zt_metric` SET `definition` = '所有研发需求个数求和\r\n评审人为某人\r\n评审结果为空\r\n评审状态为评审中或变更中\r\n过滤已删除的需求\r\n过滤已删除产品的需求' WHERE `code` = 'count_of_reviewing_story_in_user';
UPDATE `zt_metric` SET `definition` = '所有Bug个数求和\r\nbug状态为已解决和已关闭\r\n解决者为某人\r\n解决日期为某日\r\n过滤已删除的bug\r\n过滤已删除产品的bug' WHERE `code` = 'count_of_daily_fixed_bug_in_user';

UPDATE `zt_metric` SET `definition` = '所有的任务个数求和\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'count_of_task';
UPDATE `zt_metric` SET `definition` = '所有的任务个数求和\r\n状态为已完成\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'count_of_finished_task';
UPDATE `zt_metric` SET `definition` = '复用：\r\n按系统统计的任务总数\r\n按系统统计的已完成任务数\r\n公式：\r\n按系统统计的未完成任务数=按系统统计的任务总数-按系统统计的已完成任务数' WHERE `code` = 'count_of_unfinished_task';
UPDATE `zt_metric` SET `definition` = '所有的任务个数求和\r\n状态为已关闭\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'count_of_closed_task';
UPDATE `zt_metric` SET `definition` = '所有的任务个数求和\r\n创建时间为某年\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'count_of_annual_created_task';
UPDATE `zt_metric` SET `definition` = '所有的任务个数求和\r\n完成时间为某年\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'count_of_annual_finished_task';
UPDATE `zt_metric` SET `definition` = '所有的任务个数求和\r\n创建时间为某年某月\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'count_of_monthly_created_task';
UPDATE `zt_metric` SET `definition` = '所有的任务个数求和\r\n完成时间为某年某月\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'count_of_monthly_finished_task';
UPDATE `zt_metric` SET `definition` = '所有的任务的预计工时数求和\r\n过滤父任务\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'estimate_of_task';
UPDATE `zt_metric` SET `definition` = '所有的任务的消耗工时数求和\r\n过滤父任务\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'consume_of_task';
UPDATE `zt_metric` SET `definition` = '所有的任务的剩余工时数求和\r\n过滤父任务\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'left_of_task';
UPDATE `zt_metric` SET `definition` = '所有的任务个数求和\r\n完成时间为某日\r\n过滤已删除的任务\r\n过滤已删除项目的任务\r\n过滤已删除执行的任务' WHERE `code` = 'count_of_daily_finished_task';
