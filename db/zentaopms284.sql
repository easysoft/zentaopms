update `zt_task` set `assignedTo` = '' where `mode` = 'multi' and `status` != 'done' and `status` != 'closed';

REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) SELECT `lang`, `module`, 'scrumClassify', `key`, `value`, `system`, `vision` FROM `zt_lang` WHERE `module` = 'process' and `section` = 'classify' ORDER BY id ASC;
REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`, `vision`) SELECT `lang`, `module`, 'agileplusClassify', `key`, `value`, `system`, `vision` FROM `zt_lang` WHERE `module` = 'process' and `section` = 'classify' ORDER BY id ASC;

UPDATE `zt_project` AS parent INNER JOIN (select `id`,`parent`,`attribute` from `zt_project` where `parent` != 0 and `type` = 'stage') AS child ON parent.`id` = child.`parent` SET parent.`attribute`='mix' where parent.`grade`=1 and parent.`type`='stage' and parent.`attribute` != child.`attribute`;
