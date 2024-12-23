UPDATE `zt_workflowfield` SET `default` = 0 WHERE `field` = 'approval' AND `role` = 'approval' AND `default` = '';
UPDATE `zt_workflowfield` SET `default` = 'wait' WHERE `field` = 'reviewStatus' AND `role` = 'approval' AND `default` = '';

ALTER TABLE `zt_project` ADD COLUMN `enabled` enum('on','off') NOT NULL DEFAULT 'on' AFTER `parallel`;
ALTER TABLE `zt_object`  ADD COLUMN `enabled` enum('0','1')    NOT NULL DEFAULT '1'  AFTER `type`;

DELETE FROM `zt_workflowaction` WHERE `module` = 'bug' AND `action` = 'confirm';
DELETE FROM `zt_workflowlayout` WHERE `module` = 'bug' AND `action` = 'confirm';

UPDATE `zt_workflowaction` SET `action` = 'confirm' WHERE `action` = 'confirmBug' AND `module` = 'bug';
UPDATE `zt_workflowlayout` SET `action` = 'confirm' WHERE `action` = 'confirmBug' AND `module` = 'bug';

ALTER TABLE zt_metriclib MODIFY id bigint AUTO_INCREMENT;

UPDATE `zt_grouppriv` SET `method` = 'recordWorkhour' WHERE `module` = 'task' AND `method` = 'recordEstimate';
UPDATE `zt_grouppriv` SET `method` = 'editEffort'     WHERE `module` = 'task' AND `method` = 'editEstimate';
UPDATE `zt_grouppriv` SET `method` = 'deleteWorkhour' WHERE `module` = 'task' AND `method` = 'deleteEstimate';

ALTER TABLE `zt_demandreview` CHANGE `reviewDate` `reviewDate` datetime NULL;

DELETE FROM `zt_lang` WHERE `module` = 'project' AND `section` = 'menuOrder';
DELETE FROM `zt_config` WHERE `module` = 'bi' AND `key` IN ('update2BI','bizGuide','pmsGuide');

UPDATE `zt_metriclib` SET `metricCode` = 'pv_of_weekly_task_in_waterfall'          WHERE `metricCode` = 'pv_of_task_in_waterfall';
UPDATE `zt_metriclib` SET `metricCode` = 'ev_of_weekly_finished_task_in_waterfall' WHERE `metricCode` = 'ev_of_finished_task_in_waterfall';
UPDATE `zt_metriclib` SET `metricCode` = 'ac_of_weekly_all_in_waterfall'           WHERE `metricCode` = 'ac_of_all_in_waterfall';
UPDATE `zt_metriclib` SET `metricCode` = 'sv_weekly_in_waterfall'                  WHERE `metricCode` = 'sv_in_waterfall';
UPDATE `zt_metriclib` SET `metricCode` = 'cv_weekly_in_waterfall'                  WHERE `metricCode` = 'cv_in_waterfall';
