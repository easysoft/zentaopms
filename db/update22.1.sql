ALTER TABLE `zt_project` ADD `schedule` mediumtext DEFAULT NULL AFTER `days`;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`)
SELECT `group`, 'doc', 'copyDoc'
FROM `zt_grouppriv`
WHERE `module` = 'doc' AND `method` = 'moveDoc';

ALTER TABLE `zt_projectdeliverable` ADD COLUMN `submittedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '提交人' AFTER `createdDate`;
ALTER TABLE `zt_projectdeliverable` ADD COLUMN `submittedDate` datetime DEFAULT NULL COMMENT '提交时间' AFTER `submittedBy`;

ALTER TABLE `zt_reviewissue` MODIFY COLUMN `resolutionDate` datetime DEFAULT NULL COMMENT '解决时间';

DELETE FROM `zt_config` WHERE owner = 'system' AND module = 'common' AND `key` = 'webRoot';

ALTER VIEW `ztv_projectnotpl` AS SELECT * FROM `zt_project` WHERE `deleted` = '0' AND `isTpl` = 0;
