INSERT INTO `zt_priv` (`module`, `method`, `parent`, `edition`, `vision`, `system`, `order`) VALUES ('traincourse', 'cloudImport', '125', ',biz,max,ipd,', ',rnd,', '1', '10');

INSERT INTO `zt_privlang` (`objectID`, `objectType`, `lang`, `key`, `value`, `desc`) VALUES
(2107, 'priv', 'de',    'traincourse-cloudImport', '', ''),
(2107, 'priv', 'en',    'traincourse-cloudImport', '', ''),
(2107, 'priv', 'fr',    'traincourse-cloudImport', '', ''),
(2107, 'priv', 'zh-cn', 'traincourse-cloudImport', '', ''),
(2107, 'priv', 'zh-tw', 'traincourse-cloudImport', '', '');

ALTER TABLE `zt_traincourse` ADD `importedStatus` enum('wait','doing','done') NOT NULL DEFAULT 'wait' AFTER `desc`;
ALTER TABLE `zt_traincourse` ADD `lastUpdatedTime` int UNSIGNED NOT NULL DEFAULT 0 AFTER `importedStatus`;

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
