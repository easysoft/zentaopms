ALTER TABLE `zt_score` DROP `objectID`;
ALTER TABLE `zt_team` CHANGE `limitedUser` `limited` varchar(8) COLLATE 'utf8_general_ci' NOT NULL DEFAULT 'no' AFTER `role`;
