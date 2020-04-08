<?php
$lang->repo->common          = '代码';
$lang->repo->browse          = '浏览';
$lang->repo->viewRevision    = '查看修订';
$lang->repo->create          = '创建';
$lang->repo->createAction    = '创建版本库';
$lang->repo->maintain        = '版本库列表';
$lang->repo->edit            = '编辑';
$lang->repo->editAction      = '编辑版本库';
$lang->repo->delete          = '删除版本库';
$lang->repo->showSyncCommit  = '显示同步进度';
$lang->repo->ajaxSyncCommit  = '接口：AJAX同步注释';
$lang->repo->setRules        = '指令配置';
$lang->repo->download        = '下载';
$lang->repo->downloadDiff    = '下载Diff';
$lang->repo->diffAction      = '版本对比';
$lang->repo->revisionAction  = '版本详情';
$lang->repo->blameAction     = '版本追溯';
$lang->repo->addBug          = '添加评审';
$lang->repo->editBug         = '编辑评审';
$lang->repo->deleteBug       = '删除评审';
$lang->repo->addComment      = '添加备注';
$lang->repo->editComment     = '编辑备注';
$lang->repo->deleteComment   = '删除备注';

$lang->repo->submit     = '提交';
$lang->repo->cancel     = '取消';
$lang->repo->addComment = '添加评论';

$lang->repo->product  = $lang->productCommon;
$lang->repo->module   = '模块';
$lang->repo->project  = $lang->projectCommon;
$lang->repo->type     = '类型';
$lang->repo->assign   = '指派';
$lang->repo->title    = '标题';
$lang->repo->detile   = '详情';
$lang->repo->lines    = '代码行';
$lang->repo->line     = '行';
$lang->repo->expand   = '点击展开';
$lang->repo->collapse = '点击折叠';

$lang->repo->id        = '编号';
$lang->repo->SCM       = '类型';
$lang->repo->name      = '名称';
$lang->repo->path      = '地址';
$lang->repo->prefix    = '地址扩展';
$lang->repo->config    = '配置目录';
$lang->repo->desc      = '描述';
$lang->repo->account   = '用户名';
$lang->repo->password  = '密码';
$lang->repo->encoding  = '编码';
$lang->repo->client    = '客户端';
$lang->repo->size      = '大小';
$lang->repo->revision  = '查看版本';
$lang->repo->revisionA = '版本';
$lang->repo->revisions = '版本';
$lang->repo->time      = '提交时间';
$lang->repo->committer = '作者';
$lang->repo->commits   = '提交数';
$lang->repo->synced    = '初始化同步';
$lang->repo->lastSync  = '最后同步时间';
$lang->repo->deleted   = '已删除';
$lang->repo->commit    = '提交';
$lang->repo->comment   = '注释';
$lang->repo->view      = '查看文件';
$lang->repo->viewA     = '查看';
$lang->repo->log       = '版本历史';
$lang->repo->blame     = '追溯';
$lang->repo->date      = '日期';
$lang->repo->diff      = '比较差异';
$lang->repo->diffAB    = '比较';
$lang->repo->diffAll   = '全部比较';
$lang->repo->viewDiff  = '查看差异';
$lang->repo->allLog    = '所有版本';
$lang->repo->location  = '位置';
$lang->repo->file      = '文件';
$lang->repo->action    = '操作';
$lang->repo->code      = '代码';
$lang->repo->review    = '评审';
$lang->repo->acl       = '权限';
$lang->repo->group     = '分组';
$lang->repo->user      = '用户';
$lang->repo->info      = '版本信息';

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

$lang->repo->scmList['Git']        = 'Git';
$lang->repo->scmList['Subversion'] = 'Subversion';

$lang->repo->notice                 = new stdclass();
$lang->repo->notice->syncing        = '正在同步中, 请稍等...';
$lang->repo->notice->syncComplete   = '同步完成，正在跳转...';
$lang->repo->notice->syncedCount    = '已经同步记录条数';
$lang->repo->notice->delete         = '是否要删除该版本库？';
$lang->repo->notice->successDelete  = '已经成功删除版本库。';
$lang->repo->notice->commentContent = '输入回复内容';
$lang->repo->notice->deleteBug      = '确认删除该Bug？';
$lang->repo->notice->deleteComment  = '确认删除该回复？';
$lang->repo->notice->lastSyncTime   = '最后更新于：';

$lang->repo->rules = new stdclass();
$lang->repo->rules->exampleLabel = "注释示例";
$lang->repo->rules->example['task']['start']  = "%start% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['task']['finish'] = "%finish% %task% %id%1%split%2 %cost%%consumedmark%10%cunit%";
$lang->repo->rules->example['task']['effort'] = "%effort% %task% %id%1%split%2 %cost%%consumedmark%1%cunit% %left%%leftmark%3%lunit%";
$lang->repo->rules->example['bug']['resolve'] = "%resolve% %bug% %id%1%split%2";

$lang->repo->error                = new stdclass();
$lang->repo->error->useless       = '你的服务器禁用了exec,shell_exec方法，无法使用该功能';
$lang->repo->error->connect       = '连接版本库失败，请填写正确的用户名、密码和版本库地址！';
$lang->repo->error->version       = "https和svn协议需要1.8及以上版本的客户端，请升级到最新版本！详情访问:http://subversion.apache.org/";
$lang->repo->error->path          = '版本库地址直接填写文件路径，如：/home/test。';
$lang->repo->error->cmd           = '客户端错误！';
$lang->repo->error->diff          = '必须选择两个版本';
$lang->repo->error->product       = "请选择{$lang->productCommon}！";
$lang->repo->error->commentText   = '请填写评审内容';
$lang->repo->error->comment       = '请填写内容';
$lang->repo->error->title         = '请填写标题';
$lang->repo->error->accessDenied  = '你没有权限访问该版本库';
$lang->repo->error->noFound       = '你访问的版本库不存在';
$lang->repo->error->noFile        = '目录 %s 不存在';
$lang->repo->error->noPriv        = '程序没有权限切换到目录 %s';
$lang->repo->error->output        = "执行命令：%s\n错误结果(%s)： %s\n";
$lang->repo->error->clientVersion = "客户端版本过低，请升级或更换SVN客户端";
$lang->repo->error->encoding      = "编码可能错误，请更换编码重试。";

$lang->repo->syncTips      = '请参照<a target="_blank" href="https://www.zentao.net/book/zentaopmshelp/207.html">这里</a>，设置版本库定时同步。';
$lang->repo->encodingsTips = "提交日志的编码，可以用逗号连接起来的多个，比如utf-8。";

$lang->repo->example              = new stdclass();
$lang->repo->example->client      = new stdclass();
$lang->repo->example->path        = new stdclass();
$lang->repo->example->client->git = "例如：/usr/bin/git";
$lang->repo->example->client->svn = "例如：/usr/bin/svn";
$lang->repo->example->path->git   = "例如：/home/user/myproject";
$lang->repo->example->path->svn   = "例如：http://example.googlecode.com/svn/trunk/myproject";
$lang->repo->example->config      = "https需要填写配置目录的位置，通过config-dir选项生成配置目录";
$lang->repo->example->encoding    = "填写版本库中文件的编码";

$lang->repo->typeList['standard']    = '规范';
$lang->repo->typeList['performance'] = '性能';
$lang->repo->typeList['security']    = '安全';
$lang->repo->typeList['redundancy']  = '冗余';
$lang->repo->typeList['logicError']  = '逻辑错误';
