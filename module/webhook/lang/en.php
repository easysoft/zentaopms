<?php
$lang->webhook->common   = 'Webhook';
$lang->webhook->list     = 'Hook List';
$lang->webhook->api      = 'API';
$lang->webhook->entry    = 'Entry';
$lang->webhook->log      = 'Log';
$lang->webhook->assigned = 'Assigned To';
$lang->webhook->setting  = 'Setting';

$lang->webhook->browse = 'Browse';
$lang->webhook->create = 'Create';
$lang->webhook->edit   = 'Edit';
$lang->webhook->delete = 'Delete';

$lang->webhook->id          = 'ID';
$lang->webhook->type        = 'Type';
$lang->webhook->name        = 'Name';
$lang->webhook->url         = 'Webhook Address';
$lang->webhook->domain      = 'Zentao Domain';
$lang->webhook->contentType = 'Content Type';
$lang->webhook->sendType    = 'Send Type';
$lang->webhook->product     = "{$lang->productCommon}";
$lang->webhook->project     = "{$lang->projectCommon}";
$lang->webhook->params      = 'Params';
$lang->webhook->action      = 'Action';
$lang->webhook->desc        = 'Desc';
$lang->webhook->createdBy   = 'Created By';
$lang->webhook->createdDate = 'Created Date';
$lang->webhook->editedby    = 'Edited By';
$lang->webhook->editedDate  = 'Edited Date';
$lang->webhook->date        = 'Date';
$lang->webhook->data        = 'Data';
$lang->webhook->result      = 'Result';

$lang->webhook->typeList['bearychat'] = 'Bearychat';
$lang->webhook->typeList['dingding']  = 'Dingding';
$lang->webhook->typeList['default']   = 'Default';

$lang->webhook->sendTypeList['sync']  = 'Synchronization';
$lang->webhook->sendTypeList['async'] = 'Asynchronous';

$lang->webhook->paramsList['objectType'] = 'Object Type';
$lang->webhook->paramsList['objectID']   = 'Object ID';
$lang->webhook->paramsList['product']    = "{$lang->productCommon}";
$lang->webhook->paramsList['project']    = "{$lang->projectCommon}";
$lang->webhook->paramsList['action']     = 'Action';
$lang->webhook->paramsList['actor']      = 'Actor';
$lang->webhook->paramsList['date']       = 'Date';
$lang->webhook->paramsList['comment']    = 'Comment';
$lang->webhook->paramsList['text']       = 'Action Desc';

$lang->webhook->confirmDelete = 'Are your sure delete this hook?';

$lang->webhook->trimWords = '';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async   = 'If the send type is asynchronous, need open cron.';
$lang->webhook->note->product = "All actions will trigger the hook if the {$lang->productCommon} is empty, else only actions of the {$lang->productCommon} will trigger it.";
$lang->webhook->note->project = "All actions will trigger the hook if the {$lang->projectCommon} is empty, else only actions of the {$lang->projectCommon} will trigger it.";

$lang->webhook->note->typeList['bearychat'] = 'Add a zentao bot in bearychat and get the webhook address.';
$lang->webhook->note->typeList['dingding']  = 'Add a customed bot in dingding and get the webhook address.';
$lang->webhook->note->typeList['default']   = 'Get webhook address from others';

$lang->webhook->error = new stdclass();
$lang->webhook->error->curl = 'Load php-curl in php.ini.';
