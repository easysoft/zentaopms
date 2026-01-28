<?php
$lang->webhook->common     = 'Webhook';
$lang->webhook->list       = 'Hook Liste';
$lang->webhook->api        = 'API';
$lang->webhook->entry      = 'Eintrag';
$lang->webhook->log        = 'Log';
$lang->webhook->bind       = 'Bind User';
$lang->webhook->chooseDept = 'Choose department';
$lang->webhook->assigned   = 'Augeordnet an';
$lang->webhook->setting    = 'Einstellungen';

$lang->webhook->logAction = 'Webhook Log';

$lang->webhook->browse = 'Durchsuchen';
$lang->webhook->create = 'Erstellen';
$lang->webhook->edit   = 'Bearbeiten';
$lang->webhook->delete = 'Löschen';

$lang->webhook->id          = 'ID';
$lang->webhook->type        = 'Typ';
$lang->webhook->name        = 'Name';
$lang->webhook->url         = 'Webhook Adresse';
$lang->webhook->domain      = 'Zentao Domain';
$lang->webhook->contentType = 'Content Type';
$lang->webhook->sendType    = 'Sendungs Typ';
$lang->webhook->secret      = 'Secret';
$lang->webhook->product     = "{$lang->productCommon}";
$lang->webhook->execution   = "{$lang->execution->common}";
$lang->webhook->params      = 'Parameter';
$lang->webhook->action      = 'Aktion';
$lang->webhook->desc        = 'Beschreibung';
$lang->webhook->createdBy   = 'Ersteller';
$lang->webhook->createdDate = 'Erstellt am';
$lang->webhook->editedby    = 'Bearbeitet von';
$lang->webhook->editedDate  = 'Bearbeitet am';
$lang->webhook->date        = 'Datum';
$lang->webhook->data        = 'Daten';
$lang->webhook->result      = 'Ergebnis';
$lang->webhook->products    = $lang->productCommon;
$lang->webhook->executions  = $lang->execution->common;
$lang->webhook->actions     = 'Log';
$lang->webhook->deleted     = 'Deleted';
$lang->webhook->approval    = 'Approval send message';

$lang->webhook->typeList['']            = '';
$lang->webhook->typeList['dinggroup']   = 'Dingding Robot';
$lang->webhook->typeList['dinguser']    = 'Dingding Notifier';
$lang->webhook->typeList['wechatgroup'] = 'Enterprise WeChat Robot';
$lang->webhook->typeList['wechatuser']  = 'Enterprise WeChat Notifier';
$lang->webhook->typeList['feishugroup'] = 'Feishu Robot';
$lang->webhook->typeList['feishuuser']  = 'Feishu Notifier';
$lang->webhook->typeList['default']     = 'Others';

$lang->webhook->sendTypeList['sync']  = 'Synchron';
$lang->webhook->sendTypeList['async'] = 'Asynchron';

$lang->webhook->dingAgentId     = 'AgentID';
$lang->webhook->dingAppKey      = 'AppKey';
$lang->webhook->dingAppSecret   = 'AppSecret';
$lang->webhook->dingUserid      = 'Ding Userid';
$lang->webhook->dingBindStatus  = 'Bind Status';
$lang->webhook->chooseDeptAgain = 'Rechoose department';

$lang->webhook->wechatCorpId     = 'Corp ID';
$lang->webhook->wechatCorpSecret = 'Corp Secret';
$lang->webhook->wechatAgentId    = 'Agent ID';
$lang->webhook->wechatUserid     = 'Wechat Userid';
$lang->webhook->wechatBindStatus = 'Bind Status';

$lang->webhook->feishuAppId       = 'Feishu App ID';
$lang->webhook->feishuAppSecret   = 'Feishu App Secret';
$lang->webhook->feishuUserid      = 'Feishu Users';
$lang->webhook->feishuBindStatus  = 'Feishu Bind Status';

$lang->webhook->zentaoUser  = 'Zentao User';

$lang->webhook->dingBindStatusList['0'] = 'No';
$lang->webhook->dingBindStatusList['1'] = 'Yes';

$lang->webhook->paramsList['objectType'] = 'Objekt Typ';
$lang->webhook->paramsList['objectID']   = 'Objekt ID';
$lang->webhook->paramsList['product']    = "{$lang->productCommon}";
$lang->webhook->paramsList['execution']  = "{$lang->execution->common}";
$lang->webhook->paramsList['action']     = 'Aktion';
$lang->webhook->paramsList['actor']      = 'Actor';
$lang->webhook->paramsList['date']       = 'Datum';
$lang->webhook->paramsList['comment']    = 'Kommentar';
$lang->webhook->paramsList['text']       = 'Aktionsbeschreibung';

$lang->webhook->confirmDelete = 'Möchten Sie diesen Hook löschen?';
$lang->webhook->friendlyTips  = 'Friendly reminder: Click on a department to expand the sub-departments under the department.';
$lang->webhook->loadPrompt    = 'There is a lot of data and the loading is slow, please wait.';

$lang->webhook->trimWords = '';

$lang->webhook->note = new stdClass();
$lang->webhook->note->async     = 'Wenn der Sendungstyp asynchron ist, wird Cron benötigt.';
$lang->webhook->note->bind      = 'Bind User is only required for Dingding Notifier.';
$lang->webhook->note->product   = "Alle Aktionen triggern den Hook wenn das {$lang->productCommon} leer ist, andernsfalls werden nur Aktionen des {$lang->productCommon} ausgelöst.";
$lang->webhook->note->execution = "Alle Aktionen triggern den Hook wenn das {$lang->execution->common} leer ist, andernsfalls werden nur Aktionen des {$lang->execution->common} ausgelöst.";

$lang->webhook->note->dingHelp   = " <a href='http://www.zentao.net/book/zentaopmshelp/358.html' target='_blank'><i class='icon-help'></i></a>";
$lang->webhook->note->wechatHelp = " <a href='http://www.zentao.net/book/zentaopmshelp/367.html' target='_blank'><i class='icon-help'></i></a>";

$lang->webhook->note->typeList['bearychat'] = 'Fügen Sie einen ZenTao bot in bearychat ein um die Adresse des Webhooks zu erhalten.';
$lang->webhook->note->typeList['dingding']  = 'Fügen Sie einen eigenen bot in dingding ein um die Adresse des Webhooks zu erhalten.';
$lang->webhook->note->typeList['weixin']    = 'Add a customized bot in WeChat and get the webhook url.';
$lang->webhook->note->typeList['default']   = 'Webhookadresse on anderen erhalten.';

$lang->webhook->error               = new stdclass();
$lang->webhook->error->curl         = 'Laden Sie php-curl in der php.ini.';
$lang->webhook->error->noDept       = 'There is no department selected. Please choose department first.';
$lang->webhook->error->url          = 'Webhook url must start with http:// or https://!';
$lang->webhook->error->requestError = 'Request error!';
