truncate `zt_lang`;
REPLACE INTO `zt_lang` (`lang`, `module`, `section`, `key`, `value`, `system`) VALUES
('zh-cn', 'custom', 'URSRList', '1', '{\"SRName\":\"\\u8f6f\\u4ef6\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '1'),('zh-cn', 'custom', 'URSRList', '2', '{\"SRName\":\"\\u7814\\u53d1\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '1'),
('zh-cn', 'custom', 'URSRList', '3', '{\"SRName\":\"\\u8f6f\\u9700\",\"URName\":\"\\u7528\\u9700\"}', '1'),('zh-cn', 'custom', 'URSRList', '4', '{\"SRName\":\"\\u6545\\u4e8b\",\"URName\":\"\\u53f2\\u8bd7\"}', '1'),
('zh-cn', 'custom', 'URSRList', '5', '{\"SRName\":\"\\u9700\\u6c42\",\"URName\":\"\\u7528\\u6237\\u9700\\u6c42\"}', '1'),
('en', 'custom', 'URSRList', '1', '{\"SRName\":\"Story\",\"URName\":\"Epic\"}', '0'),
('en', 'custom', 'URSRList', '2', '{\"SRName\":\"Software Requirement\",\"URName\":\"User Requirement\"}', '0'),
('all','stage','typeList','request','需求', '1'),
('all','stage','typeList','design','设计', '1'),
('all','stage','typeList','dev','开发', '1'),
('all','stage','typeList','qa','测试', '1'),
('all','stage','typeList','release','发布', '1'),
('all','stage','typeList','review','总结评审','1'),
('all','stage','typeList','other','其他','1');
