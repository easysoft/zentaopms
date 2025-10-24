<?php
global $lang, $app, $config;

$config->ai->actions = new stdclass();
$config->ai->actions->modelview     = array('mainActions' => array('modelenable', 'modeldisable', 'modeltestconnection'), 'suffixActions' => array('modeledit', 'modeldelete'));
$config->ai->actions->models        = array('modelenable|modeldisable', 'modeledit');
$config->ai->actions->assistants    = array('assistantpublish|assistantwithdraw', 'assistantedit');
$config->ai->actions->assistantview = array('mainActions' => array('assistantpublish', 'assistantwithdraw'), 'suffixActions' => array('assistantedit', 'assistantdelete'));
$config->ai->actions->prompts       = $config->edition != 'open' ? array('promptassignrole', 'promptaudit', 'promptedit', 'promptpublish', 'promptunpublish') : array('promptpublish', 'promptunpublish');
$config->ai->actions->promptview    = array('mainActions' => array('promptassignrole', 'promptaudit', 'promptpublish', 'promptunpublish'), 'suffixActions' => array('promptedit', 'promptdelete'));
$config->ai->actions->miniPrograms  = $config->edition == 'open' ?  array('publishminiprogram', 'unpublishminiprogram') : array('editminiprogram', 'testminiprogram', 'publishminiprogram', 'unpublishminiprogram', 'exportminiprogram');

$config->ai->actionList = array();
$config->ai->actionList['modelenable']['icon']             = 'magic';
$config->ai->actionList['modelenable']['text']             = $lang->ai->models->enable;
$config->ai->actionList['modelenable']['hint']             = $lang->ai->models->enable;
$config->ai->actionList['modelenable']['url']              = array('module' => 'ai', 'method' => 'modelenable', 'params' => 'modelID={id}');
$config->ai->actionList['modelenable']['data-app']         = $app->tab;
$config->ai->actionList['modelenable']['className']        = 'ajax-submit';
$config->ai->actionList['modeldisable']['icon']            = 'ban-circle';
$config->ai->actionList['modeldisable']['text']            = $lang->ai->models->disable;
$config->ai->actionList['modeldisable']['hint']            = $lang->ai->models->disable;
$config->ai->actionList['modeldisable']['url']             = 'javascript:confirmDisable("{id}")';
$config->ai->actionList['modeltestconnection']['icon']     = 'controls';
$config->ai->actionList['modeltestconnection']['text']     = $lang->ai->models->testConnection;
$config->ai->actionList['modeltestconnection']['hint']     = $lang->ai->models->testConnection;
$config->ai->actionList['modeltestconnection']['url']      = 'javascript:testConnection("{id}")';
$config->ai->actionList['modeledit']['icon']               = 'edit';
$config->ai->actionList['modeledit']['text']               = $lang->ai->models->edit;
$config->ai->actionList['modeledit']['hint']               = $lang->ai->models->edit;
$config->ai->actionList['modeledit']['url']                = array('module' => 'ai', 'method' => 'modeledit', 'params' => 'modelID={id}');
$config->ai->actionList['modeldelete']['icon']             = 'trash';
$config->ai->actionList['modeldelete']['text']             = $lang->delete;
$config->ai->actionList['modeldelete']['hint']             = $lang->delete;
$config->ai->actionList['modeldelete']['url']              = 'javascript:confirmDelete("{id}")';

$config->ai->actionList['assistantpublish']['icon']     = 'publish';
$config->ai->actionList['assistantpublish']['text']     = $lang->ai->assistant->publish;
$config->ai->actionList['assistantpublish']['hint']     = $lang->ai->assistant->publish;
$config->ai->actionList['assistantpublish']['url']      = 'javascript:confirmPublish("{id}")';
$config->ai->actionList['assistantwithdraw']['icon']    = 'ban-circle';
$config->ai->actionList['assistantwithdraw']['text']    = $lang->ai->assistant->withdraw;
$config->ai->actionList['assistantwithdraw']['hint']    = $lang->ai->assistant->withdraw;
$config->ai->actionList['assistantwithdraw']['url']     = 'javascript:confirmWithdraw("{id}")';
$config->ai->actionList['assistantedit']['icon']        = 'edit';
$config->ai->actionList['assistantedit']['text']        = $lang->ai->assistant->edit;
$config->ai->actionList['assistantedit']['hint']        = $lang->ai->assistant->edit;
$config->ai->actionList['assistantedit']['url']         = array('module' => 'ai', 'method' => 'assistantedit', 'params' => 'assistantID={id}');
$config->ai->actionList['assistantdelete']['icon']      = 'trash';
$config->ai->actionList['assistantdelete']['text']      = $lang->delete;
$config->ai->actionList['assistantdelete']['hint']      = $lang->delete;
$config->ai->actionList['assistantdelete']['url']       = 'javascript:confirmDelete("{id}")';

$config->ai->actionList['promptassignrole']['icon'] = 'design';
$config->ai->actionList['promptassignrole']['text'] = $lang->ai->prompts->action->design;
$config->ai->actionList['promptassignrole']['hint'] = $lang->ai->prompts->action->design;
$config->ai->actionList['promptassignrole']['url']  = array('module' => 'ai', 'method' => 'promptassignrole', 'params' => 'prompt={id}');

$config->ai->actionList['promptaudit']['icon'] = 'menu-backend ';
$config->ai->actionList['promptaudit']['text'] = $lang->ai->prompts->action->test;
$config->ai->actionList['promptaudit']['hint'] = $lang->ai->prompts->action->test;
$config->ai->actionList['promptaudit']['url']  = 'javascript:getTestingLocation("{id}")';

$config->ai->actionList['promptedit']['icon']        = 'edit';
$config->ai->actionList['promptedit']['text']        = $lang->ai->prompts->action->edit;
$config->ai->actionList['promptedit']['hint']        = $lang->ai->prompts->action->edit;
$config->ai->actionList['promptedit']['url']         = array('module' => 'ai', 'method' => 'promptedit', 'params' => 'prompt={id}');
$config->ai->actionList['promptedit']['data-toggle'] = 'modal';
$config->ai->actionList['promptedit']['data-size']   = 'sm';

$config->ai->actionList['promptpublish']['icon']      = 'publish';
$config->ai->actionList['promptpublish']['text']      = $lang->ai->prompts->action->publish;
$config->ai->actionList['promptpublish']['hint']      = $lang->ai->prompts->action->publish;
$config->ai->actionList['promptpublish']['url']       = array('module' => 'ai', 'method' => 'promptpublish', 'params' => 'prompt={id}');
$config->ai->actionList['promptpublish']['className'] = 'ajax-submit';

$config->ai->actionList['promptunpublish']['icon']         = 'ban';
$config->ai->actionList['promptunpublish']['text']         = $lang->ai->prompts->action->unpublish;
$config->ai->actionList['promptunpublish']['hint']         = $lang->ai->prompts->action->unpublish;
$config->ai->actionList['promptunpublish']['url']          = array('module' => 'ai', 'method' => 'promptunpublish', 'params' => 'prompt={id}');
$config->ai->actionList['promptunpublish']['className']    = 'ajax-submit';
$config->ai->actionList['promptunpublish']['data-confirm'] = array('message' => $lang->ai->prompts->action->draftConfirm, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->ai->actionList['promptdelete']['icon']         = 'trash';
$config->ai->actionList['promptdelete']['text']         = $lang->ai->prompts->action->delete;
$config->ai->actionList['promptdelete']['hint']         = $lang->ai->prompts->action->delete;
$config->ai->actionList['promptdelete']['url']          = array('module' => 'ai', 'method' => 'promptdelete', 'params' => 'prompt={id}');
$config->ai->actionList['promptdelete']['className']    = 'ajax-submit';
$config->ai->actionList['promptdelete']['data-confirm'] = array('message' => $lang->ai->prompts->action->deleteConfirm, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->ai->actionList['editminiprogram']['icon'] = 'edit';
$config->ai->actionList['editminiprogram']['text'] = $lang->ai->prompts->action->edit;
$config->ai->actionList['editminiprogram']['hint'] = $lang->ai->prompts->action->edit;
$config->ai->actionList['editminiprogram']['url']  = array('module' => 'ai', 'method' => 'editminiprogram', 'params' => 'appID={id}');

$config->ai->actionList['testminiprogram']['icon']        = 'menu-backend';
$config->ai->actionList['testminiprogram']['text']        = $lang->ai->prompts->action->test;
$config->ai->actionList['testminiprogram']['hint']        = $lang->ai->prompts->action->test;
$config->ai->actionList['testminiprogram']['url']         = array('module' => 'ai', 'method' => 'testminiprogram', 'params' => 'appID={id}');
$config->ai->actionList['testminiprogram']['data-toggle'] = 'modal';
$config->ai->actionList['testminiprogram']['data-size']   = 'full';

$config->ai->actionList['publishminiprogram']['icon']         = 'publish';
$config->ai->actionList['publishminiprogram']['text']         = $lang->ai->prompts->action->publish;
$config->ai->actionList['publishminiprogram']['hint']         = $lang->ai->prompts->action->publish;
$config->ai->actionList['publishminiprogram']['url']          = array('module' => 'ai', 'method' => 'publishminiprogram', 'params' => 'prompt={id}');
$config->ai->actionList['publishminiprogram']['className']    = 'ajax-submit';
$config->ai->actionList['publishminiprogram']['data-confirm'] = array('message' => $lang->ai->miniPrograms->publishTip, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->ai->actionList['unpublishminiprogram']['icon']         = 'ban-circle';
$config->ai->actionList['unpublishminiprogram']['text']         = $lang->ai->prompts->action->unpublish;
$config->ai->actionList['unpublishminiprogram']['hint']         = $lang->ai->prompts->action->unpublish;
$config->ai->actionList['unpublishminiprogram']['url']          = array('module' => 'ai', 'method' => 'unpublishminiprogram', 'params' => 'prompt={id}');
$config->ai->actionList['unpublishminiprogram']['className']    = 'ajax-submit';
$config->ai->actionList['unpublishminiprogram']['data-confirm'] = array('message' => $lang->ai->miniPrograms->disableTip, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->ai->actionList['exportminiprogram']['icon']      = 'export';
$config->ai->actionList['exportminiprogram']['text']      = $lang->ai->export;
$config->ai->actionList['exportminiprogram']['hint']      = $lang->ai->export;
$config->ai->actionList['exportminiprogram']['url']       = array('module' => 'ai', 'method' => 'exportminiprogram', 'params' => 'prompt={id}');
$config->ai->actionList['exportminiprogram']['className'] = 'ajax-submit';
