ALTER TABLE zt_productplan ADD `status` enum('wait','doing','done','closed') NOT NULL default 'wait' AFTER `title`;
ALTER TABLE zt_product modify column `reviewer` text NOT NULL;
