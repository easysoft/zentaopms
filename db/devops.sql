-- DROP TABLE IF EXISTS `zt_ops_spaceuser`;
CREATE TABLE IF NOT EXISTS `zt_ops_spaceuser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `space` int unsigned NOT NULL DEFAULT 0 COMMENT '所属空间',
  `role` varchar(10) NOT NULL DEFAULT '' COMMENT '角色',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '用户帐号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='空间用户关联表';
CREATE UNIQUE INDEX `uk_spaceuser` ON `zt_ops_spaceuser` (`space`,`account`);

-- DROP TABLE IF EXISTS `zt_ops_repouser`;
CREATE TABLE IF NOT EXISTS `zt_ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '编号',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '所属代码库',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '用户帐号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='代码库用户关联表';
CREATE UNIQUE INDEX `uk_repouser` ON `zt_ops_repouser` (`repo`,`account`);

ALTER TABLE `zt_repo` ADD `space` int unsigned NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `zt_artifactrepo` ADD `space` int unsigned NOT NULL DEFAULT 0 AFTER `id`;
ALTER TABLE `zt_group` ADD `devopsSpace` int unsigned NOT NULL DEFAULT 0 AFTER `project`;

-- DROP TABLE IF EXISTS `zt_ops_branch_type`;
CREATE TABLE IF NOT EXISTS `zt_ops_branch_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `repo` int unsigned NOT NULL COMMENT '仓库ID，0为系统级别分支类型',
  `name` varchar(255) NOT NULL COMMENT '分支类型名称',
  `key` varchar(255) NOT NULL DEFAULT '' COMMENT '分支类型键值，仓库下唯一',
  `prefix` varchar(255) NOT NULL DEFAULT '' COMMENT '分支前缀，仓库下唯一',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑人',
  `editedDate` datetime DEFAULT NULL COMMENT '更新时间',
  `deleted` tinyint NOT NULL DEFAULT 0 COMMENT '删除标识，0-未删除，1-已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `uk_repo_branch_key` ON `zt_ops_branch_type` (`repo`, `key`);
CREATE UNIQUE INDEX `uk_repo_branch_prefix` ON `zt_ops_branch_type` (`repo`, `prefix`);

INSERT INTO `zt_ops_branch_type` (`id`, `repo`, `name`, `key`, `prefix`, `desc`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `deleted`) VALUES
(1, 0, '主干分支', 'main', 'main/,master/', '存放随时可以发布/部署的稳定代码。', 'system', NOW(), '', NULL, 0),
(2, 0, '开发分支', 'develop', 'develop/,dev/,story/', '日常开发的集成分支，完成的功能会合并到这里做集成测试。', 'system', NOW(), '', NULL, 0),
(3, 0, '特性分支', 'feature', 'feature/', '为实现某个新功能或任务而创建的短期分支。', 'system', NOW(), '', NULL, 0),
(4, 0, '预发布分支', 'release', 'release/', '用于准备一次正式发布的分支。', 'system', NOW(), '', NULL, 0),
(5, 0, '缺陷修复分支', 'bugfix', 'bugfix/,bug/', '用于修复非紧急的缺陷。', 'system', NOW(), '', NULL, 0),
(6, 0, '热修复分支', 'hotfix', 'hotfix/', '用于线上紧急修复。', 'system', NOW(), '', NULL, 0);

-- DROP TABLE IF EXISTS `zt_ops_branch_ruleset`;
CREATE TABLE IF NOT EXISTS `zt_ops_branch_ruleset` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `repo` int unsigned NOT NULL COMMENT '仓库ID',
  `branchType` int unsigned NOT NULL COMMENT '分支类型ID',
  `branchName` varchar(255) NOT NULL DEFAULT '' COMMENT '分支名',
  `createUser` varchar(500) NOT NULL COMMENT '创建分支权限人员',
  `deleteUser` varchar(500) NOT NULL COMMENT '删除分支权限人员',
  `updateUser` varchar(500) NOT NULL COMMENT '更新分支权限人员',
  `forcePushUser` varchar(500) NOT NULL COMMENT '强制推送权限人员',
  `sourceBranch` varchar(500) NOT NULL DEFAULT '' COMMENT '允许合并来源类型，为空为全部分支',
  `targetBranch` varchar(500) NOT NULL DEFAULT '' COMMENT '允许合并的目标分支类型，为空为全部分支',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑人',
  `editedDate` datetime DEFAULT NULL COMMENT '更新时间',
  `deleted` tinyint NOT NULL DEFAULT 0 COMMENT '删除标识，0-未删除，1-已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_repo` ON `zt_ops_branch_ruleset` (`repo`);

-- DROP TABLE IF EXISTS `zt_ops_review_flow`;
CREATE TABLE IF NOT EXISTS `zt_ops_review_flow` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `branchType` varchar(255) NOT NULL DEFAULT '' COMMENT '分支类型ID，0-全部分支类型，具体用逗号分隔',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '评审流程名称',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '评审流程描述',
  `definition` TEXT NOT NULL DEFAULT '' COMMENT '评审规则，json定义',
  `status` varchar(20) NOT NULL DEFAULT '' COMMENT 'enable-启用，disable-停用',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑人',
  `editedDate` datetime DEFAULT NULL COMMENT '更新时间',
  `deleted` tinyint NOT NULL DEFAULT 0 COMMENT '删除标识，0-未删除，1-已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_repo` ON `zt_ops_review_flow` (`repo`);

-- DROP TABLE IF EXISTS `zt_ops_request_reviewers`;
CREATE TABLE `zt_ops_request_reviewers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `requestID` int unsigned  NOT NULL DEFAULT 0 COMMENT '关联合并请求ID',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联仓库ID',
  `decision` varchar(255) NOT NULL DEFAULT '' COMMENT '最新审核决策（如：approve-批准、reject-拒绝、pending-待审核等）',
  `sha` varchar(40) NOT NULL DEFAULT '' COMMENT '审核对应的代码提交SHA校验值',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '评审人',
  `opinion` mediumtext DEFAULT NULL COMMENT '评审意见',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime NULL COMMENT '创建时间',
  `editedDate` datetime NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_requestID` ON `zt_ops_request_reviewers` (`requestID`);
CREATE INDEX `idx_account` ON `zt_ops_request_reviewers` (`account`);

DROP TABLE IF EXISTS `zt_mrapproval`;
DROP TABLE IF EXISTS `zt_mr`;
