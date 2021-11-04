ALTER TABLE `zt_branch` ADD `default` enum ('0', '1') NOT NULL DEFAULT '0' AFTER `name`;
ALTER TABLE `zt_branch` ADD `status` enum ('active', 'closed') NOT NULL DEFAULT 'active' AFTER `default`;
ALTER TABLE `zt_branch` ADD `desc` varchar(255) NOT NULL AFTER `status`;
ALTER TABLE `zt_branch` ADD `createdDate` date NOT NULL AFTER `desc`;
ALTER TABLE `zt_branch` ADD `closedDate` date NOT NULL AFTER `createdDate`;
ALTER TABLE `zt_projectproduct` ADD PRIMARY KEY `project_product_branch` (`project`, `product`, `branch`), DROP INDEX `PRIMARY`;
