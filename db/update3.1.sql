ALTER TABLE  `zt_task` ADD  `estStarted` DATE NOT NULL AFTER  `assignedDate` ,
ADD  `realStarted` DATE NOT NULL AFTER  `estStarted`;
