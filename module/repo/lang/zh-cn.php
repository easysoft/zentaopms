<?php
global $config;

$lang->repo->common          = '代码库';
$lang->repo->repo            = '代码库';
$lang->repo->codeRepo        = '仓库名称';
$lang->repo->browse          = '浏览';
$lang->repo->viewRevision    = '查看修订';
$lang->repo->product         = '关联' . $lang->productCommon;
$lang->repo->projects        = '相关' . $lang->projectCommon;
$lang->repo->execution       = '所属' . $lang->execution->common;
$lang->repo->create          = '创建';
$lang->repo->maintain        = '代码库列表';
$lang->repo->edit            = '编辑';
$lang->repo->delete          = '删除代码库';
$lang->repo->showSyncCommit  = '显示同步进度';
$lang->repo->ajaxSyncCommit  = '接口：AJAX同步注释';
$lang->repo->setRules        = '指令配置';
$lang->repo->download        = '下载';

$lang->repo->mirror = new stdclass();
$lang->repo->mirror->syncing             = '代码同步中...';
$lang->repo->mirror->refreshSync         = '刷新同步状态';
$lang->repo->mirror->lastUpdated         = '最后更新于：';
$lang->repo->mirror->failedTitle         = '代码同步失败';
$lang->repo->mirror->detail              = '查看详情';
$lang->repo->mirror->syncCode            = '同步代码库';
$lang->repo->mirror->syncTriggered       = '同步任务已触发';
$lang->repo->mirror->syncFailed          = '同步失败';
$lang->repo->mirror->syncRequestFailed   = '同步请求失败';
$lang->repo->mirror->queryFailed         = '查询失败';
$lang->repo->mirror->queryRequestFailed  = '查询请求失败';
$lang->repo->mirror->statusUpdated       = '同步状态已更新';
$lang->repo->mirror->stillRunning        = '仍在同步中...';
$lang->repo->mirror->done                = '同步已完成';
$lang->repo->mirror->failureTitle        = '代码同步失败原因';
$lang->repo->mirror->noDetail            = '暂无详情';

$lang->repo->downloadDiff    = '下载Diff';
$lang->repo->addBug          = '添加评审';
$lang->repo->editBug         = '编辑评审';
$lang->repo->deleteBug       = '删除评审';
$lang->repo->addComment      = '添加备注';
$lang->repo->editComment     = '编辑备注';
$lang->repo->deleteComment   = '删除备注';
$lang->repo->encrypt         = '加密方式';
$lang->repo->addWebHook      = '添加Webhook';
$lang->repo->apiGetRepoByUrl = '接口：通过URL获取代码库';
$lang->repo->blameTmpl       = '第 <strong>%line</strong> 行代码相关信息： %name 于 %time 提交 %version %comment';
$lang->repo->notRelated      = '暂时没有关联禅道对象';
$lang->repo->source          = '基准';
$lang->repo->target          = '对比';
$lang->repo->descPlaceholder = '一句话描述';
$lang->repo->namespace       = '命名空间';
$lang->repo->branchName      = '分支名称';
$lang->repo->branchFrom      = '创建自';
$lang->repo->codeBranch      = '代码分支';
$lang->repo->createdBranch   = '已创建分支';
$lang->repo->unlink          = '解除关联';
$lang->repo->visit           = '访问';
$lang->repo->space           = '所属空间';
$lang->repo->allSpace        = '全部空间';
$lang->repo->members         = '成员';
$lang->repo->sshManager      = 'SSH密钥管理';
$lang->repo->defaultArtifact = '默认制品库';
$lang->repo->origin          = '来源';
$lang->repo->originRepo      = '源代码库';
$lang->repo->provider        = '服务器';
$lang->repo->providerID      = '服务器';
$lang->repo->organize        = '组织';
$lang->repo->targetRepo      = '目标代码库';
$lang->repo->afterImport     = '导入后';
$lang->repo->repoPath        = '代码库地址';
$lang->repo->slug            = '代码库地址';
$lang->repo->tips            = '提示';

$lang->repo->createBranchAction = '创建分支';
$lang->repo->createTagAction    = '创建标签';
$lang->repo->browseAction       = '浏览代码库';
$lang->repo->createAction       = '导入代码库';
$lang->repo->editAction         = '编辑代码库';
$lang->repo->diffAction         = '对比代码';
$lang->repo->downloadAction     = '下载代码库文件';
$lang->repo->revisionAction     = '查看提交详情';
$lang->repo->blameAction        = '代码追溯';
$lang->repo->reviewAction       = '代码问题列表';
$lang->repo->downloadCode       = '下载代码';
$lang->repo->downloadZip        = '下载压缩包';
$lang->repo->sshClone           = '使用SSH克隆';
$lang->repo->httpClone          = '使用HTTP克隆';
$lang->repo->cloneUrl           = '克隆地址';
$lang->repo->linkTask           = '关联任务';
$lang->repo->unlinkedTasks      = '未关联任务';
$lang->repo->importAction       = '批量导入代码库';
$lang->repo->import             = '导入代码库';
$lang->repo->importName         = '导入后的名称';
$lang->repo->importServer       = '请选择服务器';
$lang->repo->hide               = '隐藏';
$lang->repo->show               = '显示';
$lang->repo->showHidden         = '显示隐藏的代码库';
$lang->repo->gitlabList         = 'Gitlab代码库';
$lang->repo->batchCreate        = '批量导入代码库';
$lang->repo->browseTag          = '查看标签列表';
$lang->repo->browseBranch       = '查看分支列表';
$lang->repo->showImportProgress = '显示导入进度';
$lang->repo->showImportResult   = '显示导入结果';

$lang->repo->createRepoAction = '创建代码库';

$lang->repo->submit     = '提交';
$lang->repo->cancel     = '取消';
$lang->repo->addComment = '添加评论';
$lang->repo->addIssue   = '提问题';
$lang->repo->compare    = '比较';

$lang->repo->copy     = '点击复制';
$lang->repo->copied   = '复制成功';
$lang->repo->module   = '模块';
$lang->repo->type     = '类型';
$lang->repo->assign   = '指派';
$lang->repo->title    = '标题';
$lang->repo->detile   = '详情';
$lang->repo->lines    = '代码行';
$lang->repo->line     = '行';
$lang->repo->expand   = '点击展开';
$lang->repo->collapse = '点击折叠';

$lang->repo->id                 = 'ID';
$lang->repo->SCM                = '类型';
$lang->repo->name               = '名称';
$lang->repo->identifier         = '名称';
$lang->repo->path               = '地址';
$lang->repo->prefix             = '地址扩展';
$lang->repo->config             = '配置目录';
$lang->repo->desc               = '描述';
$lang->repo->account            = '用户名';
$lang->repo->password           = '密码';
$lang->repo->encoding           = '编码';
$lang->repo->client             = '客户端';
$lang->repo->size               = '大小';
$lang->repo->revision           = '提交';
$lang->repo->revisionA          = '提交';
$lang->repo->revisions          = '提交';
$lang->repo->time               = '提交时间';
$lang->repo->committer          = '提交人';
$lang->repo->commits            = '提交数';
$lang->repo->synced             = '初始化同步';
$lang->repo->lastSync           = '最后同步时间';
$lang->repo->deleted            = '已删除';
$lang->repo->commit             = '提交';
$lang->repo->comment            = '注释';
$lang->repo->view               = '查看文件';
$lang->repo->viewA              = '查看';
$lang->repo->log                = '提交历史';
$lang->repo->commitList         = '查看提交列表';
$lang->repo->blame              = '追溯';
$lang->repo->date               = '日期';
$lang->repo->diff               = '比较差异';
$lang->repo->diffAB             = '比较';
$lang->repo->diffAll            = '全部比较';
$lang->repo->viewDiff           = '查看差异';
$lang->repo->allLog             = '提交记录';
$lang->repo->codeLocation       = '代码位置';
$lang->repo->action             = '操作';
$lang->repo->code               = '代码';
$lang->repo->review             = '评审';
$lang->repo->acl                = '访问控制';
$lang->repo->group              = '分组';
$lang->repo->user               = '用户';
$lang->repo->info               = '提交信息';
$lang->repo->job                = '构建任务';
$lang->repo->fileServerUrl      = '预合并后上传服务器目录';
$lang->repo->fileServerAccount  = '文件服务器登录用户名';
$lang->repo->fileServerPassword = '文件服务器登录密码';
$lang->repo->linkStory          = '关联' . $lang->SRCommon;
$lang->repo->linkBug            = '关联Bug';
$lang->repo->linkTask           = '关联任务';
$lang->repo->unlink             = '取消关联';
$lang->repo->viewBugs           = '查看Bug';
$lang->repo->lastSubmitTime     = '最后提交时间';
$lang->repo->lastCommitter      = '提交人';
$lang->repo->lastUpdateTime     = '最后修改时间';
$lang->repo->createdBy          = '创建人';
$lang->repo->sourceCommit       = '来源提交';
$lang->repo->relations          = '相关';
$lang->repo->story              = '需求';
$lang->repo->searchTips         = '按%s搜索';
$lang->repo->design             = '设计';
$lang->repo->bug                = 'Bug';
$lang->repo->task               = '任务';

$lang->repo->title      = '标题';
$lang->repo->status     = '状态';
$lang->repo->openedBy   = '创建者';
$lang->repo->assignedTo = '指派给';
$lang->repo->openedDate = '创建日期';

$lang->repo->actionInfo     = "由%s在%s添加";
$lang->repo->changes        = "修改记录";
$lang->repo->reviewLocation = "%s@%s，%s行 - %s行";
$lang->repo->commentEdit    = '<i class="icon-pencil"></i>';
$lang->repo->commentDelete  = '<i class="icon-remove"></i>';
$lang->repo->allChanges     = "其他改动";
$lang->repo->commitTitle    = "第%s次提交";
$lang->repo->mark           = "开始标记";
$lang->repo->split          = "多ID间隔";

$lang->repo->objectRule   = '对象匹配规则';
$lang->repo->objectIdRule = '对象ID匹配规则';
$lang->repo->actionRule   = '动作匹配规则';
$lang->repo->manHourRule  = '工时匹配规则';
$lang->repo->ruleUnit     = "单位";
$lang->repo->ruleSplit    = "多关键字用';'分割，如：任务多关键字： Task;任务";

$lang->repo->viewDiffList['inline'] = '直列';
$lang->repo->viewDiffList['appose'] = '并排';

$lang->repo->encryptList['plain']  = '不加密';
$lang->repo->encryptList['base64'] = 'BASE64';

$lang->repo->logStyles['A'] = '添加';
$lang->repo->logStyles['M'] = '修改';
$lang->repo->logStyles['D'] = '删除';

$lang->repo->encodingList['utf_8'] = 'UTF-8';
$lang->repo->encodingList['gbk']   = 'GBK';

$lang->repo->scmList['Gitlab'] = 'GitLab';
if(!$config->inQuickon && !$config->inCompose)
{
    $lang->repo->scmList['Gitea']      = 'Gitea';
    $lang->repo->scmList['Gogs']       = 'Gogs';
    $lang->repo->scmList['Git']        = '本地 Git';
    $lang->repo->scmList['Subversion'] = 'Subversion';
}

$lang->repo->aclList['open']    = '公开 (拥有代码库所属空间访问权限，即可访问该代码库)';
$lang->repo->aclList['private'] = '私有 (仅代码库成员可访问该代码库)';

$lang->repo->showAclList['open']    = '公开';
$lang->repo->showAclList['private'] = '私有';

$lang->repo->gitlabHost    = 'GitLab Server';
$lang->repo->gitlabToken   = 'GitLab Token';
$lang->repo->gitlabProject = 'GitLab 项目';

$lang->repo->serviceHost    = '服务器';
$lang->repo->serviceProject = '仓库';

$lang->repo->placeholder = new stdclass;
$lang->repo->placeholder->gitlabHost = '请填写GitLab访问地址';

$lang->repo->notice                   = new stdclass();
$lang->repo->notice->syncing          = '正在同步中, 请稍等...';
$lang->repo->notice->syncComplete     = '同步完成，正在跳转...';
$lang->repo->notice->syncFailed       = '同步失败';
$lang->repo->notice->syncedCount      = '已经同步记录条数';
$lang->repo->notice->delete           = '是否解除关联代码库？';
$lang->repo->notice->deleteConfirm    = '是否删除代码库？此操作将永久移除该仓库及其所有内容和历史记录，且无法恢复。';
$lang->repo->notice->successDelete    = '已经成功解除代码库。';
$lang->repo->notice->commentContent   = '输入评论内容';
$lang->repo->notice->deleteReview     = '确认删除该评审？';
$lang->repo->notice->deleteBug        = '确认删除该Bug？';
$lang->repo->notice->deleteComment    = '确认删除该回复？';
$lang->repo->notice->lastSyncTime     = '最后更新于：';
$lang->repo->notice->unlinkBranch     = '确认解除分支与%s的关联关系吗？';
$lang->repo->notice->noRepoLeft       = '该服务器下的所有代码库都已经关联到禅道了，请选择其他服务器。';
$lang->repo->notice->noChanges        = '没有代码差异';
$lang->repo->notice->storyNotActive   = '需求不是激活状态，不能创建分支。';
$lang->repo->notice->taskNotActive    = '任务不是未开始或进行中状态，不能创建分支。';
$lang->repo->notice->bugNotActive     = 'Bug不是激活状态，不能创建分支。';

$lang->repo->rules = new stdclass();
$lang->repo->rules->exampleLabel = "注释示例";
$lang->repo->rules->example['task']['start']  = "%start% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['task']['finish'] = "%finish% %task% %id%1%split%2 %cost%%consumedmark%10%cunit%";
$lang->repo->rules->example['task']['effort'] = "%effort% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['bug']['resolve'] = "%resolve% %bug% %id%1%split%2";

$lang->repo->error = new stdclass();
$lang->repo->error->useless           = '你的服务器禁用了exec,shell_exec方法，无法使用该功能';
$lang->repo->error->connect           = '连接代码库失败，请填写正确的用户名、密码和代码库地址！';
$lang->repo->error->version           = "https和svn协议需要1.8及以上版本的客户端，请升级到最新版本！详情访问:http://subversion.apache.org/";
$lang->repo->error->path              = '代码库地址直接填写文件路径，如：/home/test。';
$lang->repo->error->cmd               = '客户端错误！';
$lang->repo->error->diff              = '必须选择两个提交';
$lang->repo->error->safe              = "因为安全原因，需要检测客户端版本，请将版本号写入文件 %s \n 可以执行命令：%s";
$lang->repo->error->product           = "请选择{$lang->productCommon}！";
$lang->repo->error->commentText       = '请填写评审内容';
$lang->repo->error->comment           = '请填写内容';
$lang->repo->error->title             = '请填写标题';
$lang->repo->error->accessDenied      = '你没有权限访问该代码库';
$lang->repo->error->noFound           = '你访问的代码库不存在';
$lang->repo->error->empty             = '代码库内容为空，无法同步';
$lang->repo->error->noFile            = '目录 %s 不存在或没有权限访问';
$lang->repo->error->noPriv            = '程序没有权限切换到目录 %s';
$lang->repo->error->output            = "执行命令：%s\n错误结果(%s)： %s\n";
$lang->repo->error->clientVersion     = "客户端版本过低，请升级或更换SVN客户端";
$lang->repo->error->encoding          = "编码可能错误，请更换编码重试。";
$lang->repo->error->deleted           = "解除失败，提交记录与设计( %s )关联。<br/>";
$lang->repo->error->linkedBranch      = "解除失败，代码库与%s分支( %s )关联。<br/>";
$lang->repo->error->linkedJob         = "解除失败，代码库与流水线( %s )关联。<br/>";
$lang->repo->error->linkedArtifact    = "解除失败，代码库与制品库( %s )关联。<br/>";
$lang->repo->error->clientPath        = "客户端安装目录不能有空格和特殊字符！";
$lang->repo->error->notFound          = "代码库『%s』路径 %s 不存在，请确认此代码库是否已在本地服务器被删除";
$lang->repo->error->noWritable        = '%s 不可写！请检查该目录权限，否则无法下载。';
$lang->repo->error->noCloneAddr       = '该项目克隆地址未找到';
$lang->repo->error->differentVersions = '基准和对比不能一样';
$lang->repo->error->needTwoVersion    = '必须选择两个分支/标签';
$lang->repo->error->projectUnique     = $lang->repo->serviceProject . '已经有这条记录了。如果您确定该记录已删除，请到后台-系统设置-回收站还原。';
$lang->repo->error->repoNameInvalid   = '名称必须以字母或 _ 开头，只包含字母数字，连接符，下划线和点。';
$lang->repo->error->createdFail       = '创建失败';
$lang->repo->error->branchNameTooLong = '分支名称不能超过30个字符';
$lang->repo->error->noProduct         = '在开始导入代码库之前，请先关联产品。';
$lang->repo->error->emptyVersion      = '版本不能为空';
$lang->repo->error->versionError      = '版本格式错误！';

$lang->repo->syncTips          = '请参照<a target="_blank" href="https://www.zentao.net/book/zentaopmshelp/207.html">这里</a>，设置代码库定时同步。';
$lang->repo->encodingsTips     = "提交日志的编码，可以用逗号连接起来的多个，比如utf-8。";
$lang->repo->pathTipsForGitlab = "GitLab 项目URL";

$lang->repo->example              = new stdclass();
$lang->repo->example->client      = new stdclass();
$lang->repo->example->path        = new stdclass();
$lang->repo->example->client->git = "例如：/usr/bin/git";
$lang->repo->example->client->svn = "例如：/usr/bin/svn";
$lang->repo->example->path->git   = "例如：/home/user/myproject";
$lang->repo->example->path->svn   = "例如：http://example.googlecode.com/svn/trunk/myproject";
$lang->repo->example->config      = "https需要填写配置目录的位置，通过config-dir选项生成配置目录";
$lang->repo->example->encoding    = "填写代码库中文件的编码";

$lang->repo->typeList['standard']    = '规范';
$lang->repo->typeList['performance'] = '性能';
$lang->repo->typeList['security']    = '安全';
$lang->repo->typeList['redundancy']  = '冗余';
$lang->repo->typeList['logicError']  = '逻辑错误';

$lang->repo->featureBar['maintain']['all'] = '全部';

$lang->repo->errorLang[0] = "只能包含字母、数字、'.'-'和'.'。不能以'-'开头、以'.git'结尾或以'.atom'结尾。";
$lang->repo->errorLang[1] = '分支名已存在。';
$lang->repo->errorLang[2] = '分支名已存在。';
$lang->repo->errorLang[3] = '权限不足。';
$lang->repo->errorLang[4] = "分支名不能包含 ' ', '~', '^'或':'。";
$lang->repo->errorLang[5] = '分支创建失败';
$lang->repo->errorLang[6] = '权限不足。';

$lang->repo->apiError[0] = "can contain only letters, digits, '_', '-' and '.'. Cannot start with '-', end in '.git' or end in '.atom'";
$lang->repo->apiError[1] = 'Branch is exists';
$lang->repo->apiError[2] = 'branch.* already exists';
$lang->repo->apiError[3] = 'Forbidden';
$lang->repo->apiError[4] = 'cannot have ASCII control characters';
$lang->repo->apiError[5] = 'Created fail';
$lang->repo->apiError[6] = 'Project Not Found';

$lang->repo->branchType            = '分支类型';
$lang->repo->applicableBranchTypes = '适用分支类型';
$lang->repo->allBranchTypes        = '全部分支类型';

$lang->repo->branchRuleMode = array();
$lang->repo->branchRuleMode['inheritance']  = '继承';
$lang->repo->branchRuleMode['redefinition'] = '重定义';

$lang->repo->branchTypeRule = new stdClass();
$lang->repo->branchTypeRule->allowCreatedBy     = '允许哪些用户可以创建该类型分支';
$lang->repo->branchTypeRule->allowDeletedBy     = '允许哪些用户可以删除该类型分支';
$lang->repo->branchTypeRule->allowUpdatedBy     = '允许哪些用户可以更新该类型分支';
$lang->repo->branchTypeRule->allowForcePushedBy = '允许哪些用户可以强制进行推送';
$lang->repo->branchTypeRule->allowMergeFrom     = '允许哪些分支类型合并到该分支类型';
$lang->repo->branchTypeRule->allowMergeTo       = '允许合并到哪些分支类型';

$lang->repo->branchTypeRule->userOptionList = array();
$lang->repo->branchTypeRule->userOptionList['hasPriv'] = '有权限的用户均可';
$lang->repo->branchTypeRule->userOptionList['specify'] = '仅指定人员';

$lang->repo->branchTypeRule->branchTypeOptionList = array();
$lang->repo->branchTypeRule->branchTypeOptionList['all']     = '全部分支';
$lang->repo->branchTypeRule->branchTypeOptionList['specify'] = '指定分支类型';

$lang->repo->branchRule = new stdClass();
$lang->repo->branchRule->allowDeletedBy     = '允许哪些用户可以删除该分支';
$lang->repo->branchRule->allowUpdatedBy     = '允许哪些用户可以更新该分支';
$lang->repo->branchRule->allowForcePushedBy = '允许哪些用户可以强制进行推送';
$lang->repo->branchRule->allowMergeFrom     = '允许哪些分支类型合并到该分支';
$lang->repo->branchRule->allowMergeTo       = '允许合并到哪些分支类型';
$lang->repo->branchRule->delete             = '删除分支规则';
$lang->repo->branchRule->mode               = '规则控制';

$lang->repo->branchRule->userOptionList = array();
$lang->repo->branchRule->userOptionList['hasPriv'] = '有权限的用户均可';
$lang->repo->branchRule->userOptionList['specify'] = '仅指定人员';

$lang->repo->branchRule->branchTypeOptionList = array();
$lang->repo->branchRule->branchTypeOptionList['all']     = '全部分支';
$lang->repo->branchRule->branchTypeOptionList['specify'] = '指定分支类型';

$lang->repo->select            = '请选择...';
$lang->repo->searchPlaceholder = '按Git版本筛选';
$lang->repo->svnPlaceholder    = '请输入版本号';
$lang->repo->changeFile        = '改动文件';

$lang->repo->commitInfo   = '代码改动详情';
$lang->repo->linkedStory  = "相关需求";
$lang->repo->linkedTask   = "相关任务";
$lang->repo->linkedBug    = "相关Bug";
$lang->repo->commited     = "提交了";
$lang->repo->commentary   = "评论";
$lang->repo->issueTitle   = "问题标题";
$lang->repo->issueDesc    = "详请";
$lang->repo->dateTmpl     = "于 %s 提出";
$lang->repo->commentNum   = " 条评论";

$lang->repo->fileTotal  = '%d个文件';
$lang->repo->codeSurvey = '发生改动：总共<span class="add-cot">添加%d行</span>代码，<span class="delete-cot">删除%d行</span>代码';

$lang->repo->featureBar['review']['all']          = '全部';
$lang->repo->featureBar['review']['assigntome']   = '指派给我';
$lang->repo->featureBar['review']['openedbyme']   = '由我创建';
$lang->repo->featureBar['review']['resolvedbyme'] = '由我解决';
$lang->repo->featureBar['review']['assigntonull'] = '未指派';
$lang->repo->featureBar['review']['unresolved']   = '未解决';
$lang->repo->featureBar['review']['unclosed']     = '未关闭';

$lang->repo->browseSystem = '应用列表';

$lang->repo->system = new stdclass();
$lang->repo->system->product       = '所属产品';
$lang->repo->system->name          = '应用名称';
$lang->repo->system->latestRelease = '最新版本';
$lang->repo->system->deployStatus  = '最新版本状态';
$lang->repo->system->status        = '应用状态';

$lang->repo->remark              = "注释";
$lang->repo->codeTag             = '代码标签';
$lang->repo->tagName             = '标签名称';
$lang->repo->tagFrom             = '创建自';
$lang->repo->createTag           = '创建标签';
$lang->repo->deleteTag           = '删除标签';
$lang->repo->confirmTagDelete    = '您确定要删除此标签吗？';
$lang->repo->createBranch        = '创建分支';
$lang->repo->deleteBranch        = '删除分支';
$lang->repo->confirmBranchDelete = '您确定要删除此分支吗？';
$lang->repo->deleteDefaultBranch = '默认分支不能删除';
$lang->repo->divergence          = '落后|领先';
$lang->repo->ahead               = '领先';
$lang->repo->behind              = '落后';
$lang->repo->noDivergence        = '没有差异';
$lang->repo->noDivergenceOnHint  = '与%s分支没有差异';
$lang->repo->divergenceOnBranch  = '比%s分支';
$lang->repo->aheadHint           = '领先%s次提交';
$lang->repo->behindHint          = '落后%s次提交';
$lang->repo->default             = '默认';
$lang->repo->defaultBranch       = '默认分支';
$lang->repo->committerTip        = '提交人具有代码库的写入权限';
$lang->repo->commitDetail        = '%s 提交时间：%s，提交人：%s';
$lang->repo->hasNoProduct        = '当前项目或者执行没有关联产品';

$lang->repo->browseWebhooks     = 'Webhook列表';
$lang->repo->createWebhook      = '创建Webhook';
$lang->repo->editWebhook        = '编辑Webhook';
$lang->repo->logWebhook         = 'Webhook日志';
$lang->repo->viewWebhookRequest = '请求数据';
$lang->repo->deleteWebhook      = '删除Webhook';
$lang->repo->targetURL          = '目标URL';
$lang->repo->latestStatus       = '最近状态';
$lang->repo->enable             = '启用';
$lang->repo->disable            = '关闭';
$lang->repo->enableWebhook      = '启用/关闭Webhook';
$lang->repo->deleteWebhook      = '删除Webhook';

$lang->repo->webhook = new stdclass();
$lang->repo->webhook->statusList = array();
$lang->repo->webhook->statusList['enabled']  = '启用';
$lang->repo->webhook->statusList['disabled'] = '关闭';

$lang->repo->webhook->latestStatusList = array();
$lang->repo->webhook->latestStatusList['success'] = '成功';
$lang->repo->webhook->latestStatusList['fail']    = '失败';
$lang->repo->webhook->latestStatusList['pending'] = '未发送';

$lang->repo->webhook->logStatusList = array();
$lang->repo->webhook->logStatusList['success'] = '成功';
$lang->repo->webhook->logStatusList['fail']    = '失败';

$lang->repo->webhook->key                  = '密钥';
$lang->repo->webhook->desc                 = '描述';
$lang->repo->webhook->SSL                  = '启用 SSL 验证';
$lang->repo->webhook->triggerEvent         = '触发事件';
$lang->repo->webhook->customEvent          = '自定义事件';
$lang->repo->webhook->urlError             = '目标URL格式不正确';
$lang->repo->webhook->customEventError     = '自定义事件不能为空';
$lang->repo->webhook->nameExists           = '名称为%s的Webhook已经存在';
$lang->repo->webhook->defaultShowSecret    = '******';
$lang->repo->webhook->enabledSuccess       = '启用成功';
$lang->repo->webhook->disabledSuccess      = '关闭成功';
$lang->repo->webhook->enabledFail          = '启用失败';
$lang->repo->webhook->disabledFail         = '关闭失败';
$lang->repo->webhook->requestData          = '请求数据';
$lang->repo->webhook->requestDate          = '请求时间';
$lang->repo->webhook->triggerType          = '触发类型';
$lang->repo->webhook->requestURL           = '请求地址';
$lang->repo->webhook->requestHeaders       = '请求头';
$lang->repo->webhook->requestBody          = '请求数据';
$lang->repo->webhook->responseHeaders      = '响应头';
$lang->repo->webhook->responseBody         = '响应数据';
$lang->repo->webhook->emptyData            = '无数据';
$lang->repo->webhook->deleteSuccess        = '删除成功';
$lang->repo->webhook->confirmWebhookDelete = "确定要删除 '%s' 吗，删除后不可恢复";
$lang->repo->webhook->lengthError          = "『%s』长度应当不超过『%s』";
$lang->repo->webhook->deleteFail           = 'Webhook已经有触发记录，不允许删除';

$lang->repo->webhook->triggerEventList = array();
$lang->repo->webhook->triggerEventList[0] = '发送所有事件';
$lang->repo->webhook->triggerEventList[1] = '自定义事件';

$lang->repo->webhook->customEventList = array();
$lang->repo->webhook->customEventList['branch_created']           = '分支创建';
$lang->repo->webhook->customEventList['branch_updated']           = '分支更新';
$lang->repo->webhook->customEventList['branch_deleted']           = '分支删除';
$lang->repo->webhook->customEventList['tag_created']              = '标签创建';
$lang->repo->webhook->customEventList['tag_deleted']              = '标签删除';
$lang->repo->webhook->customEventList['pullreq_created']          = '创建评审请求';
$lang->repo->webhook->customEventList['pullreq_reopened']         = '重新打开评审请求';
$lang->repo->webhook->customEventList['pullreq_branch_updated']   = '更新评审请求分支';
$lang->repo->webhook->customEventList['pullreq_closed']           = '关闭评审请求';
$lang->repo->webhook->customEventList['pullreq_merged']           = '合并评审请求';

$lang->repo->sourceList = array();
$lang->repo->sourceList['GitLab']     = 'GitLab';
$lang->repo->sourceList['Gitea']      = 'Gitea';
$lang->repo->sourceList['Gogs']       = 'Gogs';
$lang->repo->sourceList['Subversion'] = 'Subversion';

$lang->repo->accessList = array();
$lang->repo->accessList['writable'] = '可读、可写、可管理';
$lang->repo->accessList['readonly'] = '只读（做镜像导入，在第三方代码库进行管理，由DevOps定期自动同步）';

$lang->repo->importProgress = new stdclass();
$lang->repo->importProgress->title        = '正在导入代码库...';
$lang->repo->importProgress->desc         = '正在将您的第三方代码库导入系统，请稍后，此过程可能需要几分钟。';
$lang->repo->importProgress->notice       = '请耐心等待数据导入完成，不要关闭本页面。';
$lang->repo->importProgress->leaveTip     = '代码库正在导入中，请勿关闭页面。页面关闭后，将无法查看代码库导入进度。';
$lang->repo->importProgress->acknowledge  = '我知道了';
$lang->repo->importProgress->importFailed = '导入失败';
$lang->repo->importProgress->failMessage  = '代码库导入失败：%s';
$lang->repo->importProgress->successTips  = '代码库导入成功, 您现在可以进行以下操作:';
$lang->repo->importProgress->toRepoBrowse = '进入代码库';
$lang->repo->importProgress->toRepoList   = '返回代码库列表';
$lang->repo->importProgress->tryAgain     = '重新尝试';
