ALTER TABLE `zt_job` ADD `sonarqubeServer` mediumint(8) unsigned NOT NULL AFTER `triggerType`;
ALTER TABLE `zt_job` ADD `projectKey` varchar(255) NOT NULL AFTER `sonarqubeServer`;
