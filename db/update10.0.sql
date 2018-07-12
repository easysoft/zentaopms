ALTER TABLE `zt_storyspec` CHANGE `title` `title` varchar(255) NOT NULL;
update `zt_todo` set assignedTo = 'closed', assignedDate = closedDate where status = 'closed';
update `zt_project` set status = 'closed' where status = 'done';

DROP TABLE IF EXISTS `zt_webhookdatas`;

ALTER TABLE `zt_webhook` ADD `domain` varchar(255) NOT NULL AFTER `url`;

ALTER TABLE `zt_action` DROP INDEX `action`;
ALTER TABLE `zt_action` ADD INDEX `date` (`date`);
ALTER TABLE `zt_action` ADD INDEX `actor` (`actor`);
ALTER TABLE `zt_action` ADD INDEX `project` (`project`);
ALTER TABLE `zt_action` ADD INDEX `objectID` (`objectID`);

ALTER TABLE `zt_block` DROP INDEX `block`;
ALTER TABLE `zt_block` ADD INDEX `account` (`account`);

ALTER TABLE `zt_bug` DROP INDEX `bug`;
ALTER TABLE `zt_bug` ADD INDEX `product` (`product`);
ALTER TABLE `zt_bug` ADD INDEX `project` (`project`);
ALTER TABLE `zt_bug` ADD INDEX `status` (`status`);
ALTER TABLE `zt_bug` ADD INDEX `plan` (`plan`);
ALTER TABLE `zt_bug` ADD INDEX `story` (`story`);
ALTER TABLE `zt_bug` ADD INDEX `case` (`case`);
ALTER TABLE `zt_bug` ADD INDEX `assignedTo` (`assignedTo`);

ALTER TABLE `zt_build` DROP INDEX `build`;
ALTER TABLE `zt_build` ADD INDEX `product` (`product`);
ALTER TABLE `zt_build` ADD INDEX `project` (`project`);

ALTER TABLE `zt_case` DROP INDEX `case`;
ALTER TABLE `zt_case` ADD INDEX `product` (`product`);
ALTER TABLE `zt_case` ADD INDEX `story` (`story`);
ALTER TABLE `zt_case` ADD INDEX `module` (`module`);

ALTER TABLE `zt_casestep` DROP INDEX `case`;
ALTER TABLE `zt_casestep` ADD INDEX `case` (`case`);
ALTER TABLE `zt_casestep` ADD INDEX `version` (`version`);

ALTER TABLE `zt_dept` DROP INDEX `dept`;
ALTER TABLE `zt_dept` ADD INDEX `parent` (`parent`);
ALTER TABLE `zt_dept` ADD INDEX `path` (`path`);

ALTER TABLE `zt_doc` DROP INDEX `doc`;
ALTER TABLE `zt_doc` ADD INDEX `product` (`product`);
ALTER TABLE `zt_doc` ADD INDEX `project` (`project`);
ALTER TABLE `zt_doc` ADD INDEX `lib` (`lib`);

ALTER TABLE `zt_doclib` ADD INDEX `product` (`product`);
ALTER TABLE `zt_doclib` ADD INDEX `project` (`project`);

ALTER TABLE `zt_extension` DROP INDEX `extension`;
ALTER TABLE `zt_extension` ADD INDEX `name` (`name`);
ALTER TABLE `zt_extension` ADD INDEX `installedTime` (`installedTime`);

ALTER TABLE `zt_file` DROP INDEX `file`;
ALTER TABLE `zt_file` ADD INDEX `objectType` (`objectType`);
ALTER TABLE `zt_file` ADD INDEX `objectID` (`objectID`);

ALTER TABLE `zt_module` DROP INDEX `module`;
ALTER TABLE `zt_module` ADD INDEX `root` (`root`);
ALTER TABLE `zt_module` ADD INDEX `type` (`type`);
ALTER TABLE `zt_module` ADD INDEX `path` (`path`);

ALTER TABLE `zt_productplan` DROP INDEX `plan`;
ALTER TABLE `zt_productplan` ADD INDEX `product` (`product`);
ALTER TABLE `zt_productplan` ADD INDEX `end` (`end`);

ALTER TABLE `zt_project` DROP INDEX `project`;
ALTER TABLE `zt_project` ADD INDEX `parent` (`parent`);
ALTER TABLE `zt_project` ADD INDEX `begin` (`begin`);
ALTER TABLE `zt_project` ADD INDEX `end` (`end`);
ALTER TABLE `zt_project` ADD INDEX `status` (`status`);
ALTER TABLE `zt_project` ADD INDEX `order` (`order`);

ALTER TABLE `zt_release` ADD INDEX `product` (`product`);

ALTER TABLE `zt_story` DROP INDEX `story`;
ALTER TABLE `zt_story` ADD INDEX `product` (`product`);
ALTER TABLE `zt_story` ADD INDEX `status` (`status`);
ALTER TABLE `zt_story` ADD INDEX `assignedTo` (`assignedTo`);

ALTER TABLE `zt_task` DROP INDEX `task`;
ALTER TABLE `zt_task` ADD INDEX `project` (`project`);
ALTER TABLE `zt_task` ADD INDEX `story` (`story`);
ALTER TABLE `zt_task` ADD INDEX `assignedTo` (`assignedTo`);

ALTER TABLE `zt_testresult` DROP INDEX `testresult`;
ALTER TABLE `zt_testresult` ADD INDEX `case` (`case`);
ALTER TABLE `zt_testresult` ADD INDEX `version` (`version`);
ALTER TABLE `zt_testresult` ADD INDEX `run` (`run`);

ALTER TABLE `zt_testsuite` ADD INDEX `product` (`product`);

ALTER TABLE `zt_testtask` ADD INDEX `product` (`product`);

ALTER TABLE `zt_todo` DROP INDEX `todo`;
ALTER TABLE `zt_todo` ADD INDEX `account` (`account`);
ALTER TABLE `zt_todo` ADD INDEX `assignedTo` (`assignedTo`);
ALTER TABLE `zt_todo` ADD INDEX `finishedBy` (`finishedBy`);
ALTER TABLE `zt_todo` ADD INDEX `date` (`date`);

ALTER TABLE `zt_user` DROP INDEX `user`;
ALTER TABLE `zt_user` ADD INDEX `dept` (`dept`);
ALTER TABLE `zt_user` ADD INDEX `email` (`email`);
ALTER TABLE `zt_user` ADD INDEX `commiter` (`commiter`);

ALTER TABLE `zt_userquery` DROP INDEX `query`;
ALTER TABLE `zt_userquery` ADD INDEX `account` (`account`);
ALTER TABLE `zt_userquery` ADD INDEX `module` (`module`);
