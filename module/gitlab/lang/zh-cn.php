<?php
$lang->gitlab = new stdclass;
$lang->gitlab->common        = 'GitLab';
$lang->gitlab->browse        = '浏览GitLab';
$lang->gitlab->create        = '添加GitLab';
$lang->gitlab->edit          = '编辑GitLab';
$lang->gitlab->bindUser      = '绑定用户';
$lang->gitlab->webhook       = 'webhook';
$lang->gitlab->bindProduct   = '关联产品';
$lang->gitlab->importIssue   = '关联issue';
$lang->gitlab->delete        = '删除GitLab';
$lang->gitlab->confirmDelete = '确认删除该GitLab吗？';
$lang->gitlab->gitlabAccount = 'GitLab用户';
$lang->gitlab->zentaoAccount = '禅道用户';

$lang->gitlab->browseAction  = 'GitLab列表';
$lang->gitlab->deleteAction  = '删除GitLab';
$lang->gitlab->gitlabProject = "{$lang->gitlab->common}项目";
$lang->gitlab->gitlabIssue   = "{$lang->gitlab->common} issue";
$lang->gitlab->zentaoProduct = '禅道产品';
$lang->gitlab->objectType    = '类型'; // task, bug, story

$lang->gitlab->id             = 'ID';
$lang->gitlab->name           = "{$lang->gitlab->common}名称";
$lang->gitlab->url            = '服务地址';
$lang->gitlab->token          = 'Token';
$lang->gitlab->defaultProject = '默认项目';
$lang->gitlab->private        = 'MD5验证';

$lang->gitlab->lblCreate  = '添加GitLab服务器';
$lang->gitlab->desc       = '描述';
$lang->gitlab->tokenFirst = 'Token不为空时，优先使用Token。';
$lang->gitlab->tips       = '使用密码时，请在GitLab全局安全设置中禁用"防止跨站点请求伪造"选项。';

$lang->gitlab->placeholder = new stdclass;
$lang->gitlab->placeholder->name  = '';
$lang->gitlab->placeholder->url   = "请填写Gitlab Server首页的访问地址，如：https://gitlab.zentao.net。";
$lang->gitlab->placeholder->token = "请填写具有admin权限账户的access token";

$lang->gitlab->noImportableIssues = "目前没有可供导入的issue。";
$lang->gitlab->tokenError         = "当前token非管理员权限。";
$lang->gitlab->hostError          = "无效的GitLab服务地址。";
$lang->gitlab->bindUserError      = "不能重复绑定用户 %s";
$lang->gitlab->importIssueError   = "未选择该issue所属的执行。";
$lang->gitlab->importIssueWarn    = "存在导入失败的issue，可再次尝试导入。";
