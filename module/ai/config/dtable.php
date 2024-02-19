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
