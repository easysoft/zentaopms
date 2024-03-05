<?php
global $lang, $app;

$config->ai->actions = new stdclass();
$config->ai->actions->modelview = array('mainActions' => array('modelenable', 'modeldisable', 'modeltestconnection'), 'suffixActions' => array('modeledit', 'modeldelete'));
$config->ai->actions->models    = array('modelenable|modeldisable', 'modeledit');

$config->ai->actionList = array();
$config->ai->actionList['modelenable']['icon']             = 'magic';
$config->ai->actionList['modelenable']['text']             = $lang->ai->models->enable;
$config->ai->actionList['modelenable']['hint']             = $lang->ai->models->enable;
$config->ai->actionList['modelenable']['url']              = array('module' => 'ai', 'method' => 'modelenable', 'params' => 'modelID={id}');
$config->ai->actionList['modelenable']['data-app']         = $app->tab;
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
