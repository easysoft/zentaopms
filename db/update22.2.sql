UPDATE `zt_workflowfield` SET `control` = 'multi-select' WHERE `control` = 'multi-selec';
DELETE FROM `zt_workflowaction` WHERE `action` IN ('link', 'unlink');