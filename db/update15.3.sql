ALTER TABLE `zt_testtask` ADD `realFinishedDate` datetime NOT NULL AFTER `end`;
ALTER TABLE `zt_doc` ADD `draft` longtext NOT NULL AFTER `views`;
ALTER TABLE `zt_release` ADD `mailto` text AFTER `desc`;
ALTER TABLE `zt_release` ADD `notify` varchar(255) AFTER `mailto`;

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('*/5',  '*',    '*',    '*',    '*',    'moduleName=mr&methodName=syncMR', '定时同步GitLab合并数据到禅道数据库', 'zentao', 1, 'normal',   '0000-00-00 00:00:00');
