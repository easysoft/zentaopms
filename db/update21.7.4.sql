ALTER TABLE `zt_case` CHANGE `order` `order` mediumint(8) unsigned NOT NULL DEFAULT '0';

CREATE INDEX `parent` ON `zt_kanbancolumn`(`parent`);
CREATE INDEX `group` ON `zt_kanbancolumn`(`group`);
CREATE INDEX `execution` ON `zt_kanbanlane`(`execution`);
CREATE INDEX `group` ON `zt_kanbanlane`(`group`);
CREATE INDEX `lane` ON `zt_kanbancell`(`lane`);
CREATE INDEX `feedback` ON `zt_bug`(`feedback`);
CREATE INDEX `feedback` ON `zt_story`(`feedback`);
CREATE INDEX `feedback` ON `zt_task`(`feedback`);
CREATE INDEX `feedback` ON `zt_ticket`(`feedback`);
CREATE INDEX `feedback` ON `zt_todo`(`feedback`);
