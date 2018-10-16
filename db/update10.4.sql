ALTER TABLE `zt_userview` CHANGE `products` `products` mediumtext COLLATE 'utf8_general_ci' NOT NULL AFTER `account`,
CHANGE `projects` `projects` mediumtext COLLATE 'utf8_general_ci' NOT NULL AFTER `products`;
ALTER TABLE `zt_task` ADD `finishedList` text NOT NULL AFTER `finishedDate`;
