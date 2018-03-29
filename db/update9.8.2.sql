ALTER TABLE `zt_team` DROP PRIMARY KEY;
ALTER TABLE `zt_team` ADD PRIMARY KEY (`root`, `type`, `account`);
