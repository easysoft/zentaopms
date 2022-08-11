ALTER TABLE `zt_job` ADD `lastSyncDate` datetime DEFAULT NULL AFTER `lastTag`;
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('*/5', '*', '*', '*', '*', 'moduleName=compile&methodName=syncCompile', '定时同步构建记录', 'zentao', 1, 'normal', '0000-00-00 00:00:00');
