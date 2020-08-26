<?php
$lang->webhook->common     = 'Webhook';
$lang->webhook->list       = 'Webhook列表';
$lang->webhook->api        = '接口';
$lang->webhook->entry      = '应用';
$lang->webhook->log        = '日志';
$lang->webhook->bind       = '绑定用户';
$lang->webhook->chooseDept = '选择同步部门';
$lang->webhook->assigned   = '指派给';
$lang->webhook->setting    = '设置';

$lang->webhook->browse = '浏览Webhook';
$lang->webhook->create = '添加Webhook';
$lang->webhook->edit   = '编辑Webhook';
$lang->webhook->delete = '删除Webhook';

$lang->webhook->id          = 'ID';
$lang->webhook->type        = '类型';
$lang->webhook->name        = '名称';
$lang->webhook->url         = 'Hook地址';
$lang->webhook->domain      = '禅道域名';
$lang->webhook->contentType = '内容类型';
$lang->webhook->sendType    = '发送方式';
$lang->webhook->secret      = '密钥';
$lang->webhook->product     = "关联{$lang->productCommon}";
$lang->webhook->project     = "关联{$lang->projectCommon}";
$lang->webhook->params      = '参数';
$lang->webhook->action      = '触发动作';
$lang->webhook->desc        = '描述';
$lang->webhook->createdBy   = '由谁创建';
$lang->webhook->createdDate = '创建时间';
$lang->webhook->editedby    = '最后编辑';
$lang->webhook->editedDate  = '编辑时间';
$lang->webhook->date        = '发送时间';
$lang->webhook->data        = '数据';
$lang->webhook->result      = '结果';

$lang->webhook->typeList['']            = '';
$lang->webhook->typeList['dinggroup']   = '钉钉群通知机器人';
$lang->webhook->typeList['dinguser']    = '钉钉工作消息通知';
$lang->webhook->typeList['wechatgroup'] = '企业微信群机器人';
$lang->webhook->typeList['wechatuser']  = '企业微信应用消息';
$lang->webhook->typeList['default']     = '其他';

$lang->webhook->sendTypeList['sync']  = '同步';
$lang->webhook->sendTypeList['async'] = '异步';

$lang->webhook->dingAgentId     = '钉钉AgentId';
$lang->webhook->dingAppKey      = '钉钉AppKey';
$lang->webhook->dingAppSecret   = '钉钉AppSecret';
$lang->webhook->dingUserid      = '钉钉用户';
$lang->webhook->dingBindStatus  = '钉钉绑定状态';
$lang->webhook->chooseDeptAgain = '重选部门';

$lang->webhook->wechatCorpId     = '企业ID';
$lang->webhook->wechatCorpSecret = '应用的凭证密钥';
$lang->webhook->wechatAgentId    = '企业应用的ID';
$lang->webhook->wechatUserid     = '微信用户';
$lang->webhook->wechatBindStatus = '微信绑定状态';

$lang->webhook->zentaoUser  = '禅道用户';

$lang->webhook->dingBindStatusList['0'] = '未绑定';
$lang->webhook->dingBindStatusList['1'] = '已绑定';

$lang->webhook->paramsList['objectType'] = '对象类型';
$lang->webhook->paramsList['objectID']   = '对象ID';
$lang->webhook->paramsList['product']    = "所属{$lang->productCommon}";
$lang->webhook->paramsList['project']    = "所属{$lang->projectCommon}";
$lang->webhook->paramsList['action']     = '动作';
$lang->webhook->paramsList['actor']      = '操作者';
$lang->webhook->paramsList['date']       = '操作日期';
$lang->webhook->paramsList['comment']    = '备注';
$lang->webhook->paramsList['text']       = '操作内容';

$lang->webhook->confirmDelete = '您确认要删除该webhook吗？';

$lang->webhook->trimWords = '了';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async   = '异步需要打开计划任务。';
$lang->webhook->note->bind    = '只有[钉钉/微信]工作通知类型才需要绑定用户。';
$lang->webhook->note->product = "此项为空时所有{$lang->productCommon}的动作都会触发钩子，否则只有关联{$lang->productCommon}的动作才会触发。";
$lang->webhook->note->project = "此项为空时所有{$lang->projectCommon}的动作都会触发钩子，否则只有关联{$lang->projectCommon}的动作才会触发。";

$lang->webhook->note->dingHelp   = " <a href='http://www.zentao.net/book/zentaopmshelp/358.html' target='_blank'><i class='icon-help'></i></a>";
$lang->webhook->note->wechatHelp = " <a href='http://www.zentao.net/book/zentaopmshelp/367.html' target='_blank'><i class='icon-help'></i></a>";

$lang->webhook->note->typeList['bearychat'] = '请在倍洽中添加一个禅道机器人，并将其webhook填写到此处。';
$lang->webhook->note->typeList['dingding']  = '请在钉钉中添加一个自定义机器人，并将其webhook填写到此处。';
$lang->webhook->note->typeList['weixin']    = '请在企业微信中添加一个自定义机器人，并将其webhook填写到此处。';
$lang->webhook->note->typeList['default']   = '从第三方系统获取webhook并填写到此处。';

$lang->webhook->error = new stdclass();
$lang->webhook->error->curl   = '需要加载php-curl扩展。';
$lang->webhook->error->noDept = '没有选择部门，请先选择同步部门。';
