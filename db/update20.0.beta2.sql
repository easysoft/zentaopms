UPDATE `zt_workflowfield` SET `default` = 0 WHERE `field` = 'approva' AND `role` = 'approval' AND `default` = '';
UPDATE `zt_workflowfield` SET `default` = 'wait' WHERE `field` = 'reviewStatus' AND `role` = 'approval' AND `default` = '';
