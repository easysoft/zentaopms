<?php
$lang->gitfox->common            = 'GitFox';
$lang->gitfox->browse            = '浏览GitFox';
$lang->gitfox->search            = '搜索';
$lang->gitfox->create            = '添加GitFox';
$lang->gitfox->edit              = '编辑GitFox';
$lang->gitfox->view              = 'GitFox详情';
$lang->gitfox->bindUser          = '权限设置';
$lang->gitfox->webhook           = '接口：允许Webhook调用';
$lang->gitfox->importIssue       = '关联Issue';
$lang->gitfox->delete            = '删除GitFox';
$lang->gitfox->confirmDelete     = '确认删除该GitFox吗？';
$lang->gitfox->gitfoxAvatar      = '头像';
$lang->gitfox->gitfoxAccount     = 'GitFox用户';
$lang->gitfox->gitfoxEmail       = 'GitFox用户邮箱';
$lang->gitfox->zentaoEmail       = '禅道用户邮箱';
$lang->gitfox->zentaoAccount     = '禅道用户';
$lang->gitfox->accountDesc       = '(系统会将相同邮箱地址的用户自动匹配)';
$lang->gitfox->bindingStatus     = '绑定状态';
$lang->gitfox->all               = '全部';
$lang->gitfox->notBind           = '未绑定';
$lang->gitfox->binded            = '已绑定';
$lang->gitfox->bindedError       = '绑定的用户已删除或者已修改，请重新绑定';
$lang->gitfox->bindDynamic       = '%s与禅道用户%s';
$lang->gitfox->serverFail        = '连接GitFox服务器异常，请检查GitFox服务器。';
$lang->gitfox->lastUpdate        = '最后更新';
$lang->gitfox->confirmAddWebhook = '您确定创建Webhook吗？';
$lang->gitfox->addWebhookSuccess = 'Webhook创建成功';
$lang->gitfox->failCreateWebhook = 'Webhook创建失败，请查看日志';
$lang->gitfox->placeholderSearch = '请输入名称';

$lang->gitfox->bindStatus['binded']      = $lang->gitfox->binded;
$lang->gitfox->bindStatus['notBind']     = "<span class='text-danger'>{$lang->gitfox->notBind}</span>";
$lang->gitfox->bindStatus['bindedError'] = "<span class='text-danger'>{$lang->gitfox->bindedError}</span>";

$lang->gitfox->browseAction         = 'GitFox列表';
$lang->gitfox->deleteAction         = '删除GitFox';
$lang->gitfox->gitfoxProject        = "{$lang->gitfox->common}项目";
$lang->gitfox->browseProject        = "GitFox项目列表";
$lang->gitfox->browseUser           = "用户";
$lang->gitfox->browseGroup          = "GitFox群组列表";
$lang->gitfox->browseBranch         = "GitFox分支列表";
$lang->gitfox->browseTag            = "GitFox标签列表";
$lang->gitfox->browseTagPriv        = "标签保护管理";
$lang->gitfox->gitfoxIssue          = "{$lang->gitfox->common} issue";
$lang->gitfox->zentaoProduct        = '禅道产品';
$lang->gitfox->objectType           = '类型'; // task, bug, story
$lang->gitfox->manageProjectMembers = '项目成员管理';
$lang->gitfox->createProject        = '添加GitFox项目';
$lang->gitfox->editProject          = '编辑GitFox项目';
$lang->gitfox->deleteProject        = '删除GitFox项目';
$lang->gitfox->createGroup          = '添加群组';
$lang->gitfox->editGroup            = '编辑群组';
$lang->gitfox->deleteGroup          = '删除群组';
$lang->gitfox->createUser           = '添加用户';
$lang->gitfox->editUser             = '编辑用户';
$lang->gitfox->deleteUser           = '删除用户';
$lang->gitfox->createBranch         = '添加分支';
$lang->gitfox->manageGroupMembers   = '群组成员管理';
$lang->gitfox->createWebhook        = '创建Webhook';
$lang->gitfox->browseBranchPriv     = '分支保护管理';
$lang->gitfox->createTag            = '创建标签';
$lang->gitfox->deleteTag            = '删除标签';
$lang->gitfox->svaeFailed           = '『%s』保存失败';

$lang->gitfox->id             = 'ID';
$lang->gitfox->name           = "应用名称";
$lang->gitfox->url            = '服务器地址';
$lang->gitfox->token          = 'Token';
$lang->gitfox->defaultProject = '默认项目';
$lang->gitfox->private        = 'MD5验证';

$lang->gitfox->server        = "服务器列表";
$lang->gitfox->lblCreate     = '添加GitFox服务器';
$lang->gitfox->desc          = '描述';
$lang->gitfox->tokenFirst    = 'Token不为空时，优先使用Token。';
$lang->gitfox->tips          = '使用密码时，请在GitFox全局安全设置中禁用"防止跨站点请求伪造"选项。';
$lang->gitfox->emptyError    = "不能为空";
$lang->gitfox->createSuccess = "创建成功";
$lang->gitfox->mustBindUser  = '您还未绑定GitFox用户，请联系管理员进行绑定';
$lang->gitfox->noAccess      = '权限不足';
$lang->gitfox->notCompatible = '当前GitFox版本与禅道不兼容，请升级GitFox版本后重试';
$lang->gitfox->deleted       = '已删除';

$lang->gitfox->placeholder = new stdclass;
$lang->gitfox->placeholder->name        = '';
$lang->gitfox->placeholder->url         = "请填写GitFox Server首页的访问地址，如：https://gitfox.zentao.net。";
$lang->gitfox->placeholder->token       = "请填写具有root权限账户的access token";
$lang->gitfox->placeholder->projectPath = "项目标识串只能包含字母、数字、“_”、“-”和“.”。不能以“-”开头，以.git或者.atom结尾";

$lang->gitfox->noImportableIssues = "目前没有可供导入的issue。";
$lang->gitfox->tokenError         = "当前token非root权限。";
$lang->gitfox->tokenLimit         = "GitFox Token权限不足。请更换为有root权限的GitFox Token。";
$lang->gitfox->hostError          = "当前GitFox服务器地址无效或当前GitFox版本与禅道不兼容，请确认当前服务器可被访问或联系管理员升级GitFox至%s及以上版本后重试";
$lang->gitfox->bindUserError      = "不能重复绑定用户 %s";
$lang->gitfox->importIssueError   = "未选择该issue所属的执行。";
$lang->gitfox->importIssueWarn    = "存在导入失败的issue，可再次尝试导入。";

$lang->gitfox->accessLevels[10] = 'Guest';
$lang->gitfox->accessLevels[20] = 'Reporter';
$lang->gitfox->accessLevels[30] = 'Developer';
$lang->gitfox->accessLevels[40] = 'Maintainer';
$lang->gitfox->accessLevels[50] = 'Owner';

$lang->gitfox->apiError[0]  = 'internal is not allowed in a private group.';
$lang->gitfox->apiError[1]  = 'public is not allowed in a private group.';
$lang->gitfox->apiError[2]  = 'is too short (minimum is 8 characters)';
$lang->gitfox->apiError[3]  = "can contain only letters, digits, '_', '-' and '.'. Cannot start with '-', end in '.git' or end in '.atom'";
$lang->gitfox->apiError[4]  = 'Branch already exists';
$lang->gitfox->apiError[5]  = 'Failed to save group {:path=>["has already been taken"]}';
$lang->gitfox->apiError[6]  = 'Failed to save group {:path=>["已经被使用"]}';
$lang->gitfox->apiError[7]  = '403 Forbidden';
$lang->gitfox->apiError[8]  = 'is invalid';
$lang->gitfox->apiError[9]  = 'admin is a reserved name';
$lang->gitfox->apiError[10] = 'has already been taken';
$lang->gitfox->apiError[11] = 'Missing CI config file';

$lang->gitfox->errorLang[0]  = '私有分组的项目，可见性级别不能设为内部。';
$lang->gitfox->errorLang[1]  = '私有分组的项目，可见性级别不能设为公开。';
$lang->gitfox->errorLang[2]  = '密码太短（最少8个字符）';
$lang->gitfox->errorLang[3]  = "只能包含字母、数字、'.'-'和'.'。不能以'-'开头、以'.git'结尾或以'.atom'结尾。";
$lang->gitfox->errorLang[4]  = '分支名已存在。';
$lang->gitfox->errorLang[5]  = '保存失败，群组URL路径已经被使用。';
$lang->gitfox->errorLang[6]  = '保存失败，群组URL路径已经被使用。';
$lang->gitfox->errorLang[7]  = $lang->gitfox->noAccess;
$lang->gitfox->errorLang[8]  = '格式错误';
$lang->gitfox->errorLang[9]  = 'admin是保留名';
$lang->gitfox->errorLang[10] = 'GitFox项目已存在';
$lang->gitfox->errorLang[11] = '缺少CI配置文件';

$lang->gitfox->errorResonse['Email has already been taken']    = '邮箱已存在';
$lang->gitfox->errorResonse['Username has already been taken'] = '用户名已存在';

$lang->gitfox->featureBar['binduser']['all']     = $lang->gitfox->all;
$lang->gitfox->featureBar['binduser']['notBind'] = $lang->gitfox->notBind;
$lang->gitfox->featureBar['binduser']['binded']  = $lang->gitfox->binded;
