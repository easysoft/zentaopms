ALTER TABLE `zt_case` CHANGE `order` `order` mediumint(8) unsigned NOT NULL DEFAULT '0';

CREATE INDEX `parent` ON `zt_kanbancolumn`(`parent`);
CREATE INDEX `group` ON `zt_kanbancolumn`(`group`);
CREATE INDEX `group` ON `zt_kanbanlane`(`group`);
