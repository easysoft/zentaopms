UPDATE `zt_workflowaction` SET `module` = 'story', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'browse';
UPDATE `zt_workflowlayout` SET `module` = 'story', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'browse';

UPDATE `zt_workflowaction` SET `module` = 'requirement', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'requirement';
UPDATE `zt_workflowlayout` SET `module` = 'requirement', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'requirement';

UPDATE `zt_workflowaction` SET `module` = 'epic', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'epic';
UPDATE `zt_workflowlayout` SET `module` = 'epic', `action` = 'browse' WHERE `module` = 'product' AND `action` = 'epic';

UPDATE `zt_workflowaction` SET `module` = 'build', `action` = 'browse' WHERE `module` = 'execution' AND `action` = 'build';
UPDATE `zt_workflowlayout` SET `module` = 'build', `action` = 'browse' WHERE `module` = 'execution' AND `action` = 'build';

UPDATE `zt_workflowaction` SET `module` = 'task', `action` = 'browse' WHERE `module` = 'execution' AND `action` = 'task';
UPDATE `zt_workflowlayout` SET `module` = 'task', `action` = 'browse' WHERE `module` = 'execution' AND `action` = 'task';