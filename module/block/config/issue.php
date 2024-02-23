<?php
global $lang, $app;
$app->loadLang('issue');

$config->block->issue = new stdclass();
$config->block->issue->dtable = new stdclass();
$config->block->issue->dtable->fieldList = array();
$config->block->issue->dtable->fieldList['id']       = array('name' => 'id',       'title' => $lang->idAB,            'type' => 'id' ,      'sort' => 'number');
$config->block->issue->dtable->fieldList['title']    = array('name' => 'title',    'title' => $lang->issue->title,    'type' => 'title',    'sort' => true,  'flex' => 1, 'link' => array('module' => 'issue', 'method' => 'view', 'params' => 'issueID={id}'));
$config->block->issue->dtable->fieldList['type']     = array('name' => 'type',     'title' => $lang->issue->type,     'type' => 'category', 'sort' => true, 'map' => $lang->issue->typeList);
$config->block->issue->dtable->fieldList['pri']      = array('name' => 'pri',      'title' => $lang->issue->priAB,    'type' => 'pri',      'sort' => true);
$config->block->issue->dtable->fieldList['severity'] = array('name' => 'severity', 'title' => $lang->issue->severity, 'type' => 'severity', 'sort' => true);
$config->block->issue->dtable->fieldList['owner']    = array('name' => 'owner',    'title' => $lang->issue->owner,    'type' => 'user',     'sort' => true);
$config->block->issue->dtable->fieldList['status']   = array('name' => 'status',   'title' => $lang->issue->status,   'type' => 'status',   'sort' => true, 'statusMap' => $lang->issue->statusList);

$config->block->issue->dtable->short = new stdclass();
$config->block->issue->dtable->short->fieldList['id']       = $config->block->issue->dtable->fieldList['id'];
$config->block->issue->dtable->short->fieldList['title']    = $config->block->issue->dtable->fieldList['title'];
$config->block->issue->dtable->short->fieldList['pri']      = $config->block->issue->dtable->fieldList['pri'];
$config->block->issue->dtable->short->fieldList['severity'] = $config->block->issue->dtable->fieldList['severity'];
