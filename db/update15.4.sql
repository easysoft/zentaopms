UPDATE `zt_action` SET `action` = 'reviewpassed' WHERE `action` = 'passreviewed';
UPDATE `zt_action` SET `action` = 'reviewrejected' WHERE `action` = 'reviewclosed';
UPDATE `zt_action` SET `action` = 'reviewclarified' WHERE `action` = 'clarifyreviewed';
DELETE FROM `zt_score` WHERE `module` = 'tutorial' AND `method` = 'finish';
