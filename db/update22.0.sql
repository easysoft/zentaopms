ALTER TABLE `zt_user`    ADD COLUMN `jira` tinyint unsigned NOT NULL DEFAULT 0 AFTER `clientLang`;
ALTER TABLE `zt_project` ADD COLUMN `coverExecutionPriv` tinyint unsigned NOT NULL DEFAULT 1 AFTER `maxColWidth`;
UPDATE `zt_project` SET `coverExecutionPriv` = 0 WHERE `isTpl` = 0;
