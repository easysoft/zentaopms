ALTER TABLE `zt_webhook` CHANGE `projects` `executions` text NOT NULL;
ALTER TABLE `zt_webhook` CHANGE `executions` `executions` text NOT NULL;

ALTER TABLE `zt_repo` ADD `extra` char(30) COLLATE 'utf8_general_ci' NOT NULL AFTER `desc`;
