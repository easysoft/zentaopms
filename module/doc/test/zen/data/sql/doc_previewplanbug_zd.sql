-- 清空相关表数据
DELETE FROM `zt_bug`;
DELETE FROM `zt_productplan`;

-- 插入产品计划测试数据
INSERT INTO `zt_productplan` (`id`, `product`, `title`, `status`, `begin`, `end`) VALUES
(1, 1, '计划1', 'wait', '2024-01-01', '2024-03-31'),
(2, 1, '计划2', 'doing', '2024-02-01', '2024-04-30'),
(3, 1, '计划3', 'done', '2024-03-01', '2024-05-31'),
(4, 1, '计划4', 'wait', '2024-04-01', '2024-06-30'),
(5, 1, '计划5', 'doing', '2024-05-01', '2024-07-31');

-- 插入Bug测试数据
INSERT INTO `zt_bug` (`id`, `title`, `plan`, `product`, `status`, `pri`, `severity`, `type`, `openedBy`, `assignedTo`, `deleted`, `openedDate`) VALUES
(1, 'Bug1', 1, 1, 'active', 1, 1, 'codeerror', 'admin', 'admin', 0, '2024-01-15 09:00:00'),
(2, 'Bug2', 1, 1, 'active', 2, 2, 'codeerror', 'admin', 'admin', 0, '2024-01-16 10:00:00'),
(3, 'Bug3', 1, 1, 'active', 3, 3, 'designdefect', 'admin', 'user1', 0, '2024-01-17 11:00:00'),
(4, '计划Bug4', 2, 1, 'active', 1, 1, 'codeerror', 'admin', 'admin', 0, '2024-02-15 09:00:00'),
(5, '计划Bug5', 2, 1, 'active', 2, 2, 'designdefect', 'admin', 'user1', 0, '2024-02-16 10:00:00'),
(6, 'Plan Bug6', 2, 1, 'resolved', 3, 3, 'others', 'admin', 'user2', 0, '2024-02-17 11:00:00'),
(7, '计划缺陷7', 3, 1, 'active', 1, 1, 'codeerror', 'admin', 'admin', 0, '2024-03-15 09:00:00'),
(8, 'Bug Test8', 3, 1, 'resolved', 2, 2, 'designdefect', 'admin', 'user1', 0, '2024-03-16 10:00:00'),
(9, '计划问题9', 3, 1, 'closed', 3, 3, 'others', 'admin', 'user2', 0, '2024-03-17 11:00:00'),
(10, 'Bug Issue10', 0, 1, 'active', 4, 4, 'codeerror', 'admin', 'admin', 0, '2024-04-15 09:00:00'),
(11, 'Bug Issue11', 0, 1, 'active', 1, 1, 'codeerror', 'admin', 'admin', 0, '2024-04-16 09:00:00'),
(12, 'Bug Issue12', 0, 1, 'active', 2, 2, 'designdefect', 'admin', 'user1', 0, '2024-04-17 09:00:00'),
(13, 'Bug Issue13', 0, 1, 'active', 3, 3, 'others', 'admin', 'user2', 0, '2024-04-18 09:00:00'),
(14, 'Bug Issue14', 0, 1, 'active', 4, 4, 'codeerror', 'admin', 'admin', 0, '2024-04-19 09:00:00'),
(15, 'Bug Issue15', 0, 1, 'active', 1, 1, 'designdefect', 'admin', 'user1', 0, '2024-04-20 09:00:00'),
(16, 'Bug Issue16', 0, 1, 'active', 2, 2, 'others', 'admin', 'user2', 0, '2024-04-21 09:00:00'),
(17, 'Bug Issue17', 0, 1, 'active', 3, 3, 'codeerror', 'admin', 'admin', 0, '2024-04-22 09:00:00'),
(18, 'Bug Issue18', 0, 1, 'resolved', 4, 4, 'designdefect', 'admin', 'user1', 0, '2024-04-23 09:00:00'),
(19, 'Bug Issue19', 0, 1, 'closed', 1, 1, 'others', 'admin', 'user2', 0, '2024-04-24 09:00:00'),
(20, 'Bug Issue20', 0, 1, 'active', 2, 2, 'codeerror', 'admin', 'admin', 0, '2024-04-25 09:00:00');