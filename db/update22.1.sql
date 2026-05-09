ALTER TABLE `zt_project` ADD `schedule` mediumtext DEFAULT NULL AFTER `days`;

REPLACE INTO `zt_grouppriv` (`group`, `module`, `method`)
SELECT `group`, 'doc', 'copyDoc'
FROM `zt_grouppriv`
WHERE `module` = 'doc' AND `method` = 'moveDoc';
