ALTER TABLE `zt_account`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_activity`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedBy` `assignedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL;

ALTER TABLE `zt_api`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_apistruct`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_assetlib`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `registerDate` `registerDate` datetime NULL;

ALTER TABLE `zt_attend`
CHANGE `reviewedBy` `reviewedBy` char(30) NULL DEFAULT '',
CHANGE `reviewedDate` `reviewedDate` datetime NULL;

ALTER TABLE `zt_auditcl`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedBy` `assignedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL;

ALTER TABLE `zt_auditplan`
CHANGE `checkDate` `checkDate` date NULL,
CHANGE `checkedBy` `checkedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedBy` `assignedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL;

ALTER TABLE `zt_basicmeas`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_branch`
CHANGE `closedDate` `closedDate` date NULL;

ALTER TABLE `zt_budget`
CHANGE `lastEditedBy` `lastEditedBy` char(30) NOT NULL DEFAULT '',
CHANGE `lastEditedDate` `lastEditedDate` date NULL;

ALTER TABLE `zt_bug`
CHANGE `assignedDate` `assignedDate` datetime NULL,
CHANGE `resolvedDate` `resolvedDate` datetime NULL,
CHANGE `closedDate` `closedDate` datetime NULL,
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL;

ALTER TABLE `zt_case`
CHANGE `scriptedBy` `scriptedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `scriptedDate` `scriptedDate` date NULL,
CHANGE `reviewedBy` `reviewedBy` varchar(255) NOT NULL DEFAULT '',
CHANGE `reviewedDate` `reviewedDate` date NULL,
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL,
CHANGE `lastRunner` `lastRunner` varchar(30) NOT NULL DEFAULT '',
CHANGE `lastRunDate` `lastRunDate` datetime NULL;

ALTER TABLE `zt_chart`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_cmcl`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_compile`
CHANGE `updateDate` `updateDate` datetime NULL;

ALTER TABLE `zt_cron`
CHANGE `lastTime` `lastTime` datetime NULL;

ALTER TABLE `zt_dataview`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_deploystep`
CHANGE `assignedTo` `assignedTo` char(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL,
CHANGE `finishedBy` `finishedBy` char(30) NOT NULL DEFAULT '',
CHANGE `finishedDate` `finishedDate` datetime NULL;

ALTER TABLE `zt_design`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedBy` `assignedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL;

ALTER TABLE `zt_doc`
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL,
CHANGE `approvedDate` `approvedDate` date NULL,
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_doclib`
CHANGE `product` `product` mediumint unsigned NOT NULL DEFAULT '0',
CHANGE `project` `project` mediumint unsigned NOT NULL DEFAULT '0',
CHANGE `execution` `execution` mediumint unsigned NOT NULL DEFAULT '0',
CHANGE `collector` `collector` text NOT NULL DEFAULT '',
CHANGE `desc` `desc` mediumtext NOT NULL DEFAULT '',
CHANGE `groups` `groups` varchar(255) NOT NULL DEFAULT '',
CHANGE `users` `users` text NOT NULL DEFAULT '',
CHANGE `order` `order` tinyint unsigned NOT NULL DEFAULT '0';

ALTER TABLE `zt_domain`
CHANGE `expiredDate` `expiredDate` datetime NULL,
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_durationestimation`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_entry`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_feedback`
CHANGE `reviewedBy` `reviewedBy` varchar(255) NOT NULL DEFAULT '',
CHANGE `reviewedDate` `reviewedDate` datetime NULL,
CHANGE `processedBy` `processedBy` char(30) NOT NULL DEFAULT '',
CHANGE `processedDate` `processedDate` datetime NULL,
CHANGE `closedBy` `closedBy` char(30)NOT NULL DEFAULT '',
CHANGE `closedDate` `closedDate` datetime NULL,
CHANGE `closedReason` `closedReason` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedBy` `editedBy` char(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedTo` `assignedTo` varchar(255) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL,
CHANGE `activatedBy` `activatedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `activatedDate` `activatedDate` datetime NULL,
CHANGE `feedbackBy` `feedbackBy` varchar(100) NOT NULL DEFAULT '';

ALTER TABLE `zt_gapanalysis`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_host`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_image`
CHANGE `restoreDate` `restoreDate` datetime NULL;

ALTER TABLE `zt_issue`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `activateBy` `activateBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `activateDate` `activateDate` date NULL,
CHANGE `closedBy` `closedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `closedDate` `closedDate` date NULL,
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedBy` `assignedBy` varchar(30) NULL,
CHANGE `assignedDate` `assignedDate` datetime NULL,
CHANGE `approvedDate` `approvedDate` date NULL;

ALTER TABLE `zt_kanban`
CHANGE `lastEditedBy` `lastEditedBy` char(30) NOT NULL DEFAULT '',
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL,
CHANGE `closedBy` `closedBy` char(30) NOT NULL DEFAULT '',
CHANGE `closedDate` `closedDate` datetime NULL,
CHANGE `activatedBy` `activatedBy` char(30) NOT NULL DEFAULT '',
CHANGE `activatedDate` `activatedDate` datetime NULL;

ALTER TABLE `zt_kanbancard`
CHANGE `lastEditedBy` `lastEditedBy` char(30) NOT NULL DEFAULT '',
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL,
CHANGE `archivedBy` `archivedBy` char(30) NOT NULL DEFAULT '',
CHANGE `archivedDate` `archivedDate` datetime NULL,
CHANGE `assignedBy` `assignedBy` char(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL;

ALTER TABLE `zt_kanbanlane`
CHANGE `lastEditedTime` `lastEditedTime` datetime NULL;

ALTER TABLE `zt_kanbanregion`
CHANGE `lastEditedBy` `lastEditedBy` char(30) NOT NULL DEFAULT '',
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL;

ALTER TABLE `zt_kanbanspace`
CHANGE `lastEditedBy` `lastEditedBy` char(30) NOT NULL DEFAULT '',
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL,
CHANGE `closedBy` `closedBy` char(30) NOT NULL DEFAULT '',
CHANGE `closedDate` `closedDate` datetime NULL,
CHANGE `activatedBy` `activatedBy` char(30) NOT NULL DEFAULT '',
CHANGE `activatedDate` `activatedDate` datetime NULL;

ALTER TABLE `zt_leave`
CHANGE `backDate` `backDate` datetime NULL,
CHANGE `reviewedBy` `reviewedBy` char(30) NOT NULL DEFAULT '',
CHANGE `reviewedDate` `reviewedDate` datetime NULL,
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `reviewers` `reviewers` text NOT NULL DEFAULT '',
CHANGE `backReviewers` `backReviewers` text NOT NULL DEFAULT '';

ALTER TABLE `zt_lieu`
CHANGE `reviewedBy` `reviewedBy` char(30) NOT NULL DEFAULT '',
CHANGE `reviewedDate` `reviewedDate` datetime NULL,
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `reviewers` `reviewers` text NOT NULL DEFAULT '';

ALTER TABLE `zt_measqueue`
CHANGE `updateDate` `updateDate` datetime NULL;

ALTER TABLE `zt_meeting`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_meetingroom`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_mr`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_nc`
CHANGE `resolvedBy` `resolvedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `resolution` `resolution` char(30) NOT NULL DEFAULT '',
CHANGE `resolvedDate` `resolvedDate` date NULL,
CHANGE `closedBy` `closedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `closedDate` `closedDate` date NULL,
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` date NULL,
CHANGE `activateDate` `activateDate` date NULL,
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_opportunity`
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` date NULL,
CHANGE `approvedDate` `approvedDate` date NULL,
CHANGE `plannedClosedDate` `plannedClosedDate` date NULL,
CHANGE `actualClosedDate` `actualClosedDate` date NULL,
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `activatedBy` `activatedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `activatedDate` `activatedDate` datetime NULL,
CHANGE `closedBy` `closedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `closedDate` `closedDate` datetime NULL,
CHANGE `canceledBy` `canceledBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `canceledDate` `canceledDate` datetime NULL,
CHANGE `cancelReason` `cancelReason` char(30) NOT NULL DEFAULT '',
CHANGE `hangupedBy` `hangupedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `hangupedDate` `hangupedDate` datetime NULL,
CHANGE `resolution` `resolution` mediumtext NOT NULL DEFAULT '',
CHANGE `resolvedBy` `resolvedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `resolvedDate` `resolvedDate` datetime NULL,
CHANGE `lastCheckedBy` `lastCheckedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `lastCheckedDate` `lastCheckedDate` datetime NULL;

ALTER TABLE `zt_overtime`
CHANGE `rejectReason` `rejectReason` varchar(100) NOT NULL DEFAULT '',
CHANGE `reviewedBy` `reviewedBy` char(30) NOT NULL DEFAULT '',
CHANGE `reviewedDate` `reviewedDate` datetime NULL,
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `reviewers` `reviewers` text NOT NULL DEFAULT '';

ALTER TABLE `zt_pipeline`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_process`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedBy` `assignedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL;

ALTER TABLE `zt_product`
CHANGE `code` `code` varchar(45) NOT NULL DEFAULT '',
CHANGE `shadow` `shadow` tinyint unsigned NOT NULL DEFAULT '0',
CHANGE `feedback` `feedback` varchar(30) NOT NULL DEFAULT '',
CHANGE `reviewer` `reviewer` text NOT NULL DEFAULT '',
CHANGE `order` `order` mediumint unsigned NOT NULL DEFAULT '0',
CHANGE `ticket` `ticket` varchar(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_productplan`
CHANGE `closedReason` `closedReason` varchar(20) NOT NULL DEFAULT '';

ALTER TABLE `zt_project`
CHANGE `model` `model` char(30) NOT NULL DEFAULT '',
CHANGE `output` `output` text NOT NULL DEFAULT '',
CHANGE `code` `code` varchar(45) NOT NULL DEFAULT '',
CHANGE `auth` `auth` char(30) NOT NULL DEFAULT '',
CHANGE `path` `path` varchar(255) NOT NULL DEFAULT '',
CHANGE `grade` `grade` tinyint unsigned NOT NULL DEFAULT '0',
CHANGE `realBegan` `realBegan` date NULL,
CHANGE `realEnd` `realEnd` date NULL,
CHANGE `days` `days` smallint unsigned NOT NULL DEFAULT '0',
CHANGE `desc` `desc` mediumtext NOT NULL DEFAULT '',
CHANGE `version` `version` smallint NOT NULL DEFAULT '0',
CHANGE `parentVersion` `parentVersion` smallint NOT NULL DEFAULT '0',
CHANGE `planDuration` `planDuration` int NOT NULL DEFAULT '0',
CHANGE `realDuration` `realDuration` int NOT NULL DEFAULT '0',
CHANGE `openedVersion` `openedVersion` varchar(20) NOT NULL DEFAULT '',
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL,
CHANGE `closedDate` `closedDate` datetime NULL,
CHANGE `canceledDate` `canceledDate` datetime NULL,
CHANGE `suspendedDate` `suspendedDate` date NULL,
CHANGE `team` `team` varchar(90) NOT NULL DEFAULT '',
CHANGE `whitelist` `whitelist` text NOT NULL DEFAULT '',
CHANGE `order` `order` mediumint unsigned NOT NULL DEFAULT '0';

ALTER TABLE `zt_researchplan`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_researchreport`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_review`
CHANGE `reviewedBy` `reviewedBy` varchar(255) NOT NULL DEFAULT '',
CHANGE `auditedBy` `auditedBy` varchar(255) NOT NULL DEFAULT '',
CHANGE `lastReviewedDate` `lastReviewedDate` date NULL,
CHANGE `lastAuditedBy` `lastAuditedBy` varchar(255) NOT NULL DEFAULT '',
CHANGE `lastAuditedDate` `lastAuditedDate` date NULL,
CHANGE `lastEditedBy` `lastEditedBy` varchar(255) NOT NULL DEFAULT '',
CHANGE `lastEditedDate` `lastEditedDate` date NULL,
CHANGE `result` `result` char(30) NOT NULL DEFAULT '',
CHANGE `auditResult` `auditResult` char(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_reviewcl`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedBy` `assignedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL;

ALTER TABLE `zt_reviewissue`
CHANGE `opinion` `opinion` varchar(255) NOT NULL DEFAULT '',
CHANGE `opinionDate` `opinionDate` date NULL,
CHANGE `resolution` `resolution` char(30) NOT NULL DEFAULT '',
CHANGE `resolutionBy` `resolutionBy` char(30) NOT NULL DEFAULT '',
CHANGE `resolutionDate` `resolutionDate` date NULL;

ALTER TABLE `zt_reviewlist`
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedBy` `assignedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL;

ALTER TABLE `zt_risk`
CHANGE `plannedClosedDate` `plannedClosedDate` date NULL,
CHANGE `actualClosedDate` `actualClosedDate` date NULL,
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `resolution` `resolution` mediumtext NOT NULL DEFAULT '',
CHANGE `resolvedBy` `resolvedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `activateBy` `activateBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `activateDate` `activateDate` date NULL,
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `closedBy` `closedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `closedDate` `closedDate` date NULL,
CHANGE `cancelBy` `cancelBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `cancelDate` `cancelDate` date NULL,
CHANGE `cancelReason` `cancelReason` char(30) NOT NULL DEFAULT '',
CHANGE `hangupBy` `hangupBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `hangupDate` `hangupDate` date NULL,
CHANGE `trackedBy` `trackedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `trackedDate` `trackedDate` date NULL,
CHANGE `assignedDate` `assignedDate` date NULL,
CHANGE `approvedDate` `approvedDate` date NULL;

ALTER TABLE `zt_searchindex`
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_serverroom`
CHANGE `editedBy` `editedBy` char(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_service`
CHANGE `editedBy` `editedBy` char(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_solutions`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` date NULL;

ALTER TABLE `zt_sqlview`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_stage`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_stakeholder`
CHANGE `editedBy` `editedBy` char(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` date NULL;

ALTER TABLE `zt_story`
CHANGE `assignedDate` `assignedDate` datetime NULL,
CHANGE `approvedDate` `approvedDate` date NULL,
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL,
CHANGE `changedBy` `changedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `changedDate` `changedDate` datetime NULL,
CHANGE `reviewedBy` `reviewedBy` varchar(255) NOT NULL DEFAULT '',
CHANGE `reviewedDate` `reviewedDate` datetime NULL,
CHANGE `closedDate` `closedDate` datetime NULL,
CHANGE `closedReason` `closedReason` varchar(30) NOT NULL DEFAULT '',
CHANGE `activatedDate` `activatedDate` datetime NULL,
CHANGE `feedbackBy` `feedbackBy` varchar(100) NOT NULL DEFAULT '',
CHANGE `notifyEmail` `notifyEmail` varchar(100) NOT NULL DEFAULT '';

ALTER TABLE `zt_storyreview`
CHANGE `reviewDate` `reviewDate` datetime NULL;

ALTER TABLE `zt_task`
CHANGE `estStarted` `estStarted` date NULL,
CHANGE `realStarted` `realStarted` datetime NULL,
CHANGE `finishedBy` `finishedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `finishedDate` `finishedDate` datetime NULL,
CHANGE `finishedList` `finishedList` text NOT NULL DEFAULT '',
CHANGE `canceledBy` `canceledBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `canceledDate` `canceledDate` datetime NULL,
CHANGE `closedBy` `closedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `closedDate` `closedDate` datetime NULL,
CHANGE `realDuration` `realDuration` int NOT NULL DEFAULT '0',
CHANGE `closedReason` `closedReason` varchar(30) NOT NULL DEFAULT '',
CHANGE `lastEditedBy` `lastEditedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL,
CHANGE `activatedDate` `activatedDate` datetime NULL;

ALTER TABLE `zt_team`
CHANGE `position` `position` varchar(30) NOT NULL DEFAULT '',
CHANGE `join` `join` date NULL;

ALTER TABLE `zt_testrun`
CHANGE `lastRunner` `lastRunner` varchar(30) NOT NULL DEFAULT '',
CHANGE `lastRunDate` `lastRunDate` datetime NULL,
CHANGE `lastRunResult` `lastRunResult` char(30) NOT NULL DEFAULT '';

ALTER TABLE `zt_testsuite`
CHANGE `lastEditedBy` `lastEditedBy` char(30) NOT NULL DEFAULT '',
CHANGE `lastEditedDate` `lastEditedDate` datetime NULL;

ALTER TABLE `zt_testtask`
CHANGE `realFinishedDate` `realFinishedDate` datetime NULL;

ALTER TABLE `zt_ticket`
CHANGE `assignedTo` `assignedTo` varchar(255) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL,
CHANGE `realStarted` `realStarted` datetime NULL,
CHANGE `startedBy` `startedBy` varchar(255) NOT NULL DEFAULT '',
CHANGE `startedDate` `startedDate` datetime NULL,
CHANGE `activatedCount` `activatedCount` int NOT NULL DEFAULT '0',
CHANGE `activatedBy` `activatedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `activatedDate` `activatedDate` datetime NULL,
CHANGE `closedBy` `closedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `closedDate` `closedDate` datetime NULL,
CHANGE `closedReason` `closedReason` varchar(30) NOT NULL DEFAULT '',
CHANGE `finishedBy` `finishedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `finishedDate` `finishedDate` datetime NULL,
CHANGE `resolvedBy` `resolvedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `resolvedDate` `resolvedDate` datetime NULL,
CHANGE `resolution` `resolution` varchar(1000) NOT NULL DEFAULT '',
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_todo`
CHANGE `feedback` `feedback` mediumint unsigned NOT NULL DEFAULT '0',
CHANGE `config` `config` varchar(255) NOT NULL DEFAULT '',
CHANGE `private` `private` tinyint(1) NOT NULL DEFAULT '0',
CHANGE `assignedDate` `assignedDate` datetime NULL,
CHANGE `finishedDate` `finishedDate` datetime NULL,
CHANGE `closedDate` `closedDate` datetime NULL;

ALTER TABLE `zt_traincourse`
CHANGE `editedBy` `editedBy` varchar(255) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` date NULL;

ALTER TABLE `zt_traincontents`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_trainplan`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_user`
CHANGE `company` `company` mediumint unsigned NOT NULL DEFAULT '0',
CHANGE `commiter` `commiter` varchar(100) NOT NULL DEFAULT '',
CHANGE `avatar` `avatar` text NOT NULL DEFAULT '',
CHANGE `nature` `nature` text NOT NULL DEFAULT '',
CHANGE `analysis` `analysis` text NOT NULL DEFAULT '',
CHANGE `strategy` `strategy` text NOT NULL DEFAULT '',
CHANGE `ldap` `ldap` char(30) NOT NULL DEFAULT '',
CHANGE `resetToken` `resetToken` varchar(50) NOT NULL DEFAULT '',
CHANGE `birthday` `birthday` date NULL,
CHANGE `join` `join` date NULL,
CHANGE `locked` `locked` datetime NULL;

ALTER TABLE `zt_workestimation`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL,
CHANGE `assignedTo` `assignedTo` varchar(30) NOT NULL DEFAULT '',
CHANGE `assignedDate` `assignedDate` datetime NULL;

ALTER TABLE `zt_workflow`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_workflowaction`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_workflowdatasource`
CHANGE `editedBy` `editedBy` char(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_workflowfield`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_workflowlabel`
CHANGE `editedBy` `editedBy` char(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_workflowrule`
CHANGE `editedBy` `editedBy` char(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_workflowsql`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;

ALTER TABLE `zt_zoutput`
CHANGE `editedBy` `editedBy` varchar(30) NOT NULL DEFAULT '',
CHANGE `editedDate` `editedDate` datetime NULL;
