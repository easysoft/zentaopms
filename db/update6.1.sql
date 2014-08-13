ALTER TABLE `zt_task` CHANGE `status` `status` enum('wait','doing','done','pause','cancel','closed') COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'wait' AFTER `deadline`;
