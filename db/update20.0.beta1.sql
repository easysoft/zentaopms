ALTER TABLE `zt_design` MODIFY `commit` text NOT NULL AFTER `product`;
ALTER TABLE `zt_design` ADD `commitedBy` varchar(30) NOT NULL AFTER `commit`;

DELETE FROM `zt_lang` WHERE `section` = 'URSRList' or `section` = 'URList' or `section` = 'SRList';
INSERT INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`) VALUES
('zh-cn', 'custom', 'URSRList', '1', '{\"SRName\":\"\\u8f6f\\u4ef6\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '1'),
('zh-cn', 'custom', 'URSRList', '2', '{\"SRName\":\"\\u8f6f\\u9700\",\"URName\":\"\\u7528\\u9700\"}', '1'),
('zh-cn', 'custom', 'URSRList', '3', '{\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u9700\\u6c42\"}', '1'),
('zh-cn', 'custom', 'URSRList', '4', '{\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u53f2\\u8bd7\"}', '1');
