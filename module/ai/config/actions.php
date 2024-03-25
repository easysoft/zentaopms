<?php
global $lang, $app;

$config->ai->actions = new stdclass();
$config->ai->actions->modelview  = array('mainActions' => array('modelenable', 'modeldisable', 'modeltestconnection'), 'suffixActions' => array('modeledit', 'modeldelete'));
$config->ai->actions->models     = array('modelenable|modeldisable', 'modeledit');
$config->ai->actions->assistants = array('assistantpublish|assistantwithdraw', 'assistantedit');
$config->ai->actions->assistantview = array('mainActions' => array('assistantpublish', 'assistantwithdraw'), 'suffixActions' => array('assistantedit', 'assistantdelete'));

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
$config->ai->actionList['assistantpublish']['text']     = $lang->ai->assistants->publish;
$config->ai->actionList['assistantpublish']['hint']     = $lang->ai->assistants->publish;
$config->ai->actionList['assistantpublish']['url']      = array('module' => 'ai', 'method' => 'assistantpublish', 'params' => 'assistantID={id}');
$config->ai->actionList['assistantwithdraw']['icon']    = 'ban-circle';
$config->ai->actionList['assistantwithdraw']['text']    = $lang->ai->assistants->withdraw;
$config->ai->actionList['assistantwithdraw']['hint']    = $lang->ai->assistants->withdraw;
$config->ai->actionList['assistantwithdraw']['url']     = 'javascript:confirmWithdraw("{id}")';
$config->ai->actionList['assistantedit']['icon']        = 'edit';
$config->ai->actionList['assistantedit']['text']        = $lang->ai->assistants->edit;
$config->ai->actionList['assistantedit']['hint']        = $lang->ai->assistants->edit;
$config->ai->actionList['assistantedit']['url']         = array('module' => 'ai', 'method' => 'assistantedit', 'params' => 'assistantID={id}');
$config->ai->actionList['assistantdelete']['icon']      = 'trash';
$config->ai->actionList['assistantdelete']['text']      = $lang->delete;
$config->ai->actionList['assistantdelete']['hint']      = $lang->delete;
$config->ai->actionList['assistantdelete']['url']       = 'javascript:confirmDelete("{id}")';
