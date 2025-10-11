ALTER TABLE `zt_doc` ADD `isDeliverable` tinyint(1) NOT NULL DEFAULT 0 AFTER `acl`;
ALTER TABLE `zt_deliverable` ADD `workflowGroup` int(8) NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_deliverable` ADD `activity` int(8) unsigned NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_deliverable` ADD `trimmable` char(30) NOT NULL DEFAULT '0' AFTER `activity`;
ALTER TABLE `zt_deliverable` ADD `trimRule` varchar(255) NOT NULL AFTER `trimmable`;
ALTER TABLE `zt_deliverable` ADD `template` text NOT NULL AFTER `trimRule`;
ALTER TABLE `zt_deliverable` ADD `status` varchar(30) NOT NULL DEFAULT 'enabled' AFTER `name`;
ALTER TABLE `zt_deliverable` ADD `category` varchar(255) NOT NULL DEFAULT '' AFTER `lastEditedDate`;
ALTER TABLE `zt_deliverable` ADD `builtin` tinyint(1) unsigned NOT NULL DEFAULT '0' AFTER `module`;

CREATE TABLE IF NOT EXISTS `zt_deliverablestage` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `deliverable` int(8) unsigned NOT NULL DEFAULT 0,
  `stage` varchar(30) NOT NULL,
  `required` varchar(30) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE UNIQUE INDEX `unique` ON `zt_deliverablestage`(`deliverable`,`stage`);
ALTER TABLE `zt_module` ADD `extra` varchar(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_process` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
ALTER TABLE `zt_process` ADD `module` int(8) unsigned NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_activity` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `process`;

UPDATE `zt_process`  SET `editedDate` = NULL WHERE `editedDate` LIKE '1970-01-01%';
UPDATE `zt_process`  SET `assignedDate` = NULL WHERE `assignedDate` LIKE '1970-01-01%';
UPDATE `zt_activity` SET `editedDate` = NULL WHERE `editedDate` LIKE '1970-01-01%';
UPDATE `zt_activity` SET `assignedDate` = NULL WHERE `assignedDate` LIKE '1970-01-01%';

ALTER TABLE `zt_searchindex` CHANGE `content` `content` longtext NULL;

CREATE TABLE IF NOT EXISTS `zt_projectdeliverable` (
  `id` int(8) unsigned NOT NULL AUTO_INCREMENT,
  `project` int(8) unsigned NOT NULL,
  `review` int(8) unsigned NOT NULL DEFAULT '0',
  `deliverable` varchar(30) NOT NULL,
  `name` varchar(255) NOT NULL,
  `doc` int(8) unsigned NOT NULL DEFAULT '0',
  `docVersion` smallint(6) unsigned NOT NULL DEFAULT '0',
  `status` varchar(30) NOT NULL DEFAULT '',
  `version` varchar(255) NULL,
  `createdBy` varchar(30) NOT NULL DEFAULT '',
  `createdDate` date NULL,
   PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
CREATE INDEX `project` ON `zt_projectdeliverable` (`project`);
CREATE UNIQUE INDEX `project_deliverable_doc` ON `zt_projectdeliverable` (`project`, `deliverable`, `doc`);

ALTER TABLE `zt_approvalflowobject` ADD `relatedBy` varchar(30) NOT NULL DEFAULT '' AFTER `objectID`;
ALTER TABLE `zt_approvalflowobject` ADD `relatedDate`  datetime NULL AFTER `relatedBy`;

ALTER TABLE `zt_reviewcl` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;

UPDATE `zt_lang` SET `value` = '单元测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'unittest'   AND `value` = '单元测试阶段';
UPDATE `zt_lang` SET `value` = '功能测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'feature'    AND `value` = '功能测试阶段';
UPDATE `zt_lang` SET `value` = '集成测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'intergrate' AND `value` = '集成测试阶段';
UPDATE `zt_lang` SET `value` = '系统测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'system'     AND `value` = '系统测试阶段';
UPDATE `zt_lang` SET `value` = '冒烟测试环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'smoke'      AND `value` = '冒烟测试阶段';
UPDATE `zt_lang` SET `value` = '版本验证环节' WHERE `module` = 'testcase' AND `section` = 'stageList' AND `key` = 'bvt'        AND `value` = '版本验证阶段';

ALTER TABLE `zt_review` ADD `version` varchar(255) NOT NULL DEFAULT '' AFTER `docVersion`;
ALTER TABLE `zt_review` ADD `deliverable` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `title`;
ALTER TABLE `zt_review` ADD `deliverables` varchar(255) NOT NULL DEFAULT '' AFTER `deliverable`;
ALTER TABLE `zt_review` ADD `isBaseline` tinyint(1) DEFAULT '0' AFTER `status`;
ALTER TABLE `zt_review` ADD `type` varchar(30) NOT NULL DEFAULT '' AFTER `version`;
UPDATE `zt_review` SET `status` = 'reviewing' WHERE `status` = 'wait';
UPDATE `zt_review` SET `status` = 'pass' WHERE `status` = 'auditing' OR `status` = 'done';
UPDATE `zt_review` SET `type` = 'deliverable';

UPDATE `zt_review` AS t1
JOIN `zt_object` AS t2 ON t1.object = t2.id
SET t1.version = t2.version;

ALTER TABLE `zt_object` ADD `status` varchar(20) NOT NULL DEFAULT '' AFTER `type`;
ALTER TABLE `zt_object` ADD `approval`  mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `status`;
ALTER TABLE `zt_object` ADD `reviewResult` varchar(20) NOT NULL DEFAULT '' AFTER `approval`;
ALTER TABLE `zt_object` ADD `reviewOpinion` text NULL AFTER `reviewResult`;
ALTER TABLE `zt_object` ADD `reviewers` text NULL AFTER `reviewResult`;
ALTER TABLE `zt_object` ADD `categoryVersion` text DEFAULT NULL AFTER `category`;

ALTER TABLE `zt_designspec` ADD `docs` text NULL AFTER `files`;
ALTER TABLE `zt_designspec` ADD `docVersions` text NULL AFTER `docs`;
UPDATE `zt_designspec` AS t1
JOIN `zt_design` AS t2 ON t1.design = t2.id AND t2.version = t1.version
SET t1.docs = t2.docs, t1.docVersions = t2.docVersions;
ALTER TABLE `zt_design` DROP COLUMN `docs`;
ALTER TABLE `zt_design` DROP COLUMN `docVersions`;

ALTER TABLE `zt_reviewissue` ADD `assignedTo` char(30) COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '' AFTER `createdDate`;
ALTER TABLE `zt_reviewissue` ADD `assignedDate` datetime NULL AFTER `assignedTo`;

REPLACE INTO `zt_grouppriv`(`group`, `module`, `method`) SELECT `group`, `module`, 'active' as `method` FROM `zt_grouppriv` WHERE `module` = 'reviewissue' AND `method` = 'updateStatus';
REPLACE INTO `zt_grouppriv`(`group`, `module`, `method`) SELECT `group`, `module`, 'close' as `method` FROM `zt_grouppriv` WHERE `module` = 'reviewissue' AND `method` = 'updateStatus';
DELETE FROM `zt_grouppriv` WHERE `module` = 'reviewissue' AND `method` = 'updateStatus';

INSERT INTO `zt_metric` (`purpose`, `scope`, `object`, `stage`, `type`, `name`, `alias`, `code`, `unit`, `desc`, `definition`, `when`, `createdBy`, `createdDate`, `builtin`, `deleted`, `dateType`) VALUES
('scale', 'user', 'reviewissue', 'released', 'php', '按人员统计的被指派的评审意见数	', '被指派的评审意见数', 'count_of_assigned_reviewissue_in_user', 'count', '按人员统计的被指派的评审意见数表示每个人被指派的评审意见数量之和，反映了每个人员需要处理的评审意见数量的规模。该数值越大，说明需要投入越多的时间处理评审意见。', '所有评审意见个数求和\r\n指派给为某人\r\n过滤已删除的评审意见\r\n过滤已关闭的评审意见\r\n过滤已删除项目的评审意见', '', 'system', '2024-05-07 08:00:00', '1', '0', 'nodate');

ALTER TABLE `zt_approvalnode` CHANGE `result` `result` varchar(20) NOT NULL DEFAULT '';

DELETE FROM `zt_workflowgroup` WHERE `projectModel` = 'kanban';
UPDATE `zt_project` SET `workflowGroup` = '0' WHERE `model` = 'kanban';

ALTER TABLE `zt_stage` ADD `workflowGroup` mediumint(8) unsigned NOT NULL DEFAULT '0' AFTER `id`;
