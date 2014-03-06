ALTER TABLE `zt_project` DROP `goal`;
ALTER TABLE `zt_build` ADD `packageType` varchar(10) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'path' AFTER `scmPath`;
