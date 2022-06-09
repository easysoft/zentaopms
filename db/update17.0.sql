ALTER TABLE `zt_review` ADD docVersion smallint(6) NOT NULL AFTER `doc`;

DELETE FROM `zt_workflowaction` WHERE `module`='story'   AND `action`='browse';
DELETE FROM `zt_workflowaction` WHERE `module`='task'    AND `action`='browse';
DELETE FROM `zt_workflowaction` WHERE `module`='build'   AND `action`='browse';