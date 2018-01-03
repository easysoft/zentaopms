ALTER TABLE `zt_story` CHANGE `stage` `stage` ENUM('','wait','planned','projected','developing','developed','testing','tested','verified','released','closed')  CHARACTER SET utf8  COLLATE utf8_general_ci  NOT NULL  DEFAULT 'wait';
ALTER TABLE `zt_todo` ADD `config` varchar(255) NOT NULL;
ALTER TABLE `zt_todo` ADD `cycle` tinyint unsigned NOT NULL DEFAULT '0' AFTER `type`;
INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('1',    '1',    '*',    '*',    '*',    'moduleName=todo&methodName=createCycle',    '生成周期性待办',  'zentao', 1, 'normal', '0000-00-00 00:00:00');
