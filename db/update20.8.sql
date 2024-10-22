ALTER TABLE zt_opportunity ADD `desc` mediumtext NULL AFTER `from`;
ALTER TABLE zt_taskteam MODIFY `status` enum('wait','doing','done','cancel','closed') NOT NULL DEFAULT 'wait';
