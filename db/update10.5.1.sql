UPDATE `zt_story` SET `stage` = 'closed' WHERE `closedReason` != '';
UPDATE `zt_config` SET `value` = 'consumed,comment' WHERE `module` = 'task' AND `section` = 'finish' AND `key` = 'requiredFields' AND `value` LIKE '%comment%';
DELETE FROM `zt_config` WHERE `module` = 'task' AND `section` = 'finish' AND `key` = 'requiredFields' AND `value` NOT LIKE '%comment%';
