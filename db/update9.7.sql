ALTER TABLE `zt_story` CHANGE `stage` `stage` ENUM('','wait','planned','projected','developing','developed','testing','tested','verified','released','closed')  CHARACTER SET utf8  COLLATE utf8_general_ci  NOT NULL  DEFAULT 'wait';
ALTER TABLE `zt_todo` ADD `config` varchar(255) NOT NULL;
ALTER TABLE `zt_todo` ADD `cycle` tinyint unsigned NOT NULL DEFAULT '0' AFTER `type`;
ALTER TABLE `zt_todo` ADD `assignedTo` VARCHAR(30) NOT NULL DEFAULT '' AFTER `config`;
ALTER TABLE `zt_todo` ADD `assignedBy` VARCHAR(30) NOT NULL DEFAULT '' AFTER `assignedTo`;
ALTER TABLE `zt_todo` ADD `assignedDate` DATETIME  NOT NULL AFTER `assignedBy`;
ALTER TABLE `zt_todo` ADD `finishedBy` VARCHAR(30) NOT NULL DEFAULT '' AFTER `assignedDate`;
ALTER TABLE `zt_todo` ADD `finishedDate` DATETIME  NOT NULL AFTER `finishedBy`;
ALTER TABLE `zt_todo` ADD `closedBy` VARCHAR(30)   NOT NULL DEFAULT '' AFTER `finishedDate`;
ALTER TABLE `zt_todo` ADD `closedDate` DATETIME    NOT NULL AFTER `closedBy`;
ALTER TABLE `zt_todo` CHANGE `status` `status` ENUM('wait','doing','done','closed') CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait';

INSERT INTO `zt_cron` (`m`, `h`, `dom`, `mon`, `dow`, `command`, `remark`, `type`, `buildin`, `status`, `lastTime`) VALUES
('1',    '1',    '*',    '*',    '*',    'moduleName=todo&methodName=createCycle',    '生成周期性待办',  'zentao', 1, 'normal', '0000-00-00 00:00:00');


ALTER TABLE `zt_projectproduct` ADD `plan` MEDIUMINT(8)  UNSIGNED  NOT NULL  AFTER `branch`;

CREATE TABLE IF NOT EXISTS `zt_notify` (
  `id` mediumint unsigned NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `objectType` varchar(50) NOT NULL,
  `objectID` mediumint unsigned NOT NULL,
  `action` mediumint NOT NULL,
  `toList` varchar(255) NOT NULL,
  `ccList` text NOT NULL,
  `subject` varchar(255) NOT NULL,
  `data` text NOT NULL,
  `createdBy` char(30) NOT NULL,
  `createdDate` datetime NOT NULL,
  `sendTime` datetime NOT NULL,
  `status` varchar(10) NOT NULL DEFAULT 'wait',
  `failReason` text NOT NULL
) ENGINE='MyISAM' COLLATE 'utf8_general_ci';
