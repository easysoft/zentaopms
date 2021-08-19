ALTER TABLE `zt_testtask` ADD `realFinishedDate` datetime NOT NULL AFTER `end`;
ALTER TABLE `zt_doc` ADD `draft` longtext NOT NULL AFTER `views`;
ALTER TABLE `zt_release` ADD `mailto` text AFTER `desc`;
ALTER TABLE `zt_release` ADD `notify` varchar(255) AFTER `mailto`;
