REPLACE INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('traincourse', 'cloudImport', '125', ',biz,max,ipd,', ',rnd,', '1', '10');
REPLACE INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('dataview', 'export', '650', ',biz,max,ipd,', ',rnd,', '1', '30');

REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2107, 'priv', 'de',    'traincourse-cloudImport', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2107, 'priv', 'en',    'traincourse-cloudImport', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2107, 'priv', 'fr',    'traincourse-cloudImport', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2107, 'priv', 'zh-cn', 'traincourse-cloudImport', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2107, 'priv', 'zh-tw', 'traincourse-cloudImport', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2108, 'priv', 'de',    'dataview-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2108, 'priv', 'en',    'dataview-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2108, 'priv', 'fr',    'dataview-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2108, 'priv', 'zh-cn', 'dataview-export', '', '');
REPLACE INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES (2108, 'priv', 'zh-tw', 'dataview-export', '', '');

REPLACE INTO zt_privmanager (id, parent, code, `type`, edition, vision, `order`) VALUES (650, 445, '', 'package', ',biz,max,ipd,', ',rnd,', 20);
REPLACE INTO zt_privlang (objectID, objectType, lang, `key`, value, `desc`) VALUES(650, 'manager', 'zh-cn', '', '导出数据表', '');

REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2108, 'depend', 1648);
REPLACE INTO zt_privrelation (priv, `type`, relationPriv) VALUES(2108, 'depend', 1651);

ALTER TABLE `zt_traincourse` ADD `importedStatus` enum('','wait','doing','done') NOT NULL DEFAULT '';
ALTER TABLE `zt_traincourse` ADD `lastUpdatedTime` int UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `zt_traincourse` MODIFY `code` varchar(255) NOT NULL DEFAULT '';

UPDATE `zt_im_chat`               SET `createdDate`    = NULL WHERE `createdDate`    = '0000-00-00 00:00:00';
UPDATE `zt_im_chat`               SET `editedDate`     = NULL WHERE `editedDate`     = '0000-00-00 00:00:00';
UPDATE `zt_im_chat`               SET `mergedDate`     = NULL WHERE `mergedDate`     = '0000-00-00 00:00:00';
UPDATE `zt_im_chat`               SET `lastActiveTime` = NULL WHERE `lastActiveTime` = '0000-00-00 00:00:00';
UPDATE `zt_im_chat`               SET `dismissDate`    = NULL WHERE `dismissDate`    = '0000-00-00 00:00:00';
UPDATE `zt_im_chat`               SET `archiveDate`    = NULL WHERE `archiveDate`    = '0000-00-00 00:00:00';
UPDATE `zt_im_chatuser`           SET `join`           = NULL WHERE `join`           = '0000-00-00 00:00:00';
UPDATE `zt_im_chatuser`           SET `quit`           = NULL WHERE `quit`           = '0000-00-00 00:00:00';
UPDATE `zt_im_client`             SET `createdDate`    = NULL WHERE `createdDate`    = '0000-00-00 00:00:00';
UPDATE `zt_im_client`             SET `editedDate`     = NULL WHERE `editedDate`     = '0000-00-00 00:00:00';
UPDATE `zt_im_message`            SET `date`           = NULL WHERE `date`           = '0000-00-00 00:00:00';
UPDATE `zt_im_message_backup`     SET `date`           = NULL WHERE `date`           = '0000-00-00 00:00:00';
UPDATE `zt_im_message_index`      SET `startDate`      = NULL WHERE `startDate`      = '0000-00-00 00:00:00';
UPDATE `zt_im_message_index`      SET `endDate`        = NULL WHERE `endDate`        = '0000-00-00 00:00:00';
UPDATE `zt_im_chat_message_index` SET `startDate`      = NULL WHERE `startDate`      = '0000-00-00 00:00:00';
UPDATE `zt_im_chat_message_index` SET `endDate`        = NULL WHERE `endDate`        = '0000-00-00 00:00:00';
UPDATE `zt_im_queue`              SET `addDate`        = NULL WHERE `addDate`        = '0000-00-00 00:00:00';
UPDATE `zt_im_queue`              SET `processDate`    = NULL WHERE `processDate`    = '0000-00-00 00:00:00';
UPDATE `zt_im_conference`         SET `openedDate`     = NULL WHERE `openedDate`     = '0000-00-00 00:00:00';
UPDATE `zt_im_conferenceaction`   SET `date`           = NULL WHERE `date`           = '0000-00-00 00:00:00';
UPDATE `zt_im_userdevice`         SET `validUntil`     = NULL WHERE `validUntil`     = '0000-00-00 00:00:00';
UPDATE `zt_im_userdevice`         SET `lastLogin`      = NULL WHERE `lastLogin`      = '0000-00-00 00:00:00';
UPDATE `zt_im_userdevice`         SET `lastLogout`     = NULL WHERE `lastLogout`     = '0000-00-00 00:00:00';

CREATE INDEX `project` ON `zt_project` (`project`);

UPDATE `zt_privlang` SET `value`='删除风险' WHERE `objectID`=197 AND `objectType`='manager';
UPDATE `zt_auditcl` SET `deleted` = '0' WHERE `deleted` = '';

UPDATE `zt_workflowdatasource` SET `vision` = 'rnd' WHERE `code` IN ('products','projects','productLines','stories','tasks','bugs','groups','users','branches','builds','modules','plans','productType','productStatus','productAcl','projectType','projectStatus','projectAcl','releaseStatus','storySource','storyPri','storyStatus','storyStage','bugSeverity','bugPri','bugType','bugOs','bugBrowser','bugStatus','taskType','taskPri','taskStatus','testcasePri','testcaseType','testcaseStage','testcaseStatus','testtaskPri','testtaskStatus','feedbackStatus','bugResolution','cases','feedbackModules','storyType','executions','projectModel','feedbackType','feedbackSolution','feedbackclosedReason','taskReason','testsuiteAuth','programs','storyClosedReason');

UPDATE `zt_product` SET `shadow` = 1 WHERE `vision` = 'lite';
