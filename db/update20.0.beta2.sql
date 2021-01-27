ALTER TABLE `zt_risk` add `closedBy` char(30) AFTER `assignedTo`;
ALTER TABLE `zt_risk` add `closedDate` date AFTER `closedBy`;
ALTER TABLE `zt_repo` CHANGE `PRJ` `product` varchar(255) NOT NULL AFTER `id`;
ALTER TABLE `zt_project` CHANGE `budgetUnit` `budgetUnit` char(30) NOT NULL  DEFAULT 'wanyuan' AFTER `budget`;
