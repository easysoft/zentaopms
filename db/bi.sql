ALTER TABLE `zt_chart` ADD `stage` enum('draft','published') NOT NULL DEFAULT 'draft' AFTER `group`;
