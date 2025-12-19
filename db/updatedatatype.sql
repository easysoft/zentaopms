ALTER TABLE `zt_acl`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(40) NOT NULL DEFAULT 'whitelist',
  MODIFY COLUMN `source` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_action`
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `read` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `efforted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_action`
  MODIFY COLUMN `read` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_actionproduct`
  MODIFY COLUMN `action` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_actionrecent`
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `read` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `efforted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_actionrecent`
  MODIFY COLUMN `read` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_activity`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `process` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_activity`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_ai_assistant`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `modelId` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `desc` text NULL DEFAULT NULL,
  MODIFY COLUMN `systemMessage` text NULL DEFAULT NULL,
  MODIFY COLUMN `greetings` text NULL DEFAULT NULL,
  MODIFY COLUMN `enabled` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_ai_assistant`
  MODIFY COLUMN `enabled` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_ai_message`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `appID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `user` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `content` text NULL DEFAULT NULL,
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL;
ALTER TABLE `zt_ai_miniprogram`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `category` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `editedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `published` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `prompt` text NULL DEFAULT NULL,
  MODIFY COLUMN `builtIn` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_ai_miniprogram`
  MODIFY COLUMN `published` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `builtIn` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_ai_miniprogramfield`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `appID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'text',
  MODIFY COLUMN `required` char(1) NOT NULL DEFAULT '1';
ALTER TABLE `zt_ai_miniprogramfield`
  MODIFY COLUMN `required` tinyint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_ai_miniprogramstar`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `appID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `userID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL;
ALTER TABLE `zt_ai_model`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `type` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `vendor` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `credentials` text NULL DEFAULT NULL,
  MODIFY COLUMN `name` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `enabled` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_ai_model`
  MODIFY COLUMN `enabled` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_ai_prompt`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `name` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `module` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `targetForm` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'draft',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_ai_prompt`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_ai_promptrole`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_ai_promptrole`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_api`
  MODIFY COLUMN `owner` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `addedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_api`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_api_lib_release`
  MODIFY COLUMN `addedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_apispec`
  MODIFY COLUMN `owner` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `addedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_apistruct`
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `addedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_apistruct`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_apistruct_spec`
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `addedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_approval`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `flow` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` mediumint NOT NULL DEFAULT 1,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_approvalflow`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_approvalflowobject`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `root` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `flow` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `objectType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_approvalflowspec`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `flow` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` mediumint NOT NULL DEFAULT 1;
ALTER TABLE `zt_approvalnode`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `approval` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'review',
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `multipleType` varchar(10) NOT NULL DEFAULT 'and',
  MODIFY COLUMN `needAll` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `solicit` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `revertTo` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `forwardBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `reviewedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_approvalnode`
  MODIFY COLUMN `needAll` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `solicit` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_approvalobject`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `approval` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `objectType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `appliedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_approvalrole`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `code` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_approvalrole`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_artifactrepo`
  MODIFY COLUMN `serverID` smallint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(7) NOT NULL DEFAULT '';
ALTER TABLE `zt_assetlib`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_assetlib`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_attend`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `reviewStatus` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `reviewedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_attendstat`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `month` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_auditcl`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `model` varchar(30) NOT NULL DEFAULT 'waterfall',
  MODIFY COLUMN `practiceArea` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_auditcl`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_auditplan`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `dateType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `process` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `processType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `result` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_auditplan`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_auditresult`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `auditplan` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `listID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `result` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `severity` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_auditresult`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_autocache`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `zt_automation`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `zt_basicmeas`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `scope` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `object` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `code` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_basicmeas`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_block`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `width` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `left` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_block`
  MODIFY COLUMN `width` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `left` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_branch`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `default` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'active',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `closedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_branch`
  MODIFY COLUMN `default` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_budget`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `stage` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `subject` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `amount` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastEditedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_budget`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_bug`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `plan` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `storyVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `task` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `toTask` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `toStory` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `severity` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'active',
  MODIFY COLUMN `confirmed` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `activatedCount` smallint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `duplicateBug` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `case` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `caseVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `feedback` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `result` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `repo` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `mr` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `testtask` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_bug`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_build`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `name` varchar(150) NOT NULL DEFAULT '',
  MODIFY COLUMN `system` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `scmPath` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `filePath` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `artifactRepoID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `builder` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_build`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_burn`
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `task` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `date` date NULL DEFAULT NULL,
  MODIFY COLUMN `estimate` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `left` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `consumed` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `storyPoint` decimal(10,2) unsigned NOT NULL DEFAULT 0.00;
ALTER TABLE `zt_case`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `lib` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `path` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `storyVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `scriptedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `frequency` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `openedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `fromBug` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fromCaseID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `lastRunResult` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `scene` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `sort` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_case`
  MODIFY COLUMN `frequency` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_casespec`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `case` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_casestep`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `case` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_cfd`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `count` smallint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_chart`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `driver` varchar(10) NOT NULL DEFAULT 'mysql',
  MODIFY COLUMN `mode` varchar(10) NOT NULL DEFAULT 'builder',
  MODIFY COLUMN `dimension` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `acl` varchar(10) NOT NULL DEFAULT 'open',
  MODIFY COLUMN `stage` varchar(10) NOT NULL DEFAULT 'draft',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_charter`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `category` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `check` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `appliedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `budget` decimal(12,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `budgetUnit` varchar(30) NOT NULL DEFAULT 'CNY',
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `closedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `activatedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `reviewedResult` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_charter`
  MODIFY COLUMN `check` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_charterproduct`
  MODIFY COLUMN `charter` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_cmcl`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `title` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_cmcl`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_company`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `name` varchar(120) NOT NULL DEFAULT '',
  MODIFY COLUMN `phone` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `fax` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `address` varchar(120) NOT NULL DEFAULT '',
  MODIFY COLUMN `zipcode` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `website` varchar(120) NOT NULL DEFAULT '',
  MODIFY COLUMN `backyard` varchar(120) NOT NULL DEFAULT '',
  MODIFY COLUMN `guest` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `admins` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_company`
  MODIFY COLUMN `guest` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_compile`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `job` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `queue` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `testtask` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_compile`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_config`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `owner` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `section` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `key` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_cron`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `buildin` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_dataset`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_dataview`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `driver` varchar(10) NOT NULL DEFAULT 'mysql',
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_deliverable`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `name` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `module` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `method` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'file',
  MODIFY COLUMN `files` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastEditedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_deliverable`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_demand`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `pool` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `pri` tinyint unsigned NOT NULL DEFAULT 3,
  MODIFY COLUMN `category` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `source` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `assignedTo` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `stage` varchar(20) NOT NULL DEFAULT 'wait',
  MODIFY COLUMN `duration` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `BSA` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `roadmap` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `duplicateDemand` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `parentVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `changedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `closedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `feedback` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_demand`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_demandpool`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `acl` char(30) NOT NULL DEFAULT 'open',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_demandpool`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_demandreview`
  MODIFY COLUMN `demand` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_demandspec`
  MODIFY COLUMN `demand` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_deploy`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `owner` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_deploy`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_deployproduct`
  MODIFY COLUMN `deploy` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `release` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_deploystep`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `deploy` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `assignedTo` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `finishedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_deploystep`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_dept`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `name` varchar(60) NOT NULL DEFAULT '',
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `path` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `position` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `function` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `manager` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_design`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_design`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_designspec`
  MODIFY COLUMN `design` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_dimension`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `acl` varchar(10) NOT NULL DEFAULT 'open',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_dimension`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_doc`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `lib` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `cycle` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `path` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `assetLib` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `from` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fromVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `approvedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `builtIn` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_doc`
  MODIFY COLUMN `builtIn` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_docaction`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `doc` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `actor` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_docblock`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `doc` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `extra` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_doccontent`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `doc` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `fromVersion` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_doclib`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `main` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `archived` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_doclib`
  MODIFY COLUMN `main` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `archived` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_durationestimation`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `stage` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_durationestimation`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_effort`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `left` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `consumed` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `begin` char(4) NOT NULL DEFAULT '',
  MODIFY COLUMN `end` char(4) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_effort`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_entry`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `key` char(32) NOT NULL DEFAULT '',
  MODIFY COLUMN `freePasswd` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_entry`
  MODIFY COLUMN `freePasswd` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_expect`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `userID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_expect`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_extension`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `zt_extuser`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `code` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_faq`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_feedback`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `solution` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `public` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `notify` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `result` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `faq` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `openedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `processedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `closedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `repeatFeedback` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_feedback`
  MODIFY COLUMN `public` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `notify` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_feedbackview`
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_file`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `pathname` varchar(100) NOT NULL DEFAULT '',
  MODIFY COLUMN `extension` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `addedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `downloads` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_file`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_gapanalysis`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `needTrain` varchar(10) NOT NULL DEFAULT 'no',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_gapanalysis`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_group`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `role` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `desc` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `developer` char(1) NOT NULL DEFAULT '1';
ALTER TABLE `zt_group`
  MODIFY COLUMN `developer` tinyint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_grouppriv`
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `method` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_history`
  MODIFY COLUMN `action` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_holiday`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'holiday';
ALTER TABLE `zt_host`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `vnc` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `ztf` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `zd` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `ssh` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `serverRoom` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_host`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_image`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `memory` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `disk` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `fileSize` decimal(10,2) unsigned NOT NULL DEFAULT 0.00;
ALTER TABLE `zt_instance`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `space` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `solution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `name` varchar(50) NOT NULL DEFAULT '',
  MODIFY COLUMN `appID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `appName` varchar(50) NOT NULL DEFAULT '',
  MODIFY COLUMN `appVersion` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `chart` varchar(50) NOT NULL DEFAULT '',
  MODIFY COLUMN `version` varchar(50) NOT NULL DEFAULT '',
  MODIFY COLUMN `source` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `channel` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `k8name` varchar(64) NOT NULL DEFAULT '',
  MODIFY COLUMN `status` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `pinned` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `domain` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `smtpSnippetName` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `ldapSnippetName` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `autoBackup` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `autoRestore` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_instance`
  MODIFY COLUMN `pinned` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_intervention`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `activity` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_intervention`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_issue`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `pri` tinyint unsigned NOT NULL DEFAULT 3,
  MODIFY COLUMN `severity` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `resolution` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lib` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `from` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `activateDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `approvedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_issue`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_job`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `repo` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `autoRun` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `server` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `sonarqubeServer` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_job`
  MODIFY COLUMN `autoRun` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_kanban`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `space` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `archived` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `performable` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'active',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `displayCards` smallint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `showWIP` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `fluidBoard` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `colWidth` smallint unsigned NOT NULL DEFAULT 264,
  MODIFY COLUMN `minColWidth` smallint unsigned NOT NULL DEFAULT 200,
  MODIFY COLUMN `maxColWidth` smallint unsigned NOT NULL DEFAULT 384,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `closedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `activatedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_kanban`
  MODIFY COLUMN `archived` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `performable` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `showWIP` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `fluidBoard` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_kanbancard`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `kanban` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `region` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fromID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `pri` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `estimate` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `progress` decimal(5,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `archived` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `archivedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `assignedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_kanbancard`
  MODIFY COLUMN `archived` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_kanbancell`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `kanban` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `lane` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `column` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_kanbancolumn`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `parent` int NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `region` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `archived` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_kanbancolumn`
  MODIFY COLUMN `archived` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_kanbangroup`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `kanban` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `region` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_kanbanlane`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `region` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `groupby` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `extra` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_kanbanlane`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_kanbanregion`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `space` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `kanban` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_kanbanregion`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_kanbanspace`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'active',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `closedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `activatedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_kanbanspace`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_lang`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `system` char(1) NOT NULL DEFAULT '1';
ALTER TABLE `zt_lang`
  MODIFY COLUMN `system` tinyint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_leave`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `hours` decimal(4,1) unsigned NOT NULL DEFAULT 0.0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `reviewedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `level` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_lieu`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `hours` decimal(4,1) unsigned NOT NULL DEFAULT 0.0,
  MODIFY COLUMN `overtime` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `trip` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `reviewedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `level` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_log`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `action` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_mark`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` varchar(50) NOT NULL DEFAULT '1',
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_market`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `industry` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `maturity` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `competition` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_market`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_marketreport`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `market` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `research` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `participants` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_marketreport`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_measqueue`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `mid` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_measqueue`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_measrecords`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `mid` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `measCode` varchar(50) NOT NULL DEFAULT '',
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_meastemplate`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `model` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_meastemplate`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_meeting`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `dept` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `room` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_meeting`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_meetingroom`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `seats` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_meetingroom`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_metric`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `scope` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `object` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `stage` varchar(10) NOT NULL DEFAULT 'wait',
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'php',
  MODIFY COLUMN `builtin` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `fromID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `lastCalcRows` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_metric`
  MODIFY COLUMN `builtin` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_module`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `root` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `name` varchar(60) NOT NULL DEFAULT '',
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `path` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `from` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_module`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_mr`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `hostID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `mergeStatus` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `approvalStatus` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `needApproved` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `needCI` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `repoID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `jobID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `executionID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `compileID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `compileStatus` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `removeSourceBranch` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `squash` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `isFlow` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `synced` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `hasNoConflict` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_mr`
  MODIFY COLUMN `needApproved` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `needCI` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `removeSourceBranch` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `squash` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `isFlow` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `synced` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `hasNoConflict` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_mrapproval`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `mrID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `action` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_nc`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `auditplan` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `listID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `severity` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `resolution` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `closedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `assignedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `activateDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_nc`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_notify`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `action` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_oauth`
  MODIFY COLUMN `providerID` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_object`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `from` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `category` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'reviewed',
  MODIFY COLUMN `enabled` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `storyEst` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `taskEst` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `requestEst` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `testEst` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `devEst` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `designEst` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_object`
  MODIFY COLUMN `enabled` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_opportunity`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `source` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `strategy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `impact` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `chance` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `ratio` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `pri` tinyint unsigned NOT NULL DEFAULT 3,
  MODIFY COLUMN `assignedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `approvedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `lib` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `from` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `cancelReason` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_opportunity`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_overtime`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `hours` decimal(4,1) unsigned NOT NULL DEFAULT 0.0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `reviewedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `level` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_pipeline`
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `url` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `password` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `token` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `private` char(32) NOT NULL DEFAULT '',
  MODIFY COLUMN `instanceID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_pipeline`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_pivot`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `dimension` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `driver` varchar(10) NOT NULL DEFAULT 'mysql',
  MODIFY COLUMN `acl` varchar(10) NOT NULL DEFAULT 'open',
  MODIFY COLUMN `stage` varchar(10) NOT NULL DEFAULT 'draft',
  MODIFY COLUMN `builtin` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_pivot`
  MODIFY COLUMN `builtin` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_pivotdrill`
  MODIFY COLUMN `pivot` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `field` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `object` varchar(40) NOT NULL DEFAULT '',
  MODIFY COLUMN `whereSql` mediumtext NULL DEFAULT NULL,
  MODIFY COLUMN `condition` mediumtext NULL DEFAULT NULL,
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'published',
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'manual';
ALTER TABLE `zt_pivotspec`
  MODIFY COLUMN `pivot` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` varchar(10) NOT NULL DEFAULT '1',
  MODIFY COLUMN `driver` varchar(10) NOT NULL DEFAULT 'mysql';
ALTER TABLE `zt_planstory`
  MODIFY COLUMN `plan` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_practice`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `code` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `zt_process`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `model` varchar(30) NOT NULL DEFAULT 'waterfall',
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `abbr` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_process`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_product`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `program` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `bind` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `line` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `workflowGroup` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `acl` varchar(10) NOT NULL DEFAULT 'open',
  MODIFY COLUMN `draftEpics` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `activeEpics` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `changingEpics` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `reviewingEpics` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `finishedEpics` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `closedEpics` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `totalEpics` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `draftRequirements` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `activeRequirements` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `changingRequirements` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `reviewingRequirements` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `finishedRequirements` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `closedRequirements` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `totalRequirements` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `draftStories` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `activeStories` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `changingStories` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `reviewingStories` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `finishedStories` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `closedStories` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `totalStories` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `unresolvedBugs` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `closedBugs` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fixedBugs` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `totalBugs` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `plans` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `releases` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `closedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_product`
  MODIFY COLUMN `bind` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_productplan`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `parent` int NOT NULL DEFAULT 0,
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'wait',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_productplan`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_programactivity`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `process` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `activity` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `result` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `linkedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_programactivity`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_programoutput`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `process` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `activity` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `output` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `result` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `linkedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_programoutput`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_programprocess`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `process` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `abbr` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `linkedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_programprocess`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_programreport`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `template` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_programreport`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_project`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `charter` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `model` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT 'sprint',
  MODIFY COLUMN `category` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lifetime` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `budget` decimal(12,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `budgetUnit` varchar(30) NOT NULL DEFAULT 'CNY',
  MODIFY COLUMN `percent` decimal(5,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `milestone` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `auth` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `storyType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `workflowGroup` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `pri` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `parentVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `planDuration` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `realDuration` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `estimate` decimal(12,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `left` decimal(12,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `consumed` decimal(12,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `teamCount` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `market` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `suspendedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `tplAcl` varchar(30) NOT NULL DEFAULT 'open',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `stageBy` varchar(10) NOT NULL DEFAULT 'product',
  MODIFY COLUMN `displayCards` smallint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fluidBoard` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `multiple` char(1) NOT NULL DEFAULT '1',
  MODIFY COLUMN `parallel` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `enabled` varchar(10) NOT NULL DEFAULT 'on',
  MODIFY COLUMN `colWidth` smallint unsigned NOT NULL DEFAULT 264,
  MODIFY COLUMN `minColWidth` smallint unsigned NOT NULL DEFAULT 200,
  MODIFY COLUMN `maxColWidth` smallint unsigned NOT NULL DEFAULT 384,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_project`
  MODIFY COLUMN `milestone` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `pri` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `fluidBoard` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `multiple` tinyint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_projectadmin`
  MODIFY COLUMN `group` smallint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_projectcase`
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `case` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `count` int unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_projectproduct`
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_projectspec`
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `milestone` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_projectspec`
  MODIFY COLUMN `milestone` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_projectstory`
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_queue`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `cron` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `command` text NULL DEFAULT NULL,
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'wait',
  MODIFY COLUMN `execId` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_queue`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_relation`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `AType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `AID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `AVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `relation` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `BType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `BID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `BVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `extra` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_relationoftasks`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `pretask` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `condition` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `task` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `action` varchar(10) NOT NULL DEFAULT '';
ALTER TABLE `zt_release`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `shadow` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `system` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `marker` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_release`
  MODIFY COLUMN `marker` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_releaserelated`
  MODIFY COLUMN `release` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_repo`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `commits` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `synced` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `extra` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `preMerge` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `job` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_repo`
  MODIFY COLUMN `preMerge` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_repobranch`
  MODIFY COLUMN `repo` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `revision` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_repofiles`
  MODIFY COLUMN `repo` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `revision` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_repohistory`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `repo` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `commit` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_report`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `dimension` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `step` tinyint unsigned NOT NULL DEFAULT 2,
  MODIFY COLUMN `addedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_researchplan`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `method` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_researchplan`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_researchreport`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `relatedPlan` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `method` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_researchreport`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_review`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `object` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `template` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `lastReviewedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `lastAuditedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `lastEditedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `result` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `auditResult` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_review`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_reviewcl`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `object` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `category` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_reviewcl`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_reviewissue`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `review` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `approval` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `injection` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `identify` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT 'review',
  MODIFY COLUMN `listID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `resolution` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `resolutionBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `resolutionDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_reviewissue`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_reviewlist`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `object` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `category` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_reviewlist`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_reviewresult`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `review` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT 'review',
  MODIFY COLUMN `result` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `reviewer` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `remainIssue` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `consumed` decimal(10,2) unsigned NOT NULL DEFAULT 0.00;
ALTER TABLE `zt_risk`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `source` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `category` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `strategy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `impact` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `probability` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `rate` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `pri` tinyint unsigned NOT NULL DEFAULT 2,
  MODIFY COLUMN `lib` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `from` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `closedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `cancelReason` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `assignedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `approvedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_risk`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_riskissue`
  MODIFY COLUMN `risk` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `issue` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_roadmap`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `closedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `closedReason` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_roadmap`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_roadmapstory`
  MODIFY COLUMN `roadmap` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_scene`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `openedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `grade` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_scene`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_score`
  MODIFY COLUMN `before` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `score` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `after` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_screen`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `dimension` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `acl` varchar(10) NOT NULL DEFAULT 'open',
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'draft',
  MODIFY COLUMN `builtin` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_screen`
  MODIFY COLUMN `builtin` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_searchindex`
  MODIFY COLUMN `objectType` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_serverroom`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_serverroom`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_solutions`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `contents` text NULL DEFAULT NULL,
  MODIFY COLUMN `support` text NULL DEFAULT NULL,
  MODIFY COLUMN `measures` text NULL DEFAULT NULL,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `addedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `editedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_solutions`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_space`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `name` varchar(200) NOT NULL DEFAULT '',
  MODIFY COLUMN `k8space` varchar(64) NOT NULL DEFAULT '',
  MODIFY COLUMN `owner` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `default` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_sqlbuilder`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `objectType` varchar(50) NOT NULL DEFAULT '';
ALTER TABLE `zt_sqlview`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_sqlview`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_stage`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_stage`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_stakeholder`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `user` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `key` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `from` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `editedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_stakeholder`
  MODIFY COLUMN `key` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_story`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `isParent` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `root` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fromBug` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `feedback` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `estimate` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `stage` varchar(10) NOT NULL DEFAULT 'wait',
  MODIFY COLUMN `stagedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lib` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fromStory` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fromVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `approvedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `toBug` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `duplicateStory` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `parentVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `demandVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `storyChanged` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `BSA` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `duration` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `demand` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `URChanged` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `unlinkReason` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `retractedReason` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_story`
  MODIFY COLUMN `isParent` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `storyChanged` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `URChanged` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_storyestimate`
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `round` smallint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `average` decimal(10,2) unsigned NOT NULL DEFAULT 0.00;
ALTER TABLE `zt_storygrade`
  MODIFY COLUMN `type` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `grade` smallint NOT NULL DEFAULT 0,
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_storyreview`
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_storyspec`
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_storystage`
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `branch` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `stagedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_suitecase`
  MODIFY COLUMN `suite` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `case` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_system`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `integrated` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `latestRelease` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'active',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_system`
  MODIFY COLUMN `integrated` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_task`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `isParent` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `design` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `story` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `storyVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `fromBug` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `feedback` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fromIssue` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `estimate` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `consumed` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `left` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'wait',
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `planDuration` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `realDuration` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `repo` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `mr` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_task`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_taskestimate`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `task` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `left` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `consumed` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_taskspec`
  MODIFY COLUMN `task` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_taskteam`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `task` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `transfer` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'wait',
  MODIFY COLUMN `storyVersion` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_team`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `root` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'project',
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `role` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `limited` varchar(8) NOT NULL DEFAULT 'no',
  MODIFY COLUMN `hours` decimal(3,1) unsigned NOT NULL DEFAULT 0.0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_testreport`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `owner` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_testreport`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_testresult`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `run` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `case` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `job` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `compile` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `caseResult` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `duration` decimal(10,3) unsigned NOT NULL DEFAULT 0.000,
  MODIFY COLUMN `deploy` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_testrun`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `task` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `case` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `version` smallint unsigned NOT NULL DEFAULT 1,
  MODIFY COLUMN `assignedTo` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastRunResult` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `status` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_testsuite`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `addedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_testsuite`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_testtask`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `name` varchar(90) NOT NULL DEFAULT '',
  MODIFY COLUMN `execution` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'wait',
  MODIFY COLUMN `testreport` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_testtask`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_ticket`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `product` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `feedback` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `estimate` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `left` decimal(10,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `activatedCount` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `repeatTicket` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_ticket`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_ticketrelation`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `ticketId` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `objectId` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_ticketsource`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `ticketId` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_todo`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `begin` char(4) NOT NULL DEFAULT '',
  MODIFY COLUMN `end` char(4) NOT NULL DEFAULT '',
  MODIFY COLUMN `feedback` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(15) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `name` varchar(150) NOT NULL DEFAULT '',
  MODIFY COLUMN `status` varchar(10) NOT NULL DEFAULT 'wait',
  MODIFY COLUMN `private` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_todo`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_traincategory`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `path` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `grade` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_traincategory`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_traincontents`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `course` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `parent` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `path` varchar(255) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_traincourse`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `category` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `importedStatus` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `editedDate` datetime NULL DEFAULT NULL,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_traincourse`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_trainplan`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'inside',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_trainplan`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_trainrecords`
  MODIFY COLUMN `user` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `objectId` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_trip`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'trip',
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `from` varchar(50) NOT NULL DEFAULT '',
  MODIFY COLUMN `to` varchar(50) NOT NULL DEFAULT '',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_user`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `company` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT 'inside',
  MODIFY COLUMN `dept` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `role` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `superior` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `nickname` varchar(60) NOT NULL DEFAULT '',
  MODIFY COLUMN `gender` char(1) NOT NULL DEFAULT 'f',
  MODIFY COLUMN `email` varchar(90) NOT NULL DEFAULT '',
  MODIFY COLUMN `skype` varchar(90) NOT NULL DEFAULT '',
  MODIFY COLUMN `qq` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `mobile` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `phone` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `address` varchar(120) NOT NULL DEFAULT '',
  MODIFY COLUMN `zipcode` varchar(10) NOT NULL DEFAULT '',
  MODIFY COLUMN `visits` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `fails` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `feedback` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `ranzhi` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `ldap` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `score` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `scoreLevel` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `clientStatus` varchar(10) NOT NULL DEFAULT 'offline',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_user`
  MODIFY COLUMN `feedback` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_usercontact`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `public` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_usergroup`
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_userquery`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `shortcut` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `common` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_userquery`
  MODIFY COLUMN `shortcut` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `common` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_usertpl`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `type` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `public` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_usertpl`
  MODIFY COLUMN `public` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_userview`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `account` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_webhook`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `sendType` varchar(10) NOT NULL DEFAULT 'sync',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_webhook`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_weeklyreport`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `pv` decimal(12,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `ev` decimal(12,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `ac` decimal(12,2) unsigned NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `sv` decimal(12,2) NOT NULL DEFAULT 0.00,
  MODIFY COLUMN `cv` decimal(12,2) NOT NULL DEFAULT 0.00;
ALTER TABLE `zt_workestimation`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `project` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_workestimation`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_workflow`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `approval` varchar(10) NOT NULL DEFAULT 'disabled';
ALTER TABLE `zt_workflowaction`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'single',
  MODIFY COLUMN `batchMode` varchar(10) NOT NULL DEFAULT 'different',
  MODIFY COLUMN `position` varchar(20) NOT NULL DEFAULT 'browseandview',
  MODIFY COLUMN `layout` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `show` varchar(20) NOT NULL DEFAULT 'dropdownlist',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `toList` varchar(255) NOT NULL DEFAULT '';
ALTER TABLE `zt_workflowdatasource`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'option',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_workflowfield`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `canExport` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `canSearch` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `isValue` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `readonly` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_workflowfield`
  MODIFY COLUMN `canExport` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `canSearch` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `isValue` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `readonly` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_workflowgroup`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `main` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `exclusive` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_workflowgroup`
  MODIFY COLUMN `main` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `exclusive` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_workflowlabel`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_workflowlayout`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `ui` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `width` smallint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `readonly` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `mobileShow` char(1) NOT NULL DEFAULT '1';
ALTER TABLE `zt_workflowlayout`
  MODIFY COLUMN `readonly` tinyint unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `mobileShow` tinyint unsigned NOT NULL DEFAULT 1;
ALTER TABLE `zt_workflowlinkdata`
  MODIFY COLUMN `objectID` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `linkedID` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_workflowrelation`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `buildin` char(1) NOT NULL DEFAULT '0',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_workflowrelation`
  MODIFY COLUMN `buildin` tinyint unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_workflowrelationlayout`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `ui` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_workflowreport`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `module` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `name` varchar(100) NOT NULL DEFAULT '',
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'pie',
  MODIFY COLUMN `countType` varchar(10) NOT NULL DEFAULT 'sum',
  MODIFY COLUMN `displayType` varchar(10) NOT NULL DEFAULT 'value',
  MODIFY COLUMN `dimension` varchar(130) NOT NULL DEFAULT '',
  MODIFY COLUMN `fields` text NULL DEFAULT NULL,
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0;
ALTER TABLE `zt_workflowrule`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `type` varchar(10) NOT NULL DEFAULT 'regex',
  MODIFY COLUMN `createdBy` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `editedBy` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_workflowsql`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `zt_workflowui`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `group` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `module` varchar(30) NOT NULL DEFAULT '',
  MODIFY COLUMN `action` varchar(50) NOT NULL DEFAULT '',
  MODIFY COLUMN `name` varchar(30) NOT NULL DEFAULT '';
ALTER TABLE `zt_workflowversion`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT;
ALTER TABLE `zt_zoutput`
  MODIFY COLUMN `id` int unsigned NOT NULL AUTO_INCREMENT,
  MODIFY COLUMN `activity` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `optional` varchar(20) NOT NULL DEFAULT '',
  MODIFY COLUMN `order` int unsigned NOT NULL DEFAULT 0,
  MODIFY COLUMN `deleted` char(1) NOT NULL DEFAULT '0';
ALTER TABLE `zt_zoutput`
  MODIFY COLUMN `deleted` tinyint unsigned NOT NULL DEFAULT 0;
