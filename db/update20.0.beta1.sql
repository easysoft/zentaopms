ALTER TABLE `zt_design` MODIFY `commit` text NOT NULL AFTER `product`;
ALTER TABLE `zt_design` ADD `commitedBy` varchar(30) NOT NULL AFTER `commit`;

DELETE FROM `zt_lang` WHERE `section` = 'URSRList' or `section` = 'URList' or `section` = 'SRList';
INSERT INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`) VALUES
('zh-cn', 'custom', 'URSRList', '1', '{\"SRName\":\"\\u8f6f\\u4ef6\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '1'),
('zh-cn', 'custom', 'URSRList', '2', '{\"SRName\":\"\\u8f6f\\u9700\",\"URName\":\"\\u7528\\u9700\"}', '1'),
('zh-cn', 'custom', 'URSRList', '3', '{\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u9700\\u6c42\"}', '1'),
('zh-cn', 'custom', 'URSRList', '4', '{\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u53f2\\u8bd7\"}', '1');

DELETE FROM `zt_config` WHERE `key` = 'URSRName' or `key` = 'URAndSR';
REPLACE INTO `zt_config` (`owner`, `module`, `section`, `key`, `value`) VALUES ('system', 'custom', '', 'URSR', '1');

UPDATE `zt_block` SET title = '指派给我', source = '', block = 'assigntome', params='{"todoNum":"20","taskNum":"20","bugNum":"20","riskNum":"20","issueNum":"20","storyNum":"20"}' WHERE module = 'my' AND block = 'task';
