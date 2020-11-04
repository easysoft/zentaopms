ALTER TABLE `zt_doc` ADD `mailto` text  AFTER `editedDate`;
ALTER TABLE `zt_task` CHANGE `realStarted` `realStarted` datetime NOT NULL AFTER `estStarted`;
