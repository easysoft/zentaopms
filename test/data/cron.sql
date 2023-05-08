truncate `zt_cron`;
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('*',    '*',    '*',    '*',    '*',    '', '监控定时任务', 'zentao', 1, 'normal',   '0000-00-00 00:00:00'),
('30',   '23',   '*',    '*',    '*',    'moduleName=execution&methodName=computeburn', '更新燃尽图',      'zentao', 1, 'normal', '0000-00-00 00:00:00'),
('0',    '8',    '*',    '*',    '*',    'moduleName=report&methodName=remind',       '每日任务提醒',    'zentao', 1, 'normal', '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=svn&methodName=run',             '同步SVN',         'zentao', 1, 'stop',   '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=git&methodName=run',             '同步GIT',         'zentao', 1, 'stop',   '0000-00-00 00:00:00'),
('30',   '0',    '*',    '*',    '*',    'moduleName=backup&methodName=backup',       '备份数据和附件',  'zentao', 1, 'normal', '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=mail&methodName=asyncSend',      '异步发信',        'zentao', 1, 'normal', '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=webhook&methodName=asyncSend',   '异步发送Webhook', 'zentao', 1, 'normal', '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=admin&methodName=deleteLog',     '删除过期日志',    'zentao', 1, 'normal', '0000-00-00 00:00:00'),
('1',    '1',    '*',    '*',    '*',    'moduleName=todo&methodName=createCycle',    '生成周期性待办',  'zentao', 1, 'normal', '0000-00-00 00:00:00'),
('1',    '0',    '*',    '*',    '*',    'moduleName=ci&methodName=initQueue', '创建周期性任务', 'zentao', 1, 'normal',   '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=ci&methodName=checkCompileStatus', '同步DevOps构建任务状态', 'zentao', 1, 'normal',   '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=ci&methodName=exec', '执行DevOps构建任务', 'zentao', 1, 'normal',   '0000-00-00 00:00:00'),
('*/5',  '*',    '*',    '*',    '*',    'moduleName=mr&methodName=syncMR', '定时同步GitLab合并数据到禅道数据库', 'zentao', 1, 'normal', '0000-00-00 00:00:00');
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('30', '23', '*', '*', '*', 'moduleName=execution&methodName=computeTaskEffort', '计算任务剩余工时', 'zentao', '1', 'normal', '0000-00-00 00:00:00');
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('30', '7', '*', '*', '*', 'moduleName=effort&methodName=remindNotRecord', '提醒录入日志', 'zentao', '1', 'stop', '0000-00-00 00:00:00');
REPLACE INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('1','0','*','*','*','moduleName=weekly&methodName=computeWeekly','更新项目周报','system',0,'normal','2020-08-27 10:07:53'),
('1','0','*','*','*','moduleName=measurement&methodName=initCrontabQueue','初始化度量队列','zentao',0,'normal','2020-07-07 14:51:48'),
('*/5','*','*','*','*','moduleName=measurement&methodName=execCrontabQueue','执行度量队列','zentao',0,'running','2020-07-10 13:10:58');
