<?php
$lang->webhook->common   = 'Webhook';
$lang->webhook->list     = 'Webhook列表';
$lang->webhook->api      = '接口';
$lang->webhook->entry    = '应用';
$lang->webhook->log      = '日志';
$lang->webhook->assigned = '指派给';
$lang->webhook->setting  = '设置';

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

$lang->webhook->typeList['bearychat'] = '倍洽';
$lang->webhook->typeList['dingding']  = '钉钉';
$lang->webhook->typeList['default']   = '默认';

$lang->webhook->sendTypeList['sync']  = '同步';
$lang->webhook->sendTypeList['async'] = '异步';

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
$lang->webhook->note->async   = '异步需要打开计划任务';
$lang->webhook->note->product = "此项为空时所有{$lang->productCommon}的动作都会触发钩子，否则只有关联{$lang->productCommon}的动作才会触发。";
$lang->webhook->note->project = "此项为空时所有{$lang->projectCommon}的动作都会触发钩子，否则只有关联{$lang->projectCommon}的动作才会触发。";

$lang->webhook->note->typeList['bearychat'] = '请在倍洽中添加一个禅道机器人，并将其webhook填写到此处。';
$lang->webhook->note->typeList['dingding']  = '请在钉钉中添加一个自定义机器人，并将其webhook填写到此处。';
$lang->webhook->note->typeList['default']   = '从第三方系统获取webhook并填写到此处。';

$lang->webhook->error = new stdclass();
$lang->webhook->error->curl = '需要加载php-curl扩展。';
