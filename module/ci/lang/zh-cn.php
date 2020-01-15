<?php
$lang->ci->common                       = '持续集成';
$lang->ci->list                         = '列表';
$lang->ci->create                       = '添加';
$lang->ci->numb                         = '序号';
$lang->ci->name                         = '名称';

$lang->ci->subModules['credential']     = '凭证';
$lang->ci->subModules['jenkins']        = 'Jenkins';
$lang->ci->subModules['repo']           = '代码库';
$lang->ci->subModules['citask']         = '构建任务';

// credential
$lang->credential->common               = '凭证';
$lang->credential->browse               = '浏览凭证';
$lang->credential->create               = '添加凭证';
$lang->credential->edit                 = '编辑凭证';
$lang->credential->delete               = '删除凭证';
$lang->credential->confirmDelete        = '确认删除该凭证吗？';

$lang->credential->id                   = 'ID';
$lang->credential->name                 = '名称';
$lang->credential->type                 = '类型';
$lang->credential->username             = '用户名';
$lang->credential->password             = '密码';
$lang->credential->privateKey           = '私钥';
$lang->credential->passphrase           = '私钥密码';
$lang->credential->token                = 'Token';
$lang->credential->desc                 = '描述';

$lang->credential->typeList['account']  = '用户名密码';
$lang->credential->typeList['sshKey']  = '密钥';
$lang->credential->typeList['token']   = 'Token';

// jenkins
$lang->jenkins->common                  = 'Jenkins';
$lang->jenkins->browse                  = '浏览Jenkins';
$lang->jenkins->create                  = '添加Jenkins';
$lang->jenkins->edit                    = '编辑Jenkins';
$lang->jenkins->delete                  = '删除Jenkins';
$lang->jenkins->confirmDelete           = '确认删除该Jenkins吗？';

$lang->jenkins->id                      = 'ID';
$lang->jenkins->name                    = '名称';
$lang->jenkins->serviceUrl              = '服务地址';
$lang->jenkins->type                    = '认证方式';
$lang->jenkins->credential              = '凭证';
$lang->jenkins->username                = '用户名';
$lang->jenkins->password                = '密码';
$lang->jenkins->token                   = 'Token';
$lang->jenkins->desc                    = '描述';
$lang->jenkins->tips                    = '选择用户名密码类型凭证时，请在Jenkins全局安全设置中禁用"防止跨站点请求伪造"选项。';

$lang->jenkins->typeList['token']       = 'Token';
$lang->jenkins->typeList['credential']  = '凭证';

// ci task
$lang->citask->common                  = '构建任务';
$lang->citask->browse                  = '浏览构建任务';
$lang->citask->create                  = '添加构建任务';
$lang->citask->edit                    = '编辑构建任务';
$lang->citask->exeNow                  = '立即执行';
$lang->citask->delete                  = '删除构建任务';
$lang->citask->confirmDelete           = '确认删除该构建任务吗？';

$lang->citask->id                      = 'ID';
$lang->citask->name                    = '名称';
$lang->citask->repo                    = '代码库';
$lang->citask->jenkins                 = 'Jenkins服务';
$lang->citask->jenkinsTask             = 'Jenkins任务名';
$lang->citask->buildType               = '构建类型';
$lang->citask->triggerType             = '触发方式';
$lang->citask->scheduleType            = '时间计划';
$lang->citask->cornExpression          = 'Corn表达式';
$lang->citask->custom                  = '自定义';

$lang->citask->tagKeywords             = '标签关键字';
$lang->citask->commentKeywords         = '注释关键字';
$lang->citask->extTask                 = '执行任务';

$lang->citask->at                      = '在';
$lang->citask->time                    = '时间';
$lang->citask->exe                     = '执行';
$lang->citask->scheduleInterval        = '每隔';
$lang->citask->scheduleDay             = '天数';
$lang->citask->day                     = '天';
$lang->citask->lastExe                 = '最后执行';
$lang->citask->scheduleTime            = '时间';

$lang->citask->dayTypeList             = array(workDay=>'工作日', everyDay=>'每天');
$lang->citask->buildTypeList           = array(build=>'仅构建', buildAndDeploy=>'构建部署', buildAndTest=>'构建测试');
$lang->citask->triggerTypeList         = array(tag=>'打标签', commit=>'代码提交注释', schedule=>'定时计划');
$lang->citask->scheduleTypeList        = array(corn=>'Corn表达式', custom=>'自定义');

// repo
$lang->repo->common        = '代码库';
$lang->repo->browse        = '浏览代码库';
$lang->repo->create        = '添加代码库';
$lang->repo->edit          = '编辑代码库';
$lang->repo->delete        = '删除代码库';
$lang->repo->confirmDelete = '确认删除该代码库吗？';
$lang->repo->browseBranch  = '查看分支';

$lang->repo->id         = 'ID';
$lang->repo->name       = '名称';
$lang->repo->path       = '地址';
$lang->repo->type       = '类型';
$lang->repo->client     = '客户端';
$lang->repo->credential = '凭证';
$lang->repo->encoding   = '编码';
$lang->repo->account    = '用户名';
$lang->repo->password   = '密码';
$lang->repo->token      = 'Token';
$lang->repo->acl        = '权限';
$lang->repo->group      = '分组';
$lang->repo->user       = '用户';
$lang->repo->desc       = '描述';

$lang->repo->example           = new stdclass();
$lang->repo->example->client   = "例如：/usr/bin/svn, C:\subversion\svn.exe, /usr/bin/git";
$lang->repo->example->path     = "例如：SVN: http://example.googlecode.com/svn/,  GIT: /homt/test";
$lang->repo->example->config   = "https需要填写配置目录的位置，通过config-dir选项生成配置目录";
$lang->repo->example->encoding = "填写版本库中文件的编码";
$lang->repo->svnCredentialLimt = "Subversion版本库的凭证必须为用户名密码类型";

$lang->repo->showSyncComment   = '显示同步进度';
$lang->repo->watch             = '监听';

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

//$lang->repo->scmList['Subversion'] = 'Subversion';
$lang->repo->scmList['Git']        = 'Git';
$lang->repo->tips                  = '请使用用户<strong class="text-blue">{user}</strong>签出代码，以便于系统获取后续的代码同步权限。如：<strong class="text-blue">sudo -u {user} git clone git_rep_address</strong>';

$lang->repo->watchList['1'] = '';