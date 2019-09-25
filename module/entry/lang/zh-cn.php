<?php
$lang->entry->common  = '应用';
$lang->entry->list    = '应用列表';
$lang->entry->api     = '接口';
$lang->entry->webhook = 'Webhook';
$lang->entry->log     = '日志';
$lang->entry->setting = '设置';

$lang->entry->browse    = '浏览应用';
$lang->entry->create    = '添加应用';
$lang->entry->edit      = '编辑应用';
$lang->entry->delete    = '删除应用';
$lang->entry->createKey = '重新生成密钥';

$lang->entry->id          = 'ID';
$lang->entry->name        = '名称';
$lang->entry->account     = '账号';
$lang->entry->code        = '代号';
$lang->entry->freePasswd  = '免密登录';
$lang->entry->key         = '密钥';
$lang->entry->ip          = 'IP';
$lang->entry->desc        = '描述';
$lang->entry->createdBy   = '由谁创建';
$lang->entry->createdDate = '创建时间';
$lang->entry->editedby    = '最后编辑';
$lang->entry->editedDate  = '编辑时间';
$lang->entry->date        = '请求时间';
$lang->entry->url         = '请求地址';

$lang->entry->confirmDelete = '您确认要删除该应用吗？';
$lang->entry->help          = '使用说明';
$lang->entry->notify        = '消息通知';

$lang->entry->helpLink   = 'https://www.zentao.net/book/zentaopmshelp/integration-287.html';
$lang->entry->notifyLink = 'https://www.zentao.net/book/zentaopmshelp/301.html';

$lang->entry->note = new stdClass();
$lang->entry->note->name    = '授权应用名称';
$lang->entry->note->code    = '授权应用代号，必须为字母或数字的组合';
$lang->entry->note->ip      = "允许访问API的应用ip，多个ip用逗号隔开。支持IP段，如192.168.1.*";
$lang->entry->note->allIP   = '无限制';
$lang->entry->note->account = '授权应用账号';

$lang->entry->freePasswdList[1] = '开启';
$lang->entry->freePasswdList[0] = '关闭';

$lang->entry->errmsg['PARAM_CODE_MISSING']    = '缺少code参数';
$lang->entry->errmsg['PARAM_TOKEN_MISSING']   = '缺少token参数';
$lang->entry->errmsg['SESSION_CODE_MISSING']  = '缺少session code';
$lang->entry->errmsg['EMPTY_KEY']             = '应用未设置密钥';
$lang->entry->errmsg['INVALID_TOKEN']         = '无效的token参数';
$lang->entry->errmsg['SESSION_VERIFY_FAILED'] = 'session验证失败';
$lang->entry->errmsg['IP_DENIED']             = '该IP被限制访问';
$lang->entry->errmsg['ACCOUNT_UNBOUND']       = '未绑定用户';
$lang->entry->errmsg['INVALID_ACCOUNT']       = '用户不存在';
$lang->entry->errmsg['EMPTY_ENTRY']           = '应用不存在';
$lang->entry->errmsg['CALLED_TIME']           = 'Token已失效';
$lang->entry->errmsg['ERROR_TIMESTAMP']       = '错误的时间戳。';
