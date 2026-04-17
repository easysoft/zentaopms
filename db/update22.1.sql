ALTER TABLE `zt_projectdeliverable` ADD COLUMN `submittedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '提交人' AFTER `createdDate`;
ALTER TABLE `zt_projectdeliverable` ADD COLUMN `submittedDate` datetime DEFAULT NULL COMMENT '提交时间' AFTER `submittedBy`;
