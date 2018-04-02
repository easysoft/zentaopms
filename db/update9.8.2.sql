ALTER TABLE `zt_team` DROP PRIMARY KEY;
ALTER TABLE `zt_team` ADD PRIMARY KEY (`root`, `type`, `account`);
ALTER TABLE `zt_project` CHANGE `team` `team` varchar(90) NOT NULL;
