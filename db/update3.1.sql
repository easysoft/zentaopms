ALTER TABLE  `zt_task` ADD  `estimateStartDate` DATETIME NOT NULL AFTER  `assignedDate` ,
ADD  `actualStartDate` DATETIME NOT NULL AFTER  `estimateStartDate`;
