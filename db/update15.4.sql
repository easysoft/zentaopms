UPDATE `zt_action` SET `action` = 'reviewpassed' where `action` = 'passreviewed';
UPDATE `zt_action` SET `action` = 'reviewrejected' where `action` = 'reviewclosed';
UPDATE `zt_action` SET `action` = 'reviewclarified' where `action` = 'clarifyreviewed';
