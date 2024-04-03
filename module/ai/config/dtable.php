<?php
global $lang;

$config->ai->dtable = new stdclass();

$config->ai->dtable->models = array();
$config->ai->dtable->models['id']          = array('name' => 'id', 'title' => $lang->idAB, 'type' => 'id', 'sortType' => true);
$config->ai->dtable->models['name']        = array('name' => 'name', 'title' => $lang->ai->models->name, 'flex' => 1, 'type' => 'link', 'link' => array('module' => 'ai', 'method' => 'modelview', 'params' => 'modelID={id}'));
$config->ai->dtable->models['status']      = array('name' => 'enabled', 'title' => $lang->statusAB, 'flex' => 'auto', 'type' => 'status', 'statusMap' => $lang->ai->models->statusList, 'minWidth' => 80);
$config->ai->dtable->models['createdDate'] = array('name' => 'createdDate', 'title' => $lang->ai->models->createdDate, 'flex' => 'auto', 'type' => 'datetime', 'minWidth' => 120);
$config->ai->dtable->models['vendor']      = array('name' => 'vendor', 'title' => $lang->ai->models->vendor, 'flex' => 'auto', 'type' => 'text', 'sortType' => true, 'minWidth' => 120, 'align' => 'center');
$config->ai->dtable->models['usesProxy']   = array('name' => 'usesProxy', 'title' => $lang->ai->models->usesProxy, 'type' => 'status', 'statusMap' => $lang->ai->models->proxyStatusList, 'minWidth' => 120);
$config->ai->dtable->models['actions']     = array('title' => $lang->actions, 'type' => 'actions', 'width' => 100, 'fixed' => 'right', 'align' => 'center', 'menu' => $config->ai->actions->models, 'list' => $config->ai->actionList);

$config->ai->dtable->assistants                  = array();
$config->ai->dtable->assistants['id']            = array('name' => 'id', 'title' => $lang->idAB, 'type' => 'id', 'sortType' => true);
$config->ai->dtable->assistants['name']          = array('name' => 'name', 'title' => $lang->ai->assistants->name, 'flex' => 1, 'sortType' => true, 'type' => 'link', 'link' => array('module' => 'ai', 'method' => 'assistantview', 'params' => 'assistantID={id}'));
$config->ai->dtable->assistants['model']         = array('name' => 'modelId', 'title' => $lang->ai->assistants->refModel, 'flex' => 'auto', 'type' => 'text', 'sortType' => true, 'minWidth' => 120,);
$config->ai->dtable->assistants['status']        = array('name' => 'enabled', 'title' => $lang->statusAB, 'flex' => 'auto', 'type' => 'status', 'statusMap' => $lang->ai->assistants->statusList, 'minWidth' => 80);
$config->ai->dtable->assistants['createdDate']   = array('name' => 'createdDate', 'title' => $lang->ai->assistants->createdDate, 'flex' => 'auto', 'type' => 'datetime', 'minWidth' => 120);
$config->ai->dtable->assistants['publishedDate'] = array('name' => 'publishedDate', 'title' => $lang->ai->assistants->publishedDate, 'flex' => 'auto', 'type' => 'datetime', 'minWidth' => 120,);
$config->ai->dtable->assistants['actions']       = array('title' => $lang->actions, 'type' => 'actions', 'width' => 100, 'fixed' => 'right', 'align' => 'center', 'menu' => $config->ai->actions->assistants, 'list' => $config->ai->actionList);