ALTER TABLE `zt_team` DROP PRIMARY KEY,
ADD PRIMARY KEY (`root`,`type`,`account`);
