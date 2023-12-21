<?php
global $config;

$lang->repo->common          = '代码库';
$lang->repo->codeRepo        = '代码库';
$lang->repo->browse          = '浏览';
$lang->repo->viewRevision    = '查看修订';
$lang->repo->product         = '所属' . $lang->productCommon;
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
$lang->repo->downloadDiff    = '下载Diff';
$lang->repo->addBug          = '添加评审';
$lang->repo->editBug         = '编辑评审';
$lang->repo->deleteBug       = '删除评审';
$lang->repo->addComment      = '添加备注';
$lang->repo->editComment     = '编辑备注';
$lang->repo->deleteComment   = '删除备注';
$lang->repo->encrypt         = '加密方式';
$lang->repo->repo            = '代码库';
$lang->repo->parent          = '父文件夹';
$lang->repo->branch          = '分支';
$lang->repo->tag             = '标签';
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

$lang->repo->createBranchAction = '创建分支';
$lang->repo->browseAction       = '浏览代码库';
$lang->repo->createAction       = '关联代码库';
$lang->repo->editAction         = '编辑代码库';
$lang->repo->diffAction         = '代码对比';
$lang->repo->downloadAction     = '下载代码库文件';
$lang->repo->revisionAction     = '版本详情';
$lang->repo->blameAction        = '版本追溯';
$lang->repo->reviewAction       = '评审列表';
$lang->repo->downloadCode       = '下载代码';
$lang->repo->downloadZip        = '下载压缩包';
$lang->repo->sshClone           = '使用SSH克隆';
$lang->repo->httpClone          = '使用HTTP克隆';
$lang->repo->cloneUrl           = '克隆地址';
$lang->repo->linkTask           = '关联任务';
$lang->repo->unlinkedTasks      = '未关联任务';
$lang->repo->importAction       = '导入代码库';
$lang->repo->import             = '导入';
$lang->repo->importName         = '导入后的名称';
$lang->repo->importServer       = '请选择服务器';
$lang->repo->gitlabList         = 'Gitlab代码库';
$lang->repo->batchCreate        = '批量关联代码库';

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
$lang->repo->path               = '地址';
$lang->repo->prefix             = '地址扩展';
$lang->repo->config             = '配置目录';
$lang->repo->desc               = '描述';
$lang->repo->account            = '用户名';
$lang->repo->password           = '密码';
$lang->repo->encoding           = '编码';
$lang->repo->client             = '客户端';
$lang->repo->size               = '大小';
$lang->repo->revision           = '查看版本';
$lang->repo->revisionA          = '版本';
$lang->repo->revisions          = '版本';
$lang->repo->time               = '提交时间';
$lang->repo->committer          = '作者';
$lang->repo->commits            = '提交数';
$lang->repo->synced             = '初始化同步';
$lang->repo->lastSync           = '最后同步时间';
$lang->repo->deleted            = '已删除';
$lang->repo->commit             = '提交';
$lang->repo->comment            = '注释';
$lang->repo->view               = '查看文件';
$lang->repo->viewA              = '查看';
$lang->repo->log                = '版本历史';
$lang->repo->blame              = '追溯';
$lang->repo->date               = '日期';
$lang->repo->diff               = '比较差异';
$lang->repo->diffAB             = '比较';
$lang->repo->diffAll            = '全部比较';
$lang->repo->viewDiff           = '查看差异';
$lang->repo->allLog             = '所有提交';
$lang->repo->location           = '位置';
$lang->repo->file               = '文件';
$lang->repo->action             = '操作';
$lang->repo->code               = '代码';
$lang->repo->review             = '评审';
$lang->repo->acl                = '访问控制';
$lang->repo->group              = '分组';
$lang->repo->user               = '用户';
$lang->repo->info               = '版本信息';
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

$lang->repo->title      = '标题';
$lang->repo->status     = '状态';
$lang->repo->openedBy   = '创建者';
$lang->repo->assignedTo = '指派给';
$lang->repo->openedDate = '创建日期';

$lang->repo->latestRevision = '最近修订版本';
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

$lang->repo->scmList['Gitlab']     = 'GitLab';
$lang->repo->scmList['Gogs']       = 'Gogs';
if(!$config->inQuickon) $lang->repo->scmList['Gitea']      = 'Gitea';
$lang->repo->scmList['Git']        = '本地 Git';
$lang->repo->scmList['Subversion'] = 'Subversion';

$lang->repo->aclList['private'] = '私有 (所属产品和相关项目人员可访问)';
$lang->repo->aclList['open']    = '公开 (有DevOps视图权限即可访问)';
$lang->repo->aclList['custom']  = '自定义';

$lang->repo->gitlabHost    = 'GitLab Server';
$lang->repo->gitlabToken   = 'GitLab Token';
$lang->repo->gitlabProject = 'GitLab 项目';

$lang->repo->serviceHost    = '服务器';
$lang->repo->serviceProject = '仓库';

$lang->repo->placeholder = new stdclass;
$lang->repo->placeholder->gitlabHost = '请填写GitLab访问地址';

$lang->repo->notice                 = new stdclass();
$lang->repo->notice->syncing        = '正在同步中, 请稍等...';
$lang->repo->notice->syncComplete   = '同步完成，正在跳转...';
$lang->repo->notice->syncFailed     = '同步失败';
$lang->repo->notice->syncedCount    = '已经同步记录条数';
$lang->repo->notice->delete         = '是否要删除该代码库？';
$lang->repo->notice->successDelete  = '已经成功删除代码库。';
$lang->repo->notice->commentContent = '输入评论内容';
$lang->repo->notice->deleteReview   = '确认删除该评审？';
$lang->repo->notice->deleteBug      = '确认删除该Bug？';
$lang->repo->notice->deleteComment  = '确认删除该回复？';
$lang->repo->notice->lastSyncTime   = '最后更新于：';

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
$lang->repo->error->diff              = '必须选择两个版本';
$lang->repo->error->safe              = "因为安全原因，需要检测客户端版本，请将版本号写入文件 %s \n 可以执行命令：%s";
$lang->repo->error->product           = "请选择{$lang->productCommon}！";
$lang->repo->error->commentText       = '请填写评审内容';
$lang->repo->error->comment           = '请填写内容';
$lang->repo->error->title             = '请填写标题';
$lang->repo->error->accessDenied      = '你没有权限访问该代码库';
$lang->repo->error->noFound           = '你访问的代码库不存在';
$lang->repo->error->noFile            = '目录 %s 不存在';
$lang->repo->error->noPriv            = '程序没有权限切换到目录 %s';
$lang->repo->error->output            = "执行命令：%s\n错误结果(%s)： %s\n";
$lang->repo->error->clientVersion     = "客户端版本过低，请升级或更换SVN客户端";
$lang->repo->error->encoding          = "编码可能错误，请更换编码重试。";
$lang->repo->error->deleted           = "删除代码库失败，当前代码库有提交记录与设计关联";
$lang->repo->error->linkedJob         = "删除代码库失败，当前代码库与构建有关联，请取消关联或删除构建。";
$lang->repo->error->clientPath        = "客户端安装目录不能有空格和特殊字符！";
$lang->repo->error->notFound          = "代码库『%s』路径 %s 不存在，请确认此代码库是否已在本地服务器被删除";
$lang->repo->error->noWritable        = '%s 不可写！请检查该目录权限，否则无法下载。';
$lang->repo->error->noCloneAddr       = '该项目克隆地址未找到';
$lang->repo->error->differentVersions = '基准和对比不能一样';
$lang->repo->error->needTwoVersion    = '必须选择两个分支/标签';
$lang->repo->error->emptyVersion      = '版本不能为空';
$lang->repo->error->versionError      = '版本格式错误！';
$lang->repo->error->projectUnique     = $lang->repo->serviceProject . '已经有这条记录了。如果您确定该记录已删除，请到后台-系统-数据-回收站还原。';
$lang->repo->error->repoNameInvalid   = '名称应该只包含字母数字，破折号，下划线和点。';
$lang->repo->error->createdFail       = '创建失败';
$lang->repo->error->noProduct         = '在开始关联代码库之前，请先关联项目所对应的产品。';

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
