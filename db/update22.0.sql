ALTER TABLE `zt_user`    ADD COLUMN `jira` tinyint unsigned NOT NULL DEFAULT 0 AFTER `clientLang`;
ALTER TABLE `zt_project` ADD COLUMN `coverExecutionPriv` tinyint unsigned NOT NULL DEFAULT 1 AFTER `maxColWidth`;
UPDATE `zt_project` SET `coverExecutionPriv` = 0 WHERE `isTpl` = 0;

UPDATE `zt_doc` AS t1 LEFT JOIN `zt_doclib` AS t2 ON t1.`lib` = t2.`id` SET t1.`project` = 0, t1.`product` = 0 WHERE t2.`type` IN ('custom','mine');
DELETE FROM `zt_workflowfield` WHERE `module` IN('program','project','execution') AND `type` = 'enum' AND `field` = 'stage';
