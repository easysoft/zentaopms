ALTER TABLE `zt_kanban` MODIFY COLUMN `archived` enum('0','1') NOT NULL DEFAULT '1';
ALTER TABLE `zt_bug` add `issueKey` varchar(50) NOT NULL DEFAULT '' AFTER `repoType`;
