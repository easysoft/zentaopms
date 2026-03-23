CREATE INDEX `idx_object`  ON `zt_acl` (`objectType`, `objectID`, `account`);
CREATE INDEX `idx_account` ON `zt_acl` (`account`);

ALTER TABLE `zt_user` MODIFY `resetToken` char(32) NOT NULL DEFAULT '' COMMENT '重置密码的令牌';
ALTER TABLE `zt_user` ADD `resetExpired` int unsigned NOT NULL DEFAULT 0 COMMENT '重置密码令牌的过期时间戳' AFTER `resetToken`;

CREATE INDEX `idx_reset` ON `zt_user` (`resetToken`, `resetExpired`);
