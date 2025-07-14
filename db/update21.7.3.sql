ALTER TABLE `zt_doc` ADD `cycle` char(10) COLLATE 'utf8mb4_general_ci' NOT NULL AFTER `chapterType`;
ALTER TABLE `zt_doc` ADD `objects` text COLLATE 'utf8mb4_general_ci' NULL AFTER `templateDesc`;
ALTER TABLE `zt_doc` ADD `isReport` enum('0', '1') COLLATE 'utf8mb4_general_ci' NOT NULL DEFAULT '0' AFTER `templateDesc`;
