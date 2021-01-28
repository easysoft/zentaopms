ALTER TABLE `zt_job` ADD `customParam` text AFTER `atTime`;
ALTER TABLE `zt_doc` ADD `mailto` text AFTER `editedDate`;
ALTER TABLE `zt_task` CHANGE `realStarted` `realStarted` datetime NOT NULL AFTER `estStarted`;
ALTER TABLE `zt_user` ADD `type` char(30) NOT NULL default 'inside' AFTER `account`;

ALTER TABLE `zt_notify` ADD INDEX `objectType_toList_status` (`objectType`, `toList`, `status`);
ALTER TABLE `zt_user` ADD INDEX `deleted` (`deleted`);
ALTER TABLE `zt_case` ADD INDEX `fromBug` (`fromBug`);
ALTER TABLE `zt_bug` ADD INDEX `toStory` (`toStory`);
ALTER TABLE `zt_task` ADD INDEX `parent` (`parent`);
ALTER TABLE `zt_bug` ADD INDEX `result` (`result`);
ALTER TABLE `zt_history` ADD INDEX `action` (`action`);
ALTER TABLE `zt_action` ADD INDEX `action` (`action`);
ALTER TABLE `zt_action` DROP INDEX `product`;

REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'common', '', 'CRProduct', '1');
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'common', '', 'CRProject', '1');
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'common', 'xuanxuan', 'pollingInterval', '60');
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'common', 'xuanxuan', 'aes', 'on');
