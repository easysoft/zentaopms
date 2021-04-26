<<<<<<< 8f71af3835517e1753ce10785378aceafe5f8f91
ALTER TABLE `zt_webhook` CHANGE `projects` `executions` text NOT NULL;
ALTER TABLE `zt_webhook` CHANGE `executions` `executions` text NOT NULL;
=======
ALTER TABLE `zt_repo` ADD `extra` char(30) COLLATE 'utf8_general_ci' NOT NULL AFTER `desc`;
>>>>>>> * Fix bug #12296.
