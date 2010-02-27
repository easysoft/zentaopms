-- 20100226 add start and end date field to task.
/**ALTER TABLE `zt_task` ADD `deadline` DATE NOT NULL AFTER `left`;
ALTER TABLE `zt_task` CHANGE `owner` `assignedTo` CHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
ALTER TABLE `zt_task` ADD `openedBy` CHAR( 30 ) NOT NULL AFTER `pri` ,
ADD `openedDate` DATETIME NOT NULL AFTER `openedBy` ;
ALTER TABLE `zt_task` ADD `assignedDate` DATETIME NOT NULL AFTER `assignedTo`;
ALTER TABLE `zt_task` ADD `lastEditedBy` CHAR( 30 ) NOT NULL AFTER `assignedDate` ,
ADD `lastEditedDate` DATETIME NOT NULL AFTER `lastEditedBy` ,
ADD `closedBy` CHAR( 30 ) NOT NULL AFTER `lastEditedDate` ,
ADD `closedDate` DATETIME NOT NULL AFTER `closedBy`;
ALTER TABLE `zt_task` ADD `closedReason` CHAR( 30 ) NOT NULL AFTER `closedDate`;
ALTER TABLE `zt_task` CHANGE `status` `status` VARCHAR( 30 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT 'wait';

update zt_task, zt_action 
set zt_task.openedBy = zt_action.actor , zt_task.openedDate = zt_action.date
where zt_task.id = zt_action.objectID and zt_action.objectType = 'task' and zt_action.action='opened'*/
ALTER TABLE `zt_task` ADD `deadline` DATE NOT NULL AFTER `left` ;
