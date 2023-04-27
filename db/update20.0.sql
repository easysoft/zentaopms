ALTER TABLE `zt_block` ADD `dashboard` varchar(20) NOT NULL DEFAULT '' AFTER `account`;
UPDATE `zt_block` SET `dashboard` = `module`;
