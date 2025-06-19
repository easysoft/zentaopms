ALTER TABLE `zt_project` ADD `templateAcl` char(30) NOT NULL DEFAULT 'open' AFTER `whitelist`;
ALTER TABLE `zt_project` ADD `templateWhite` text NULL AFTER `templateAcl`;
