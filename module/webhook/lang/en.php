<?php
$lang->webhook->common     = 'Webhook';
$lang->webhook->list       = 'Webhook List';
$lang->webhook->api        = 'API';
$lang->webhook->entry      = 'Apply';
$lang->webhook->log        = 'Log';
$lang->webhook->bind       = 'Link User';
$lang->webhook->chooseDept = 'Select Dept to Sync';
$lang->webhook->assigned   = 'Assigned to';
$lang->webhook->setting    = 'Settings';

$lang->webhook->logAction = 'Webhook Log';

$lang->webhook->browse = 'View Webhook ';
$lang->webhook->create = 'Create Webhook ';
$lang->webhook->edit   = 'Edit Webhook ';
$lang->webhook->delete = 'Delete Webhook ';

$lang->webhook->id          = 'ID';
$lang->webhook->type        = 'Type';
$lang->webhook->name        = 'Name';
$lang->webhook->url         = 'Webhook URL';
$lang->webhook->domain      = 'ZenTao Domain';
$lang->webhook->contentType = 'Content Type';
$lang->webhook->sendType    = 'Sending Type';
$lang->webhook->secret      = 'Secret';
$lang->webhook->product     = "Link {$lang->productCommon}";
$lang->webhook->execution   = "Link {$lang->execution->common}";
$lang->webhook->params      = 'Parameters';
$lang->webhook->action      = 'Trigger Action';
$lang->webhook->desc        = 'Description';
$lang->webhook->createdBy   = 'Creator';
$lang->webhook->createdDate = 'Created on';
$lang->webhook->editedby    = 'Last Edited by';
$lang->webhook->editedDate  = 'Edited on';
$lang->webhook->date        = 'Sent at';
$lang->webhook->data        = 'Data';
$lang->webhook->result      = 'Result';
$lang->webhook->products    = $lang->productCommon;
$lang->webhook->executions  = $lang->execution->common;
$lang->webhook->actions     = 'System Log';
$lang->webhook->deleted     = 'Deleted';
$lang->webhook->approval    = 'Approval Workflow Notifications';

$lang->webhook->typeList['']            = '';
$lang->webhook->typeList['dinggroup']   = 'DingTalk Group Bot';
$lang->webhook->typeList['dinguser']    = 'DingTalk notifications';
$lang->webhook->typeList['wechatgroup'] = 'WeCom Group Bot';
$lang->webhook->typeList['wechatuser']  = 'WeCom App Messages';
$lang->webhook->typeList['feishugroup'] = 'Feishu Group Bot';
$lang->webhook->typeList['feishuuser']  = 'Feishu Messages';
$lang->webhook->typeList['default']     = 'Others';

$lang->webhook->sendTypeList['sync']  = 'Synchronous';
$lang->webhook->sendTypeList['async'] = 'Asynchronous';

$lang->webhook->dingAgentId     = 'DingTalk AgentID';
$lang->webhook->dingAppKey      = 'DingTalk AppKey';
$lang->webhook->dingAppSecret   = 'DingTalk AppSecret';
$lang->webhook->dingUserid      = 'DingTalk User ID';
$lang->webhook->dingBindStatus  = 'DingTalk Binding';
$lang->webhook->chooseDeptAgain = 'Reselect Dept';

$lang->webhook->wechatCorpId     = 'Corp ID';
$lang->webhook->wechatCorpSecret = 'App Secret';
$lang->webhook->wechatAgentId    = 'Agent ID';
$lang->webhook->wechatUserid     = 'WeCome User';
$lang->webhook->wechatBindStatus = 'WeCome Binding';

$lang->webhook->feishuAppId       = 'Feishu App ID';
$lang->webhook->feishuAppSecret   = 'Feishu App Secret';
$lang->webhook->feishuUserid      = 'Feishu User';
$lang->webhook->feishuBindStatus  = 'Feishu Binding';

$lang->webhook->zentaoUser  = 'Zentao User';

$lang->webhook->dingBindStatusList['0'] = 'No';
$lang->webhook->dingBindStatusList['1'] = 'Yes';

$lang->webhook->paramsList['objectType'] = 'Object Type';
$lang->webhook->paramsList['objectID']   = 'Object ID';
$lang->webhook->paramsList['product']    = "{$lang->productCommon}";
$lang->webhook->paramsList['execution']  = "{$lang->execution->common}";
$lang->webhook->paramsList['action']     = 'Action';
$lang->webhook->paramsList['actor']      = 'Acted by';
$lang->webhook->paramsList['date']       = 'Acted on';
$lang->webhook->paramsList['comment']    = 'Comment';
$lang->webhook->paramsList['text']       = 'Action Details';

$lang->webhook->confirmDelete = 'Are you sure you want to delete this webhook?';
$lang->webhook->friendlyTips  = 'Tip: Click a department to expand its sub-departments.';
$lang->webhook->loadPrompt    = 'Loading large dataset, please wait...';

$lang->webhook->trimWords = '';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async     = 'Asynchronous mode requires enabled scheduled tasks (Cron) in Admin-System.';
$lang->webhook->note->bind      = 'User linking is only required for [DingTalk/WeCom] notification types.';
$lang->webhook->note->product   = "If left empty, actions from all {$lang->productCommon}s will trigger the webhook. Otherwise, only actions from linked {$lang->productCommon} will trigger it.";
$lang->webhook->note->execution = "If left empty, actions from all {$lang->execution->common}s will trigger the webhook. Otherwise, only actions from linked {$lang->execution->common} will trigger it.";

$lang->webhook->note->dingHelp   = " <a href='http://www.zentao.net/book/zentaopmshelp/358.html' target='_blank'><i class='icon-help'></i></a>";
$lang->webhook->note->wechatHelp = " <a href='http://www.zentao.net/book/zentaopmshelp/367.html' target='_blank'><i class='icon-help'></i></a>";

$lang->webhook->note->typeList['bearychat'] = 'Please add a ZenTao Bot in BearyChat and enter its Webhook URL here.';
$lang->webhook->note->typeList['dingding']  = 'Please add a Custom Bot in DingTalk and enter its Webhook URL here.';
$lang->webhook->note->typeList['weixin']    = 'Please add a Custom Bot in WeCom and enter its Webhook URL here.';
$lang->webhook->note->typeList['default']   = 'Get the Webhook URL from the third-party system and enter it here.';

$lang->webhook->error               = new stdclass();
$lang->webhook->error->curl         = 'The php-curl extension is required.';
$lang->webhook->error->noDept       = 'No department selected. Please select the departments to sync first.';
$lang->webhook->error->url          = 'Webhook url must start with http:// or https://!';
$lang->webhook->error->requestError = 'Request error.';
