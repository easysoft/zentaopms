-- DROP TABLE IF EXISTS `ops_artifact_assets`;
CREATE TABLE IF NOT EXISTS `ops_artifact_assets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `artifactLibID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属制品库ID',
  `groupID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属分组ID，0表示无分组',
  `packageID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属制品包ID',
  `versionID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属版本ID，0表示无版本关联',
  `blobID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联文件对象ID',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '制品类型:file/container/raw/helm/pypi/npm/maven/composer',
  `basename` varchar(255) NOT NULL DEFAULT '' COMMENT '制品名称',
  `contentType` varchar(100) NOT NULL DEFAULT '' COMMENT '内容类型',
  `rank` varchar(50) NOT NULL DEFAULT '' COMMENT '制品等级 main/sub',
  `checksum` varchar(500) NOT NULL DEFAULT '' COMMENT '校验摘要',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='制品资源表';
CREATE INDEX `idx_artifactLibID_type_basename_deleted` ON `ops_artifact_assets` (`artifactLibID`, `type`, `basename`, `deleted`);
CREATE INDEX `idx_blobID` ON `ops_artifact_assets` (`blobID`);
CREATE INDEX `idx_deleted` ON `ops_artifact_assets` (`deleted`);
CREATE INDEX `idx_groupID_basename_deleted` ON `ops_artifact_assets` (`groupID`, `basename`, `deleted`);
CREATE INDEX `idx_versionID_basename_deleted` ON `ops_artifact_assets` (`versionID`, `basename`, `deleted`);

-- DROP TABLE IF EXISTS `ops_artifact_blobs`;
CREATE TABLE IF NOT EXISTS `ops_artifact_blobs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `storageID` int unsigned NOT NULL DEFAULT 0 COMMENT '存储后端ID',
  `assetID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联制品资源ID',
  `ref` varchar(255) NOT NULL DEFAULT '' COMMENT '存储引用',
  `size` bigint unsigned NOT NULL DEFAULT 0 COMMENT '文件大小',
  `downloads` int unsigned NOT NULL DEFAULT 0 COMMENT '下载次数',
  `metadata` varchar(500) NOT NULL DEFAULT '' COMMENT '文件元数据',
  `checksum` varchar(500) NOT NULL DEFAULT '' COMMENT '校验摘要',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='制品文件对象表';
CREATE UNIQUE INDEX `uk_ref_storageID` ON `ops_artifact_blobs` (`ref`, `storageID`);
CREATE INDEX `idx_assetID_deleted` ON `ops_artifact_blobs` (`assetID`, `deleted`);
CREATE INDEX `idx_deleted` ON `ops_artifact_blobs` (`deleted`);

-- DROP TABLE IF EXISTS `ops_artifact_groups`;
CREATE TABLE IF NOT EXISTS `ops_artifact_groups` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `artifactLibID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属制品库ID',
  `parentID` int unsigned NOT NULL DEFAULT 0 COMMENT '父级分组ID，0表示根分组',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分组名称',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '制品类型:file/container/raw/helm/pypi/npm/maven/composer',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='制品分组表';
CREATE INDEX `idx_artifactLibID_deleted` ON `ops_artifact_groups` (`artifactLibID`, `deleted`);
CREATE INDEX `idx_artifactLibID_parentID_deleted` ON `ops_artifact_groups` (`artifactLibID`, `parentID`, `deleted`);
CREATE INDEX `idx_parentID_deleted` ON `ops_artifact_groups` (`parentID`, `deleted`);
CREATE INDEX `idx_artifactLibID_parentID_name` ON `ops_artifact_groups` (`artifactLibID`, `parentID`, `name`);

-- DROP TABLE IF EXISTS `ops_artifact_libs`;
CREATE TABLE IF NOT EXISTS `ops_artifact_libs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `spaceID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属空间ID，0表示全局级',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属代码库ID，0表示非代码库级',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '制品库名称',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '制品库描述',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '制品类型:file/container/raw/helm/pypi/npm/maven/composer',
  `scope` varchar(30) NOT NULL DEFAULT '' COMMENT '制品库类型:global/space/repo',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='制品库主表（支持全局/空间/代码库三级作用域）';
CREATE INDEX `idx_spaceID_repoID_name` ON `ops_artifact_libs` (`spaceID`, `repoID`, `name`);
CREATE INDEX `idx_spaceID_deleted` ON `ops_artifact_libs` (`spaceID`, `deleted`);
CREATE INDEX `idx_repoID_deleted` ON `ops_artifact_libs` (`repoID`, `deleted`);
CREATE INDEX `idx_spaceID_repoID_deleted` ON `ops_artifact_libs` (`spaceID`, `repoID`, `deleted`);

-- DROP TABLE IF EXISTS `ops_artifact_meta_assets`;
CREATE TABLE IF NOT EXISTS `ops_artifact_meta_assets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `artifactLibID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属制品库ID',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '制品类型:file/container/raw/helm/pypi/npm/maven/composer',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '元数据路径',
  `contentType` varchar(100) NOT NULL DEFAULT '' COMMENT '内容类型',
  `rank` varchar(50) NOT NULL DEFAULT '' COMMENT '制品等级 main/sub',
  `blobID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联文件对象ID',
  `checkSum` varchar(500) NOT NULL DEFAULT '' COMMENT '校验和',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='制品元数据资源表';
CREATE UNIQUE INDEX `uk_artifactLibID_type_path` ON `ops_artifact_meta_assets` (`artifactLibID`, `type`, `path`);
CREATE INDEX `idx_artifactLibID_deleted` ON `ops_artifact_meta_assets` (`artifactLibID`, `deleted`);
CREATE INDEX `idx_blobID` ON `ops_artifact_meta_assets` (`blobID`);
CREATE INDEX `idx_deleted` ON `ops_artifact_meta_assets` (`deleted`);

-- DROP TABLE IF EXISTS `ops_artifact_packages`;
CREATE TABLE IF NOT EXISTS `ops_artifact_packages` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `artifactLibID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属制品库ID',
  `groupID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属分组ID',
  `namespace` varchar(255) NOT NULL DEFAULT '' COMMENT '命名空间',
  `name` varchar(100) NOT NULL DEFAULT '' COMMENT '制品包名称',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '制品类型:file/container/raw/helm/pypi/npm/maven/composer',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='制品包表';
CREATE UNIQUE INDEX `uk_artifactLibID_name_namespace_type` ON `ops_artifact_packages` (`artifactLibID`, `name`, `namespace`, `type`);
CREATE INDEX `idx_artifactLibID_type_deleted` ON `ops_artifact_packages` (`artifactLibID`, `type`, `deleted`);
CREATE INDEX `idx_artifactLibID_namespace_deleted` ON `ops_artifact_packages` (`artifactLibID`, `namespace`, `deleted`);
CREATE INDEX `idx_groupID_deleted` ON `ops_artifact_packages` (`groupID`, `deleted`);
CREATE INDEX `idx_deleted` ON `ops_artifact_packages` (`deleted`);

-- DROP TABLE IF EXISTS `ops_artifact_tree_nodes`;
CREATE TABLE IF NOT EXISTS `ops_artifact_tree_nodes` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `artifactLibID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属制品库ID',
  `parentID` int unsigned NOT NULL DEFAULT 0 COMMENT '父级节点ID，0表示根节点',
  `nodeName` varchar(255) NOT NULL DEFAULT '' COMMENT '节点名称',
  `nodePath` varchar(255) NOT NULL DEFAULT '' COMMENT '节点路径',
  `linkTable` varchar(20) NOT NULL DEFAULT '' COMMENT '关联表',
  `linkRecord` int unsigned NOT NULL DEFAULT 0 COMMENT '关联表ID',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '制品类型:file/container/raw/helm/pypi/npm/maven/composer',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='制品树节点表';
CREATE UNIQUE INDEX `uk_artifactLibID_nodePath_type` ON `ops_artifact_tree_nodes` (`artifactLibID`, `nodePath`, `type`);
CREATE INDEX `idx_parentID_deleted` ON `ops_artifact_tree_nodes` (`parentID`, `deleted`);
CREATE INDEX `idx_artifactLibID_linkTable_linkRecord_deleted` ON `ops_artifact_tree_nodes` (`artifactLibID`, `linkTable`, `linkRecord`, `deleted`);
CREATE INDEX `idx_artifactLibID_type_deleted` ON `ops_artifact_tree_nodes` (`artifactLibID`, `type`, `deleted`);
CREATE INDEX `idx_deleted` ON `ops_artifact_tree_nodes` (`deleted`);

-- DROP TABLE IF EXISTS `ops_artifact_versions`;
CREATE TABLE IF NOT EXISTS `ops_artifact_versions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `artifactLibID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属制品库ID',
  `packageID` int unsigned NOT NULL DEFAULT 0 COMMENT '所属制品包ID',
  `version` varchar(100) NOT NULL DEFAULT '' COMMENT '版本号',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='制品版本表';
CREATE UNIQUE INDEX `uk_artifactLibID_packageID_version` ON `ops_artifact_versions` (`artifactLibID`, `packageID`, `version`);
CREATE INDEX `idx_artifactLibID_deleted` ON `ops_artifact_versions` (`artifactLibID`, `deleted`);
CREATE INDEX `idx_packageID_deleted` ON `ops_artifact_versions` (`packageID`, `deleted`);
CREATE INDEX `idx_createdDate` ON `ops_artifact_versions` (`createdDate`);
CREATE INDEX `idx_editedDate` ON `ops_artifact_versions` (`editedDate`);
CREATE INDEX `idx_deleted` ON `ops_artifact_versions` (`deleted`);

-- DROP TABLE IF EXISTS `ops_branch_ruleset`;
CREATE TABLE IF NOT EXISTS `ops_branch_ruleset` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `branchType` int unsigned NOT NULL DEFAULT 0 COMMENT '分支类型ID',
  `branchName` varchar(255) NOT NULL DEFAULT '' COMMENT '分支名',
  `createUser` varchar(500) NOT NULL DEFAULT '' COMMENT '创建分支权限人员',
  `deleteUser` varchar(500) NOT NULL DEFAULT '' COMMENT '删除分支权限人员',
  `updateUser` varchar(500) NOT NULL DEFAULT '' COMMENT '更新分支权限人员',
  `forcePushUser` varchar(500) NOT NULL DEFAULT '' COMMENT '强制推送权限人员',
  `ppmCreateUser` varchar(500) NOT NULL DEFAULT '' COMMENT '创建推送合并请求人员',
  `ppmHandleUser` varchar(500) NOT NULL DEFAULT '' COMMENT '处理推送合并请求人员',
  `sourceBranch` varchar(500) NOT NULL DEFAULT '' COMMENT '允许合并来源类型，为空为全部分支',
  `targetBranch` varchar(500) NOT NULL DEFAULT '' COMMENT '允许合并的目标分支类型，为空为全部分支',
  `commitLine` smallint unsigned NOT NULL DEFAULT 0 COMMENT 'commit限制行数',
  `pushLine` smallint unsigned NOT NULL DEFAULT 0 COMMENT 'push行数限制行数',
  `forceReview` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否开启强制评审',
  `reviewFlowID` int unsigned NOT NULL DEFAULT 0 COMMENT '评审流程ID',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='分支规则表';
CREATE INDEX `idx_repo` ON `ops_branch_ruleset` (`repo`);

-- DROP TABLE IF EXISTS `ops_branch_type`;
CREATE TABLE IF NOT EXISTS `ops_branch_type` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID，0为系统级别分支类型',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '分支类型名称',
  `key` varchar(255) NOT NULL DEFAULT '' COMMENT '分支类型键值，仓库下唯一',
  `prefix` varchar(255) NOT NULL DEFAULT '' COMMENT '分支前缀，仓库下唯一',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='分支类型表';
CREATE UNIQUE INDEX `uk_repo_key` ON `ops_branch_type` (`repo`, `key`);
CREATE UNIQUE INDEX `uk_repo_prefix` ON `ops_branch_type` (`repo`, `prefix`);

-- DROP TABLE IF EXISTS `ops_migrations`;
CREATE TABLE IF NOT EXISTS `ops_migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `version` text DEFAULT NULL COMMENT '版本',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- DROP TABLE IF EXISTS `ops_pipeline`;
CREATE TABLE IF NOT EXISTS `ops_pipeline` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '流水线ID，自增主键',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '流水线名称',
  `engine` varchar(30) NOT NULL DEFAULT 'gitfox' COMMENT '引擎: gitlab, jenkins, gitfox',
  `providerID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的外部服务ID',
  `scope` varchar(30) NOT NULL DEFAULT '' COMMENT '流水线类型：系统/空间/仓库',
  `spaceID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的空间ID',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的代码仓库ID',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '流水线描述信息',
  `status` varchar(30) NOT NULL DEFAULT '' COMMENT '流水线状态：草稿/激活',
  `latestVersion` int unsigned NOT NULL DEFAULT 0 COMMENT '流水线最新版本',
  `defaultBranch` varchar(255) NOT NULL DEFAULT '' COMMENT '流水线默认分支',
  `yamlPath` varchar(255) NOT NULL DEFAULT '' COMMENT '流水线配置文件路径',
  `customParam` text DEFAULT NULL COMMENT 'Jenkins,GitLab 流水线执行传参',
  `lastExec` datetime DEFAULT NULL COMMENT '最后一次执行时间',
  `lastResult` varchar(30) NOT NULL DEFAULT '' COMMENT '最后一次执行状态',
  `externalPipeline` varchar(128) NOT NULL DEFAULT '' COMMENT 'Jenkins/GitLab pipeline',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='流水线基本信息表';
CREATE UNIQUE INDEX `uk_spaceID_repoID_name` ON `ops_pipeline` (`spaceID`, `repoID`, `name`);

-- DROP TABLE IF EXISTS `ops_pipeline_content`;
CREATE TABLE IF NOT EXISTS `ops_pipeline_content` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `pipelineID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的流水线ID',
  `version` int unsigned NOT NULL DEFAULT 0 COMMENT '流水线版本',
  `data` longtext DEFAULT NULL COMMENT '流水线内容',
  `variables` text DEFAULT NULL COMMENT '流水线参数',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='流水线配置表';
CREATE UNIQUE INDEX `uk_pipelineID_version` ON `ops_pipeline_content` (`pipelineID`, `version`);

-- DROP TABLE IF EXISTS `ops_pipeline_executions`;
CREATE TABLE IF NOT EXISTS `ops_pipeline_executions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '执行记录ID，自增主键',
  `pipelineID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的流水线ID',
  `parent` int unsigned NOT NULL DEFAULT 0 COMMENT '父执行记录ID（用于子流水线/重试场景，无则为0）',
  `trigger` varchar(50) NOT NULL DEFAULT '' COMMENT '触发方式：manual,branch_updated',
  `commit` char(40) NOT NULL DEFAULT '' COMMENT 'CommitID',
  `ref` varchar(255) NOT NULL DEFAULT '' COMMENT '分支/tag/commit，如refs/heads/main,refs/tags/v1.0,commit',
  `params` text DEFAULT NULL COMMENT '执行入参（JSON格式）',
  `startedDate` datetime DEFAULT NULL COMMENT '执行开始时间',
  `finishedDate` datetime DEFAULT NULL COMMENT '执行结束时间',
  `duration` int unsigned NOT NULL DEFAULT 0 COMMENT '执行时长(s)',
  `status` varchar(50) NOT NULL DEFAULT '' COMMENT '执行状态',
  `error` varchar(500) NOT NULL DEFAULT '' COMMENT '执行错误信息（无错误则为空字符串）',
  `queue` int unsigned NOT NULL DEFAULT 0 COMMENT 'Jenkins队列号，用于后续状态查询和去重',
  `logs` longtext DEFAULT NULL COMMENT 'Jenkins,GitLab的流水线日志，支持 longtext（最大 4GB）',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `commitBefore` varchar(255) NOT NULL DEFAULT '' COMMENT '当前流水线执行时所用提交的前一个提交的SHA值',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='流水线执行记录表';
CREATE INDEX `idx_createdBy` ON `ops_pipeline_executions` (`createdBy`);
CREATE INDEX `idx_status` ON `ops_pipeline_executions` (`status`);

-- DROP TABLE IF EXISTS `ops_plugin_group`;
CREATE TABLE IF NOT EXISTS `ops_plugin_group` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '插件分组ID，自增主键',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '插件分组名称',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '插件分组描述信息',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='插件分组基本信息表';

-- DROP TABLE IF EXISTS `ops_plugins`;
CREATE TABLE IF NOT EXISTS `ops_plugins` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '插件ID，自增主键',
  `groupID` int unsigned NOT NULL DEFAULT 0 COMMENT '插件分组ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '插件名称（唯一标识符）',
  `desc` mediumtext DEFAULT NULL COMMENT '插件描述信息',
  `logo` mediumtext DEFAULT NULL COMMENT '插件Logo（SVG格式）',
  `yaml` longtext DEFAULT NULL COMMENT '插件规范配置（YAML格式）',
  `json` longtext DEFAULT NULL COMMENT '插件规范配置（JSON格式）',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '插件类型：step/stage',
  `kind` varchar(255) NOT NULL DEFAULT '' COMMENT '插件 kind',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='CI插件表';
CREATE UNIQUE INDEX `uk_name` ON `ops_plugins` (`name`);
CREATE INDEX `idx_type` ON `ops_plugins` (`type`);

-- DROP TABLE IF EXISTS `ops_ppm`;
CREATE TABLE IF NOT EXISTS `ops_ppm` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `title` varchar(255) NOT NULL DEFAULT '' COMMENT 'PR 标题',
  `desc` text DEFAULT NULL COMMENT '拉取请求详细描述',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联zt_repo表的id',
  `sourceRepoID` int unsigned NOT NULL DEFAULT 0 COMMENT '源仓库ID，关联repository表的id',
  `sourceBranch` varchar(255) NOT NULL DEFAULT '' COMMENT '源分支名称（待合并的分支）',
  `sourceSHA` char(40) NOT NULL DEFAULT '' COMMENT '源分支最新提交的SHA-1值',
  `targetRepoID` int unsigned NOT NULL DEFAULT 0 COMMENT '目标仓库ID，关联repository表的id',
  `targetBranch` varchar(255) NOT NULL DEFAULT '' COMMENT '目标分支名称（合并目标分支）',
  `mergedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '执行合并操作的用户（未合并则为NULL）',
  `mergeMethod` varchar(255) NOT NULL DEFAULT '' COMMENT '合并方法:merge/squash/rebase/fast-forward',
  `mergeTargetSHA` char(40) NOT NULL DEFAULT '' COMMENT '合并时目标分支的 SHA,用于验证目标分支未变更',
  `mergeBaseSHA` char(40) NOT NULL DEFAULT '' COMMENT '合并基准 SHA(源分支和目标分支的共同祖先)',
  `mergeSHA` char(40) NOT NULL DEFAULT '' COMMENT '合并后生成的 commit SHA',
  `mergeCheckStatus` varchar(255) NOT NULL DEFAULT '' COMMENT '合并前检查状态unchecked=未检查，conflict=有冲突，mergeable=无冲突）',
  `mergeConflicts` text DEFAULT NULL COMMENT 'merge/squash 冲突文件列表,换行符分隔',
  `rebaseCheckStatus` varchar(255) NOT NULL DEFAULT 'unchecked' COMMENT '变基检查状态（unchecked=未检查，conflict=有冲突，mergeable=无冲突）',
  `rebaseConflicts` text DEFAULT NULL COMMENT '变基时产生的冲突详情（文件路径、冲突内容，未变基/无冲突则为NULL）',
  `commitCount` int unsigned NOT NULL DEFAULT 0 COMMENT 'PR 包含的 commit 数量',
  `fileCount` int unsigned NOT NULL DEFAULT 0 COMMENT 'PR 修改的文件数量',
  `additions` int unsigned NOT NULL DEFAULT 0 COMMENT '新增的代码行数',
  `deletions` int unsigned NOT NULL DEFAULT 0 COMMENT '删除的代码行数',
  `flow` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '拉取请求对应的流程标识（0=默认流程，其他值对应自定义流程）',
  `reviewFlowID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的评审流程ID',
  `executionID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的禅道执行ID',
  `status` varchar(255) NOT NULL DEFAULT '' COMMENT '拉取请求状态（如open/closed/merged/draft等）',
  `mergedDate` datetime DEFAULT NULL COMMENT '合并时间',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `approval` int unsigned NOT NULL DEFAULT 0 COMMENT '审批实例',
  `reviewers` varchar(255) NOT NULL DEFAULT '' COMMENT '最新节点评审人',
  `approvalflow` int unsigned NOT NULL DEFAULT 0 COMMENT '审批流模板',
  `reviewStatus` varchar(20) NOT NULL DEFAULT '' COMMENT '审批状态',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='合并请求主表';
CREATE INDEX `idx_createdBy` ON `ops_ppm` (`createdBy`);
CREATE INDEX `idx_sourceRepoID_sourceBranch_targetRepoID_targetBranch` ON `ops_ppm` (`sourceRepoID`, `sourceBranch`, `targetRepoID`, `targetBranch`);

-- DROP TABLE IF EXISTS `ops_provider`;
CREATE TABLE IF NOT EXISTS `ops_provider` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type` varchar(30) NOT NULL DEFAULT '' COMMENT 'GitLab,Gitea,Gogs,Subversion,GitHub,Jenkins',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '外部服务名称',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT '外部服务地址',
  `token` varchar(255) NOT NULL DEFAULT '' COMMENT '外部服务调用Token，如果 type=Jenkins 则存入 base64_encode(username:token)',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='外部服务表';

-- DROP TABLE IF EXISTS `ops_public_keys`;
CREATE TABLE IF NOT EXISTS `ops_public_keys` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '关联用户账号',
  `identifier` varchar(255) NOT NULL DEFAULT '' COMMENT '公钥唯一标识',
  `verifiedDate` datetime DEFAULT NULL COMMENT '公钥验证时间',
  `fingerprint` varchar(255) NOT NULL DEFAULT '' COMMENT '公钥指纹',
  `content` text DEFAULT NULL COMMENT '公钥原始内容',
  `comment` varchar(500) NOT NULL DEFAULT '' COMMENT '公钥注释部分',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '公钥算法类型',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='SSH公钥表';
CREATE UNIQUE INDEX `uk_fingerprint` ON `ops_public_keys` (`fingerprint`);
CREATE UNIQUE INDEX `uk_identifier_account` ON `ops_public_keys` (`identifier`, `account`);

-- DROP TABLE IF EXISTS `ops_repo`;
CREATE TABLE IF NOT EXISTS `ops_repo` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '代码库ID',
  `spaceID` int unsigned NOT NULL DEFAULT 0 COMMENT '空间ID',
  `product` varchar(255) NOT NULL DEFAULT '' COMMENT '关联产品',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '代码库唯一标识',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '代码库描述',
  `scmType` varchar(10) NOT NULL DEFAULT 'git' COMMENT '源代码管理类型',
  `gitUID` char(42) NOT NULL DEFAULT '' COMMENT 'Git仓库唯一标识',
  `forkID` int unsigned NOT NULL DEFAULT 0 COMMENT '派生来源代码库ID',
  `mirror` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否为镜像库（0=普通库，1=镜像库）',
  `providerID` int unsigned NOT NULL DEFAULT 0 COMMENT '源代码提供商ID',
  `connector` text DEFAULT NULL COMMENT '连接器信息',
  `defaultBranch` varchar(255) NOT NULL DEFAULT '' COMMENT '默认分支名',
  `acl` varchar(30) NOT NULL DEFAULT 'open' COMMENT '权限:private,open',
  `status` varchar(30) NOT NULL DEFAULT 'active' COMMENT '代码库状态',
  `synced` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '同步标识',
  `branchArchivable` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否开启分支归档',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='代码库表';
CREATE UNIQUE INDEX `uk_name_spaceID` ON `ops_repo` (`name`, `spaceID`);
CREATE UNIQUE INDEX `uk_gitUID` ON `ops_repo` (`gitUID`);
CREATE INDEX `idx_deleted` ON `ops_repo` (`deleted`);

-- DROP TABLE IF EXISTS `ops_repobranch`;
CREATE TABLE IF NOT EXISTS `ops_repobranch` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `revision` int unsigned NOT NULL DEFAULT 0 COMMENT '修订版本',
  `branch` varchar(100) NOT NULL DEFAULT '' COMMENT '分支',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE UNIQUE INDEX `uk_repo_revision_branch` ON `ops_repobranch` (`repo`, `revision`, `branch`);
CREATE INDEX `idx_branch` ON `ops_repobranch` (`branch`);
CREATE INDEX `idx_revision` ON `ops_repobranch` (`revision`);

-- DROP TABLE IF EXISTS `ops_repofiles`;
CREATE TABLE IF NOT EXISTS `ops_repofiles` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `revision` int unsigned NOT NULL DEFAULT 0 COMMENT '修订版本',
  `path` varchar(255) NOT NULL DEFAULT '' COMMENT '路径',
  `oldPath` varchar(255) NOT NULL DEFAULT '' COMMENT '旧路径',
  `parent` varchar(255) NOT NULL DEFAULT '' COMMENT '父级ID',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '类型',
  `action` char(1) NOT NULL DEFAULT '' COMMENT '动作',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_path` ON `ops_repofiles` (`path`);
CREATE INDEX `idx_parent` ON `ops_repofiles` (`parent`);
CREATE INDEX `idx_repo` ON `ops_repofiles` (`repo`);
CREATE INDEX `idx_revision` ON `ops_repofiles` (`revision`);

-- DROP TABLE IF EXISTS `ops_repohistory`;
CREATE TABLE IF NOT EXISTS `ops_repohistory` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `revision` varchar(40) NOT NULL DEFAULT '' COMMENT '修订版本',
  `commit` int unsigned NOT NULL DEFAULT 0 COMMENT '提交记录',
  `comment` text DEFAULT NULL COMMENT '备注',
  `committer` varchar(100) NOT NULL DEFAULT '' COMMENT '提交者',
  `time` datetime DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;
CREATE INDEX `idx_repo` ON `ops_repohistory` (`repo`);
CREATE INDEX `idx_revision` ON `ops_repohistory` (`revision`);

-- DROP TABLE IF EXISTS `ops_repouser`;
CREATE TABLE IF NOT EXISTS `ops_repouser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'id',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '所属代码库',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '用户帐号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='代码库用户关联表';
CREATE UNIQUE INDEX `uk_repo_account` ON `ops_repouser` (`repo`, `account`);

-- DROP TABLE IF EXISTS `ops_request_reviewers`;
CREATE TABLE IF NOT EXISTS `ops_request_reviewers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `requestID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联合并请求ID',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联仓库ID',
  `decision` varchar(255) NOT NULL DEFAULT '' COMMENT '最新审核决策（如：approve-批准、reject-拒绝、pending-待审核等）',
  `sha` varchar(40) NOT NULL DEFAULT '' COMMENT '审核对应的代码提交SHA校验值',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '评审人',
  `opinion` mediumtext DEFAULT NULL COMMENT '评审意见',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='评审人员表';
CREATE INDEX `idx_requestID` ON `ops_request_reviewers` (`requestID`);
CREATE INDEX `idx_account` ON `ops_request_reviewers` (`account`);

-- DROP TABLE IF EXISTS `ops_review_flow`;
CREATE TABLE IF NOT EXISTS `ops_review_flow` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `repo` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '评审流程名称',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '评审流程描述',
  `definition` text DEFAULT NULL COMMENT '评审规则，json定义',
  `status` varchar(20) NOT NULL DEFAULT '' COMMENT 'enable-启用，disable-停用',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='评审流程表';
CREATE INDEX `idx_repo` ON `ops_review_flow` (`repo`);

-- DROP TABLE IF EXISTS `ops_runner`;
CREATE TABLE IF NOT EXISTS `ops_runner` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT 'Runner名称',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT 'Runner描述',
  `version` varchar(50) NOT NULL DEFAULT '' COMMENT 'Runner版本',
  `hostname` varchar(255) NOT NULL DEFAULT '' COMMENT '主机名',
  `ip` varchar(45) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `os` varchar(50) NOT NULL DEFAULT '' COMMENT '操作系统',
  `arch` varchar(20) NOT NULL DEFAULT '' COMMENT '系统架构',
  `labels` text DEFAULT NULL COMMENT '标签（JSON格式）',
  `token` varchar(255) NOT NULL DEFAULT '' COMMENT '认证令牌',
  `heartBeat` int unsigned NOT NULL DEFAULT 0 COMMENT '心跳时间戳',
  `online` varchar(20) NOT NULL DEFAULT 'offline' COMMENT '在线状态 (online:离线, offline:在线)',
  `status` varchar(20) NOT NULL DEFAULT 'disable' COMMENT 'Runner状态 (disable:停用, enable:启用)',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='runner 基本信息表';
CREATE INDEX `idx_token` ON `ops_runner` (`token`);
CREATE INDEX `idx_online` ON `ops_runner` (`online`);
CREATE INDEX `idx_status` ON `ops_runner` (`status`);

-- DROP TABLE IF EXISTS `ops_scan_issue_task_binds`;
CREATE TABLE IF NOT EXISTS `ops_scan_issue_task_binds` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `taskID` int unsigned NOT NULL DEFAULT 0 COMMENT '扫描任务ID',
  `issueID` int unsigned NOT NULL DEFAULT 0 COMMENT '扫描问题ID',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描问题与任务绑定关系表';
CREATE UNIQUE INDEX `uk_taskID_issueID` ON `ops_scan_issue_task_binds` (`taskID`, `issueID`);
CREATE INDEX `idx_taskID` ON `ops_scan_issue_task_binds` (`taskID`);
CREATE INDEX `idx_issueID` ON `ops_scan_issue_task_binds` (`issueID`);

-- DROP TABLE IF EXISTS `ops_scan_issues`;
CREATE TABLE IF NOT EXISTS `ops_scan_issues` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `issueKey` varchar(255) NOT NULL DEFAULT '' COMMENT '问题唯一标识（SHA1）',
  `ruleID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联规则ID',
  `message` text DEFAULT NULL COMMENT '问题描述信息',
  `path` varchar(500) NOT NULL DEFAULT '' COMMENT '文件路径',
  `line` bigint unsigned NOT NULL DEFAULT 0 COMMENT '行号',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `repoBranch` varchar(255) NOT NULL DEFAULT '' COMMENT '仓库分支',
  `createdByTaskID` int unsigned NOT NULL DEFAULT 0 COMMENT '创建该问题的任务ID',
  `updatedByTaskID` int unsigned NOT NULL DEFAULT 0 COMMENT '最后更新该问题的任务ID',
  `status` varchar(20) NOT NULL DEFAULT 'wait' COMMENT '问题状态（wait/todo/solving/solved/closed/ignore）',
  `scanMethod` varchar(20) NOT NULL DEFAULT '' COMMENT '扫描方法（check/smell）',
  `payload` text DEFAULT NULL COMMENT '扩展数据（JSON）',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `resolution` varchar(50) NOT NULL DEFAULT '' COMMENT '问题解决方案（bydesign/duplicate/external/fixed/notrepro/postponed/willnotfix/tostory）',
  `resolved` datetime DEFAULT NULL COMMENT '问题解决时间',
  `closed` datetime DEFAULT NULL COMMENT '问题关闭时间',
  `ignored` bigint unsigned NOT NULL DEFAULT 0 COMMENT '问题忽略到期时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描问题表';
CREATE UNIQUE INDEX `uk_issueKey` ON `ops_scan_issues` (`issueKey`);
CREATE INDEX `idx_repoID_repoBranch` ON `ops_scan_issues` (`repoID`, `repoBranch`);
CREATE INDEX `idx_ruleID` ON `ops_scan_issues` (`ruleID`);
CREATE INDEX `idx_status` ON `ops_scan_issues` (`status`);
CREATE INDEX `idx_scanMethod` ON `ops_scan_issues` (`scanMethod`);
CREATE INDEX `idx_deleted` ON `ops_scan_issues` (`deleted`);
CREATE INDEX `idx_resolution` ON `ops_scan_issues` (`resolution`);

-- DROP TABLE IF EXISTS `ops_scan_plan_conditions`;
CREATE TABLE IF NOT EXISTS `ops_scan_plan_conditions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `planID` int unsigned NOT NULL DEFAULT 0 COMMENT '扫描计划ID',
  `triggerID` int unsigned NOT NULL DEFAULT 0 COMMENT '触发器ID',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `priority` varchar(20) NOT NULL DEFAULT '' COMMENT '规则优先级（low-低、medium-中、high-高）',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '条件类型（defect/security/compliance/optimize）',
  `unit` varchar(20) NOT NULL DEFAULT '' COMMENT '阈值单位（count/percent）',
  `threshold` bigint unsigned NOT NULL DEFAULT 0 COMMENT '阈值',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描计划触发条件表';
CREATE INDEX `idx_triggerID_deleted` ON `ops_scan_plan_conditions` (`triggerID`, `deleted`);
CREATE INDEX `idx_repoID_planID_triggerID_deleted` ON `ops_scan_plan_conditions` (`repoID`, `planID`, `triggerID`, `deleted`);
CREATE INDEX `idx_deleted` ON `ops_scan_plan_conditions` (`deleted`);

-- DROP TABLE IF EXISTS `ops_scan_plan_solutions`;
CREATE TABLE IF NOT EXISTS `ops_scan_plan_solutions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `planID` int unsigned NOT NULL DEFAULT 0 COMMENT '扫描计划ID',
  `solutionID` int unsigned NOT NULL DEFAULT 0 COMMENT '扫描方案ID',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描计划与扫描方案关联表';
CREATE UNIQUE INDEX `uk_planID_solutionID_deleted` ON `ops_scan_plan_solutions` (`planID`, `solutionID`, `deleted`);
CREATE INDEX `idx_planID` ON `ops_scan_plan_solutions` (`planID`);
CREATE INDEX `idx_planID_deleted` ON `ops_scan_plan_solutions` (`planID`, `deleted`);
CREATE INDEX `idx_solutionID` ON `ops_scan_plan_solutions` (`solutionID`);
CREATE INDEX `idx_deleted` ON `ops_scan_plan_solutions` (`deleted`);

-- DROP TABLE IF EXISTS `ops_scan_plans`;
CREATE TABLE IF NOT EXISTS `ops_scan_plans` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '扫描计划名称',
  `desc` text DEFAULT NULL COMMENT '扫描计划描述',
  `scanType` varchar(20) NOT NULL DEFAULT 'full' COMMENT '扫描类型（full/incremental）',
  `branches` text DEFAULT NULL COMMENT '扫描分支范围',
  `files` text DEFAULT NULL COMMENT '扫描文件范围',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描计划表';
CREATE UNIQUE INDEX `uk_repoID_name_deleted` ON `ops_scan_plans` (`repoID`, `name`, `deleted`);
CREATE INDEX `idx_repoID_deleted` ON `ops_scan_plans` (`repoID`, `deleted`);
CREATE INDEX `idx_name` ON `ops_scan_plans` (`name`);
CREATE INDEX `idx_deleted` ON `ops_scan_plans` (`deleted`);

-- DROP TABLE IF EXISTS `ops_scan_rule_migrations`;
CREATE TABLE IF NOT EXISTS `ops_scan_rule_migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '迁移名称',
  `version` varchar(255) NOT NULL DEFAULT '' COMMENT '迁移版本',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描规则迁移记录表';

-- DROP TABLE IF EXISTS `ops_scan_rules`;
CREATE TABLE IF NOT EXISTS `ops_scan_rules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `ruleKey` varchar(255) NOT NULL DEFAULT '' COMMENT '规则唯一标识',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则名称',
  `desc` text DEFAULT NULL COMMENT '规则描述（中文）',
  `descEn` text DEFAULT NULL COMMENT '规则描述（英文）',
  `lang` varchar(50) NOT NULL DEFAULT '' COMMENT '语言类型（如：java、python等）',
  `plugin` varchar(50) NOT NULL DEFAULT '' COMMENT '所属插件名称（标识规则归属的扫描插件）',
  `tag` varchar(50) NOT NULL DEFAULT '' COMMENT '规则标签（用于分类筛选，如：安全、规范、性能等）',
  `priority` varchar(20) NOT NULL DEFAULT '' COMMENT '规则优先级（low-低、medium-中、high-高）',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '规则类型（如：defect(缺陷)、security(安全)、compliance(合规)、optimize(优化)等）',
  `content` text DEFAULT NULL COMMENT '规则内容（中文，如扫描正则、检查逻辑描述等）',
  `contentEn` text DEFAULT NULL COMMENT '规则内容（英文，如扫描正则、检查逻辑描述等）',
  `isCustom` tinyint unsigned NOT NULL DEFAULT 1 COMMENT '是否自定义规则（1=自定义规则，0=系统默认规则）',
  `status` varchar(20) NOT NULL DEFAULT 'enabled' COMMENT '规则状态（enabled-启用、disabled-禁用，默认启用）',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描规则表';
CREATE UNIQUE INDEX `uk_ruleKey_lang_deleted` ON `ops_scan_rules` (`ruleKey`, `lang`, `deleted`);
CREATE INDEX `idx_name` ON `ops_scan_rules` (`name`);
CREATE INDEX `idx_type` ON `ops_scan_rules` (`type`);
CREATE INDEX `idx_plugin_tag` ON `ops_scan_rules` (`plugin`, `tag`);
CREATE INDEX `idx_priority_status` ON `ops_scan_rules` (`priority`, `status`);
CREATE INDEX `idx_deleted` ON `ops_scan_rules` (`deleted`);
CREATE INDEX `idx_lang` ON `ops_scan_rules` (`lang`);

-- DROP TABLE IF EXISTS `ops_scan_ruleset_rules`;
CREATE TABLE IF NOT EXISTS `ops_scan_ruleset_rules` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `rulesetID` int unsigned NOT NULL DEFAULT 0 COMMENT '规则集主键ID',
  `ruleID` int unsigned NOT NULL DEFAULT 0 COMMENT '规则主键ID',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描集与扫描规则关联表';
CREATE UNIQUE INDEX `uk_rulesetID_ruleID_deleted` ON `ops_scan_ruleset_rules` (`rulesetID`, `ruleID`, `deleted`);
CREATE INDEX `idx_rulesetID` ON `ops_scan_ruleset_rules` (`rulesetID`);
CREATE INDEX `idx_ruleID` ON `ops_scan_ruleset_rules` (`ruleID`);
CREATE INDEX `idx_deleted` ON `ops_scan_ruleset_rules` (`deleted`);
CREATE INDEX `idx_rulesetID_ruleID_deleted` ON `ops_scan_ruleset_rules` (`rulesetID`, `ruleID`, `deleted`);

-- DROP TABLE IF EXISTS `ops_scan_rulesets`;
CREATE TABLE IF NOT EXISTS `ops_scan_rulesets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '规则集名称',
  `desc` text DEFAULT NULL COMMENT '规则集描述（中文）',
  `descEn` varchar(500) NOT NULL DEFAULT '' COMMENT '规则集描述（英文）',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '规则集类型（如：代码扫描、依赖扫描、全量扫描等）',
  `lang` varchar(50) NOT NULL DEFAULT '' COMMENT '适配语言类型（java、python，NULL表示适配所有语言）',
  `plugin` varchar(50) NOT NULL DEFAULT '' COMMENT '所属插件名称（标识规则集归属的扫描插件）',
  `tag` varchar(50) NOT NULL DEFAULT '' COMMENT '规则集标签（用于分类筛选，如：安全合规、代码规范、性能优化等）',
  `isCustom` tinyint unsigned NOT NULL DEFAULT 1 COMMENT '是否自定义规则集（1=自定义，0=系统默认规则集）',
  `status` varchar(20) NOT NULL DEFAULT 'enabled' COMMENT '规则状态（enabled-启用、disabled-禁用，默认启用）',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描规则集表';
CREATE UNIQUE INDEX `uk_name_lang_deleted` ON `ops_scan_rulesets` (`name`, `lang`, `deleted`);
CREATE INDEX `idx_name` ON `ops_scan_rulesets` (`name`);
CREATE INDEX `idx_type` ON `ops_scan_rulesets` (`type`);
CREATE INDEX `idx_status` ON `ops_scan_rulesets` (`status`);
CREATE INDEX `idx_plugin_tag` ON `ops_scan_rulesets` (`plugin`, `tag`);
CREATE INDEX `idx_deleted` ON `ops_scan_rulesets` (`deleted`);

-- DROP TABLE IF EXISTS `ops_scan_solution_rulesets`;
CREATE TABLE IF NOT EXISTS `ops_scan_solution_rulesets` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `solutionID` int unsigned NOT NULL DEFAULT 0 COMMENT '扫描方案主键ID',
  `rulesetID` int unsigned NOT NULL DEFAULT 0 COMMENT '扫描规则集主键ID',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描方案与规则集关联表';
CREATE UNIQUE INDEX `uk_solutionID_rulesetID_deleted` ON `ops_scan_solution_rulesets` (`solutionID`, `rulesetID`, `deleted`);
CREATE INDEX `idx_rulesetID` ON `ops_scan_solution_rulesets` (`rulesetID`);
CREATE INDEX `idx_solutionID` ON `ops_scan_solution_rulesets` (`solutionID`);
CREATE INDEX `idx_deleted` ON `ops_scan_solution_rulesets` (`deleted`);

-- DROP TABLE IF EXISTS `ops_scan_solutions`;
CREATE TABLE IF NOT EXISTS `ops_scan_solutions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '扫描解决方案名称',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '解决方案描述（中文）',
  `descEn` varchar(500) NOT NULL DEFAULT '' COMMENT '解决方案描述（英文）',
  `status` varchar(20) NOT NULL DEFAULT 'enabled' COMMENT '规则状态（enabled-启用、disabled-禁用，默认启用）',
  `tag` varchar(50) NOT NULL DEFAULT '' COMMENT '解决方案标签（用于分类筛选，如：安全加固、漏洞修复、合规整改等）',
  `isCustom` tinyint unsigned NOT NULL DEFAULT 1 COMMENT '是否自定义解决方案（1=自定义，0=系统默认解决方案）',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描方案表';
CREATE UNIQUE INDEX `uk_name_deleted` ON `ops_scan_solutions` (`name`, `deleted`);
CREATE INDEX `idx_name` ON `ops_scan_solutions` (`name`);
CREATE INDEX `idx_status` ON `ops_scan_solutions` (`status`);
CREATE INDEX `idx_deleted` ON `ops_scan_solutions` (`deleted`);

-- DROP TABLE IF EXISTS `ops_scan_tasks`;
CREATE TABLE IF NOT EXISTS `ops_scan_tasks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `planID` int unsigned NOT NULL DEFAULT 0 COMMENT '扫描计划ID',
  `executionID` int unsigned NOT NULL DEFAULT 0 COMMENT '执行ID',
  `repoNumber` bigint unsigned NOT NULL DEFAULT 0 COMMENT '仓库编号',
  `planNumber` bigint unsigned NOT NULL DEFAULT 0 COMMENT '计划编号',
  `triggerID` int unsigned NOT NULL DEFAULT 0 COMMENT '触发器ID',
  `triggerType` varchar(20) NOT NULL DEFAULT '' COMMENT '触发类型（manual/cron/action）',
  `status` varchar(20) NOT NULL DEFAULT '' COMMENT '任务状态（in_progress/success/failed）',
  `result` varchar(20) NOT NULL DEFAULT '' COMMENT '任务结果（pass/no-pass/-）',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `scanType` varchar(20) NOT NULL DEFAULT 'full' COMMENT '扫描类型（full/incremental）',
  `issueNumber` bigint unsigned NOT NULL DEFAULT 0 COMMENT '问题编号',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描任务表';
CREATE INDEX `idx_planID_deleted` ON `ops_scan_tasks` (`planID`, `deleted`);
CREATE INDEX `idx_executionID` ON `ops_scan_tasks` (`executionID`);
CREATE INDEX `idx_status` ON `ops_scan_tasks` (`status`);
CREATE INDEX `idx_deleted` ON `ops_scan_tasks` (`deleted`);

-- DROP TABLE IF EXISTS `ops_scan_trigger_solutions`;
CREATE TABLE IF NOT EXISTS `ops_scan_trigger_solutions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `triggerID` int unsigned NOT NULL DEFAULT 0 COMMENT '触发器ID',
  `solutionID` int unsigned NOT NULL DEFAULT 0 COMMENT '解决方案ID',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描触发器关联解决方案表';
CREATE UNIQUE INDEX `uk_triggerID_solutionID` ON `ops_scan_trigger_solutions` (`triggerID`, `solutionID`);
CREATE INDEX `idx_triggerID` ON `ops_scan_trigger_solutions` (`triggerID`);

-- DROP TABLE IF EXISTS `ops_scan_triggers`;
CREATE TABLE IF NOT EXISTS `ops_scan_triggers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `planID` int unsigned NOT NULL DEFAULT 0 COMMENT '扫描计划ID',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '触发器名称',
  `desc` text DEFAULT NULL COMMENT '触发器描述',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '触发器类型（cron/manual/event）',
  `scanType` varchar(20) NOT NULL DEFAULT '' COMMENT '扫描类型（full/incremental）',
  `cron` varchar(255) NOT NULL DEFAULT '' COMMENT 'cron 表达式',
  `cronBranch` varchar(255) NOT NULL DEFAULT '' COMMENT 'cron 扫描分支',
  `keywords` text DEFAULT NULL COMMENT '关键字过滤条件',
  `actions` text DEFAULT NULL COMMENT '触发动作配置',
  `disabled` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否禁用，0-启用，1-禁用',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='扫描计划触发器表';
CREATE INDEX `idx_planID` ON `ops_scan_triggers` (`planID`);
CREATE INDEX `idx_repoID_planID_deleted` ON `ops_scan_triggers` (`repoID`, `planID`, `deleted`);
CREATE INDEX `idx_deleted` ON `ops_scan_triggers` (`deleted`);

-- DROP TABLE IF EXISTS `ops_schedule_jobs`;
CREATE TABLE IF NOT EXISTS `ops_schedule_jobs` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '任务ID，自增主键',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '任务唯一标识',
  `type` varchar(255) NOT NULL DEFAULT '' COMMENT '任务类型',
  `priority` int unsigned NOT NULL DEFAULT 0 COMMENT '任务优先级',
  `data` text DEFAULT NULL COMMENT '任务数据（JSON格式）',
  `result` text DEFAULT NULL COMMENT '任务结果（JSON格式）',
  `maxDurationSeconds` int unsigned NOT NULL DEFAULT 0 COMMENT '最大执行时长（秒）',
  `maxRetries` int unsigned NOT NULL DEFAULT 0 COMMENT '最大重试次数',
  `state` varchar(255) NOT NULL DEFAULT '' COMMENT '任务状态',
  `scheduled` datetime DEFAULT NULL COMMENT '计划执行时间',
  `totalExecutions` int unsigned NOT NULL DEFAULT 0 COMMENT '总执行次数',
  `runBy` varchar(255) NOT NULL DEFAULT '' COMMENT '执行者',
  `runDeadline` datetime DEFAULT NULL COMMENT '执行截止时间',
  `runProgress` int unsigned NOT NULL DEFAULT 0 COMMENT '执行进度',
  `lastExecuted` datetime DEFAULT NULL COMMENT '最后执行时间',
  `isRecurring` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否为周期性任务，0-否，1-是',
  `recurringCron` varchar(255) NOT NULL DEFAULT '' COMMENT '周期性任务Cron表达式',
  `consecutiveFailures` int unsigned NOT NULL DEFAULT 0 COMMENT '连续失败次数',
  `lastFailureError` text DEFAULT NULL COMMENT '最后失败错误信息',
  `groupID` varchar(255) NOT NULL DEFAULT '' COMMENT '任务组ID',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='后台任务表';
CREATE UNIQUE INDEX `uk_name` ON `ops_schedule_jobs` (`name`);
CREATE INDEX `idx_type` ON `ops_schedule_jobs` (`type`);
CREATE INDEX `idx_state` ON `ops_schedule_jobs` (`state`);
CREATE INDEX `idx_groupID` ON `ops_schedule_jobs` (`groupID`);

-- DROP TABLE IF EXISTS `ops_space`;
CREATE TABLE IF NOT EXISTS `ops_space` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `name` varchar(200) NOT NULL DEFAULT '' COMMENT '名称',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '空间唯一标识',
  `acl` varchar(30) NOT NULL DEFAULT 'open' COMMENT '权限:private,open',
  `auth` varchar(30) NOT NULL DEFAULT 'extend' COMMENT '空间人员权限定义：extend继承，reset重定义',
  `desc` varchar(500) NOT NULL DEFAULT '' COMMENT '描述',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='空间表';
CREATE UNIQUE INDEX `uk_code` ON `ops_space` (`code`);

-- DROP TABLE IF EXISTS `ops_spaceuser`;
CREATE TABLE IF NOT EXISTS `ops_spaceuser` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `space` int unsigned NOT NULL DEFAULT 0 COMMENT '所属空间',
  `role` varchar(10) NOT NULL DEFAULT '' COMMENT '角色',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '用户帐号',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='空间用户关联表';
CREATE UNIQUE INDEX `uk_space_account` ON `ops_spaceuser` (`space`, `account`);

-- DROP TABLE IF EXISTS `ops_stage_executions`;
CREATE TABLE IF NOT EXISTS `ops_stage_executions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '阶段ID，自增主键',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的代码仓库ID,并发控制用',
  `executionID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的流水线执行记录ID',
  `parent` int unsigned NOT NULL DEFAULT 0 COMMENT '父执行记录ID（用于阶段重试场景，无则为0）',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '显示名称',
  `code` varchar(255) NOT NULL DEFAULT '' COMMENT '阶段内部标识名称',
  `sn` int unsigned NOT NULL DEFAULT 0 COMMENT 'serial number阶段序号',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT '类型:agent',
  `host` varchar(255) NOT NULL DEFAULT '' COMMENT 'machine运行机器标识',
  `os` varchar(255) NOT NULL DEFAULT '' COMMENT '阶段运行的操作系统（如：linux、windows、macos）',
  `arch` varchar(255) NOT NULL DEFAULT '' COMMENT '阶段运行的CPU架构（如：amd64、arm64、x86）',
  `labels` varchar(255) NOT NULL DEFAULT '' COMMENT '阶段标签，runtime.selector',
  `depends` varchar(255) NOT NULL DEFAULT '' COMMENT '依赖的前置阶段code',
  `runPolicy` varchar(50) NOT NULL DEFAULT 'onSuccess' COMMENT '执行策略：onSuccess,onFailure,always,custom',
  `customPolicy` varchar(255) NOT NULL DEFAULT '' COMMENT '自定义执行策略',
  `failPolicy` varchar(50) NOT NULL DEFAULT 'fail' COMMENT '失败策略：fail,ignore,warning',
  `started` datetime DEFAULT NULL COMMENT '阶段开始执行时间',
  `finished` datetime DEFAULT NULL COMMENT '阶段结束执行时间',
  `duration` int unsigned NOT NULL DEFAULT 0 COMMENT '执行时长(s)',
  `status` varchar(50) NOT NULL DEFAULT '' COMMENT '阶段执行状态',
  `errorType` varchar(50) NOT NULL DEFAULT '' COMMENT '失败类型',
  `errorCode` int unsigned NOT NULL DEFAULT 0 COMMENT '阶段执行退出码（0=成功，非0=失败）',
  `errorLog` text DEFAULT NULL COMMENT '阶段执行错误信息（无错误则为空字符串）',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='流水线阶段执行表';

-- DROP TABLE IF EXISTS `ops_step_executions`;
CREATE TABLE IF NOT EXISTS `ops_step_executions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '步骤唯一主键',
  `stageID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联阶段stages主键ID',
  `name` varchar(255) NOT NULL DEFAULT '' COMMENT '显示名称',
  `code` varchar(100) NOT NULL DEFAULT '' COMMENT '步骤业务唯一标识',
  `sn` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '步骤在所属阶段内的序号',
  `image` varchar(255) NOT NULL DEFAULT '' COMMENT '步骤执行所需的容器镜像',
  `depends` varchar(255) NOT NULL DEFAULT '' COMMENT '步骤依赖的其他步骤code',
  `groupID` int unsigned NOT NULL DEFAULT 0 COMMENT '步骤所属分组ID',
  `failPolicy` varchar(50) NOT NULL DEFAULT 'fail' COMMENT '失败策略：fail,ignore,warning',
  `started` datetime DEFAULT NULL COMMENT '步骤执行开始时间',
  `finished` datetime DEFAULT NULL COMMENT '步骤执行完成时间',
  `duration` int unsigned NOT NULL DEFAULT 0 COMMENT '执行时长(s)',
  `status` varchar(50) NOT NULL DEFAULT '' COMMENT '步骤执行状态（枚举）',
  `errorCode` int unsigned NOT NULL DEFAULT 0 COMMENT '步骤执行退出码',
  `errorLog` varchar(500) NOT NULL DEFAULT '' COMMENT '步骤执行失败时的错误信息，成功时为空字符串',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='流水线步骤执行表';

-- DROP TABLE IF EXISTS `ops_tokens`;
CREATE TABLE IF NOT EXISTS `ops_tokens` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `account` varchar(30) NOT NULL DEFAULT '' COMMENT '关联用户账号',
  `identifier` varchar(255) NOT NULL DEFAULT '' COMMENT 'Token唯一标识',
  `type` varchar(50) NOT NULL DEFAULT '' COMMENT 'Token类型: pat,app',
  `expirationDate` datetime DEFAULT NULL COMMENT '过期时间',
  `token` text DEFAULT NULL COMMENT 'Token值',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='令牌表';
CREATE UNIQUE INDEX `uk_identifier_account` ON `ops_tokens` (`identifier`, `account`);

-- DROP TABLE IF EXISTS `ops_triggers`;
CREATE TABLE IF NOT EXISTS `ops_triggers` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT '触发器ID，自增主键',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的仓库ID',
  `pipelineID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的流水线ID',
  `event` varchar(500) NOT NULL DEFAULT '' COMMENT '事件触发动作',
  `comment` varchar(255) NOT NULL DEFAULT '' COMMENT '当事件为 branch_updated 时进行提交关键词匹配',
  `cron` varchar(255) NOT NULL DEFAULT '' COMMENT '定时配置',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='流水线触发器表';
CREATE INDEX `idx_pipelineID` ON `ops_triggers` (`pipelineID`);

-- DROP TABLE IF EXISTS `ops_webhook_executions`;
CREATE TABLE IF NOT EXISTS `ops_webhook_executions` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Web钩子执行记录主键ID，自增',
  `webhookID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联的Web钩子ID',
  `triggerType` varchar(255) NOT NULL DEFAULT '' COMMENT '触发类型（如push、merge、release等）',
  `eventID` varchar(255) NOT NULL DEFAULT '' COMMENT '触发事件唯一标识,内部事件ID',
  `result` text DEFAULT NULL COMMENT '执行结果（成功/失败/超时等状态描述）',
  `duration` bigint unsigned NOT NULL DEFAULT 0 COMMENT '执行耗时（毫秒），从请求发送到接收响应的总时长',
  `error` text DEFAULT NULL COMMENT '执行错误信息（无错误则为空字符串）',
  `reqUrl` varchar(255) NOT NULL DEFAULT '' COMMENT '回调请求URL',
  `reqHeaders` text DEFAULT NULL COMMENT '回调请求头（JSON格式文本存储）',
  `reqBody` text DEFAULT NULL COMMENT '回调请求体（JSON/表单等格式文本）',
  `respStatusCode` int unsigned NOT NULL DEFAULT 0 COMMENT '回调响应状态码（如200、400、500等）',
  `respStatus` varchar(255) NOT NULL DEFAULT '' COMMENT '回调响应状态描述（如OK、Bad Request等）',
  `respHeaders` text DEFAULT NULL COMMENT '回调响应头（JSON格式文本存储）',
  `respBody` text DEFAULT NULL COMMENT '回调响应体（JSON/文本等格式）',
  `retriggerID` int unsigned NOT NULL DEFAULT 0 COMMENT '关联重触发的执行记录ID（若为重试则指向原执行记录）',
  `retriggerable` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否可重触发：1=可重触发，0=不可重触发',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Web钩子执行记录表：存储每次Web钩子回调的请求、响应、执行状态、耗时等全量信息';
CREATE INDEX `idx_webhookID` ON `ops_webhook_executions` (`webhookID`);
CREATE INDEX `idx_createdDate` ON `ops_webhook_executions` (`createdDate`);

-- DROP TABLE IF EXISTS `ops_webhooks`;
CREATE TABLE IF NOT EXISTS `ops_webhooks` (
  `id` int unsigned NOT NULL AUTO_INCREMENT COMMENT 'Web钩子主键ID，自增',
  `spaceID` int unsigned NOT NULL DEFAULT 0 COMMENT '空间ID，关联spaces表id',
  `repoID` int unsigned NOT NULL DEFAULT 0 COMMENT '仓库ID，关联repository表id',
  `displayName` varchar(255) NOT NULL DEFAULT '' COMMENT 'Web钩子显示名称',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT 'Web钩子描述信息',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'Web钩子回调地址',
  `secret` varchar(255) NOT NULL DEFAULT '' COMMENT 'Web钩子签名密钥',
  `isSSL` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否需要SSL证书验证：0=忽略，1=验证',
  `triggers` varchar(500) NOT NULL DEFAULT '' COMMENT '触发条件等',
  `internal` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否为内部钩子：1=是，0=否，默认0',
  `authMethod` varchar(90) NOT NULL DEFAULT '' COMMENT '认证方式（如none/basic/bearer等），默认空',
  `authHeader` varchar(255) NOT NULL DEFAULT '' COMMENT '认证请求头（如Basic Auth、Bearer Token等），默认空',
  `latestExecResult` varchar(255) NOT NULL DEFAULT '' COMMENT '最近一次执行结果（成功/失败/异常等）',
  `enabled` tinyint unsigned NOT NULL DEFAULT 1 COMMENT '是否启用Web钩子：1=启用，0=禁用',
  `createdBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁创建',
  `createdDate` datetime DEFAULT NULL COMMENT '创建时间',
  `editedBy` varchar(30) NOT NULL DEFAULT '' COMMENT '由谁编辑',
  `editedDate` datetime DEFAULT NULL COMMENT '编辑时间',
  `deleted` tinyint unsigned NOT NULL DEFAULT 0 COMMENT '是否删除',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB COMMENT='Web钩子配置表：存储Web钩子的基本信息、触发规则、认证方式、执行状态等';

INSERT INTO `ops_branch_type` (`id`, `repo`, `name`, `key`, `prefix`, `desc`, `createdBy`, `createdDate`, `editedBy`, `editedDate`, `deleted`) VALUES
(1, 0, '主干分支', 'main', 'main/,master/', '存放随时可以发布/部署的稳定代码。', 'system', NOW(), '', NULL, 0),
(2, 0, '开发分支', 'develop', 'develop/,dev/,story/', '日常开发的集成分支，完成的功能会合并到这里做集成测试。', 'system', NOW(), '', NULL, 0),
(3, 0, '特性分支', 'feature', 'feature/', '为实现某个新功能或任务而创建的短期分支。', 'system', NOW(), '', NULL, 0),
(4, 0, '预发布分支', 'release', 'release/', '用于准备一次正式发布的分支。', 'system', NOW(), '', NULL, 0),
(5, 0, '缺陷修复分支', 'bugfix', 'bugfix/,bug/', '用于修复非紧急的缺陷。', 'system', NOW(), '', NULL, 0),
(6, 0, '热修复分支', 'hotfix', 'hotfix/', '用于线上紧急修复。', 'system', NOW(), '', NULL, 0);

INSERT INTO `ops_plugin_group` (`name`, `desc`, `deleted`) VALUES
('default', '默认', 0),
('build', '构建', 0),
('test', '测试', 0),
('deploy', '部署', 0),
('artifact', '制品', 0),
('scm', '代码版本管理', 0);

ALTER TABLE `zt_group` ADD `devopsSpace` int unsigned NOT NULL DEFAULT 0 AFTER `project`;

DELETE FROM `zt_cron` WHERE `command` = 'moduleName=svn&methodName=run';
DELETE FROM `zt_cron` WHERE `command` = 'moduleName=git&methodName=run';
DELETE FROM `zt_cron` WHERE `command` = 'moduleName=ci&methodName=checkCompileStatus';
DELETE FROM `zt_cron` WHERE `command` = 'moduleName=ci&methodName=exec';
DELETE FROM `zt_cron` WHERE `command` = 'moduleName=mr&methodName=syncMR';
DELETE FROM `zt_cron` WHERE `command` = 'moduleName=compile&methodName=ajaxSyncCompile';
DELETE FROM `zt_cron` WHERE `command` = 'moduleName=ci&methodName=initQueue';
DELETE FROM `zt_cron` WHERE `command` = 'moduleName=instance&methodName=cronCleanBackup';
