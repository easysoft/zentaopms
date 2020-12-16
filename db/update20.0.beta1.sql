ALTER TABLE `zt_design` MODIFY `commit` text NOT NULL AFTER `product`;
ALTER TABLE `zt_design` ADD `commitedBy` varchar(30) NOT NULL AFTER `commit`;
