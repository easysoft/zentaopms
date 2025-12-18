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
  `createdDate` datetime NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑人',
  `editedDate` datetime NULL COMMENT '更新时间',
  `deleted` tinyint NOT NULL DEFAULT 0 COMMENT '删除标识，0-未删除，1-已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `uk_repo_branch_key` ON `zt_ops_branch_type` (`repo`, `key`);
CREATE UNIQUE INDEX `uk_repo_branch_prefix` ON `zt_ops_branch_type` (`repo`, `prefix`);

-- DROP TABLE IF EXISTS `zt_ops_branch_ruleset`;
CREATE TABLE IF NOT EXISTS `zt_ops_branch_ruleset` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `repo` int unsigned NOT NULL COMMENT '仓库ID',
  `branchType` int unsigned NOT NULL COMMENT '分支类型ID',
  `branchName` varchar(255) NOT NULL DEFAULT '' COMMENT '分支名',
  `deleteUser` varchar(500) NOT NULL COMMENT '删除分支权限人员',
  `updateUser` varchar(500) NOT NULL COMMENT '更新分支权限人员',
  `forcePushUser` varchar(500) NOT NULL COMMENT '强制推送权限人员',
  `sourceBranch` varchar(500) NOT NULL DEFAULT '' COMMENT '允许合并来源类型，为空为全部分支',
  `targetBranch` varchar(500) NOT NULL DEFAULT '' COMMENT '允许合并的目标分支类型，为空为全部分支',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑人',
  `editedDate` datetime NULL COMMENT '更新时间',
  `deleted` tinyint NOT NULL DEFAULT 0 COMMENT '删除标识，0-未删除，1-已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_repo` ON `zt_ops_branch_ruleset` (`repo`);

-- DROP TABLE IF EXISTS `zt_ops_review_flow`;
CREATE TABLE IF NOT EXISTS `zt_ops_review_flow` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `branchType` varchar(255) NOT NULL DEFAULT '' COMMENT '分支类型ID，0-全部分支类型，具体用逗号分隔',
  `name`  varchar(255) NOT NULL DEFAULT '' COMMENT '评审流程名称',
  `desc`  varchar(500) NOT NULL DEFAULT '' COMMENT '评审流程描述',
  `definition` TEXT NOT NULL DEFAULT '' COMMENT '评审规则，json定义',
  `status` varchar(20) NOT NULL DEFAULT '' COMMENT 'enable-启用，disable-停用',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '创建人',
  `createdDate` datetime NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '编辑人',
  `editedDate` datetime NULL COMMENT '更新时间',
  `deleted` tinyint NOT NULL DEFAULT 0 COMMENT '删除标识，0-未删除，1-已删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_repo` ON `zt_ops_review_flow` (`repo`);
