-- 20100226 add start and end date field to task.
ALTER TABLE `zt_task` ADD `planStart` DATE NOT NULL AFTER `left` ,
ADD `planEnd` DATE NOT NULL AFTER `planStart` ,
ADD `realStart` DATE NOT NULL AFTER `planEnd` ,
ADD `realEnd` DATE NOT NULL AFTER `realStart`
