<?php
$lang->webhook->common   = 'webhook';
$lang->webhook->list     = '钩子列表';
$lang->webhook->api      = '接口';
$lang->webhook->entry    = '应用';
$lang->webhook->log      = '日志';
$lang->webhook->assigned = '指派给';

$lang->webhook->browse = '浏览钩子';
$lang->webhook->create = '添加钩子';
$lang->webhook->edit   = '编辑钩子';
$lang->webhook->delete = '删除钩子';

$lang->webhook->id          = 'ID';
$lang->webhook->type        = '类型';
$lang->webhook->name        = '名称';
$lang->webhook->url         = 'Hook地址';
$lang->webhook->contentType = '内容类型';
$lang->webhook->sendType    = '发送方式';
$lang->webhook->product     = '关联产品';
$lang->webhook->project     = '关联项目';
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
$lang->webhook->paramsList['product']    = '所属产品';
$lang->webhook->paramsList['project']    = '所属项目';
$lang->webhook->paramsList['action']     = '动作';
$lang->webhook->paramsList['actor']      = '操作者';
$lang->webhook->paramsList['date']       = '操作日期';
$lang->webhook->paramsList['comment']    = '备注';
$lang->webhook->paramsList['text']       = '操作内容';

$lang->webhook->saveSuccess   = '保存成功';
$lang->webhook->confirmDelete = '您确认要删除该webhook吗？';

$lang->webhook->trimWords = '了';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async   = '异步需要打开计划任务';
$lang->webhook->note->product = '此项为空时所有产品的动作都会触发钩子，否则只有关联产品的动作才会触发。';
$lang->webhook->note->project = '此项为空时所有项目的动作都会触发钩子，否则只有关联项目的动作才会触发。';

$lang->webhook->note->typeList['bearychat'] = '请在倍洽中添加一个禅道机器人，并将其webhook填写到此处。';
$lang->webhook->note->typeList['dingding']  = '请在钉钉中添加一个自定义机器人，并将其webhook填写到此处。';
$lang->webhook->note->typeList['default']   = '从第三方系统获取webhook并填写到此处。';
