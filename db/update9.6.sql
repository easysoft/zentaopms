ALTER TABLE `zt_score` DROP `objectID`;
ALTER TABLE `zt_team` CHANGE `limitedUser` `limited` varchar(8) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'no' AFTER `role`;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES ('*/5', '*', '*', '*', '*', 'moduleName=admin&methodName=deleteLog', '删除过期日志', 'zentao', 1, 'normal', '0000-00-00 00:00:00');
