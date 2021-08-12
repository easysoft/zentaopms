ALTER TABLE `zt_testtask` ADD `realFinishedDate` datetime NOT NULL AFTER `end`;
ALTER TABLE `zt_doc` ADD `tempContent` longtext NOT NULL AFTER `views`;
