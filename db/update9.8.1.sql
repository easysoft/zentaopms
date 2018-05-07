<<<<<<< HEAD
ALTER TABLE `zt_release` ADD `marker` enum('0','1') NOT NULL default '0' AFTER `name`;
ALTER TABLE `zt_doc` ADD `collector` text NOT NULL AFTER `views`;
ALTER TABLE `zt_doc` ADD `visitedDate` datetime NOT NULL AFTER `editedDate`;
ALTER TABLE `zt_doclib` ADD `collector` text NOT NULL AFTER `main`;
ALTER TABLE `zt_module` ADD `collector` text NOT NULL AFTER `owner`;
=======
ALTER TABLE `zt_user` CHANGE `realname` `realname` varchar(100) COLLATE 'utf8_general_ci' NOT NULL DEFAULT '' AFTER `password`;
ALTER TABLE `zt_project` CHANGE `openedDate` `openedDate` datetime NOT NULL,
CHANGE `closedDate` `closedDate` datetime NOT NULL,
CHANGE `canceledDate` `canceledDate` datetime NOT NULL;
>>>>>>> f6289cc53c13786e3242f8792030e7752898794f
