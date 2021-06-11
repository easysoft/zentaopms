<?php
$lang->gitlab = new stdclass;
$lang->gitlab->common        = 'Gitlab';
$lang->gitlab->browse        = '浏览gitlab';
$lang->gitlab->create        = '添加gitlab';
$lang->gitlab->edit          = '编辑gitlab';
$lang->gitlab->bind          = '绑定用户';
$lang->gitlab->delete        = '删除';
$lang->gitlab->confirmDelete = '确认删除该gitlab吗？';

$lang->gitlab->browseAction = 'gitlab列表';
$lang->gitlab->deleteAction = '删除gitlab';

$lang->gitlab->id       = 'ID';
$lang->gitlab->name     = '名称';
$lang->gitlab->url      = '服务地址';
$lang->gitlab->token    = 'Token';

$lang->gitlab->lblCreate  = '添加gitlab服务器';
$lang->gitlab->desc       = '描述';
$lang->gitlab->tokenFirst = 'Token不为空时，优先使用Token。';
$lang->gitlab->tips       = '使用密码时，请在gitlab全局安全设置中禁用"防止跨站点请求伪造"选项。';

$lang->gitlab->gitlabNameTips      = '该名称会显示在其他相应模块中 <br> 可使用便于识别的友好名称';
$lang->gitlab->gitlabUrlTips       = "填写示例：https://gitlab.zcorp.cc <br> 仅填写gitlab服务地址，不需要填写其他URI";
$lang->gitlab->gitlabTokenTips     = "请填写具有admin权限账户的access token <br> 可在：https://&lt;gitlab url&gt;/-/profile/personal_access_tokens 创建";

$lang->gitlab->tokenError = "当前token非管理员权限。";
$lang->gitlab->hostError  = "无效的gitlab服务地址。";
