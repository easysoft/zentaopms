ALTER TABLE `zt_project` ADD `openedVersion` varchar(20) COLLATE 'utf8_general_ci' NOT NULL AFTER `openedDate`;
ALTER TABLE `zt_product` ADD `createdVersion` varchar(20) COLLATE 'utf8_general_ci' NOT NULL AFTER `createdDate`;

ALTER TABLE  `zt_product` DROP  `order`;
ALTER TABLE  `zt_project` DROP  `order`;

ALTER TABLE `zt_story` CHANGE `reviewedBy` `reviewedBy` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

ALTER TABLE `zt_build` DROP INDEX `name`;

ALTER TABLE `zt_project` DROP INDEX `company` , ADD INDEX `project` ( `type` , `parent` , `begin` , `end` , `status` , `statge` , `pri` );
ALTER TABLE `zt_user` DROP INDEX `company`;

ALTER TABLE `zt_action` DROP `company`;
ALTER TABLE `zt_bug` DROP `company`;
ALTER TABLE `zt_build` DROP `company`;
ALTER TABLE `zt_burn` DROP `company`;
ALTER TABLE `zt_case` DROP `company`;
ALTER TABLE `zt_caseStep` DROP `company`;
ALTER TABLE `zt_dept` DROP `company`;
ALTER TABLE `zt_doc` DROP `company`;
ALTER TABLE `zt_docLib` DROP `company`;
ALTER TABLE `zt_extension` DROP `company`;
ALTER TABLE `zt_effort` DROP `company`;
ALTER TABLE `zt_file` DROP `company`;
ALTER TABLE `zt_group` DROP `company`;
ALTER TABLE `zt_history` DROP `company`;
ALTER TABLE `zt_module` DROP `company`;
ALTER TABLE `zt_product` DROP `company`;
ALTER TABLE `zt_productPlan` DROP `company`;
ALTER TABLE `zt_project` DROP `company`;
ALTER TABLE `zt_projectProduct` DROP `company`;
ALTER TABLE `zt_projectStory` DROP `company`;
ALTER TABLE `zt_release` DROP `company`;
ALTER TABLE `zt_story` DROP `company`;
ALTER TABLE `zt_storySpec` DROP `company`;
ALTER TABLE `zt_task` DROP `company`;
ALTER TABLE `zt_taskEstimate` DROP `company`;
ALTER TABLE `zt_team` DROP `company`;
ALTER TABLE `zt_testResult` DROP `company`;
ALTER TABLE `zt_testRun` DROP `company`;
ALTER TABLE `zt_testTask` DROP `company`;
ALTER TABLE `zt_todo` DROP `company`;
ALTER TABLE `zt_user` DROP `company`;
ALTER TABLE `zt_userContact` DROP `company`;
ALTER TABLE `zt_userGroup` DROP `company`;
ALTER TABLE `zt_userQuery` DROP `company`;
ALTER TABLE `zt_userTPL` DROP `company`;
ALTER TABLE `zt_webapp` DROP `company`;
