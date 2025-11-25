ALTER TABLE `zt_task` CHANGE `status` `status` enum('wait','doing','done','pause','cancel','closed') NOT NULL DEFAULT 'wait' AFTER `deadline`;
