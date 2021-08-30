ALTER TABLE `zt_testtask` ADD `realFinishedDate` datetime NOT NULL AFTER `end`;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('*/5',  '*',    '*',    '*',    '*',    'moduleName=mr&methodName=syncMR', '定时同步GitLab合并数据到禅道数据库', 'zentao', 1, 'normal',   '0000-00-00 00:00:00');
