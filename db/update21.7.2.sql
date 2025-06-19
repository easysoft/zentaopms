ALTER TABLE `zt_project` ADD `tplAcl` char(30) NOT NULL DEFAULT 'open' AFTER `whitelist`;
ALTER TABLE `zt_project` ADD `tplWhiteList` text NULL AFTER `tplAcl`;
