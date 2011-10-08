<?php
global $lang, $app;
$app->loadLang('story');
$config->product->search['module']                   = 'story';
$config->product->search['fields']['title']          = $lang->story->title;
$config->product->search['fields']['id']             = $lang->story->id;
$config->product->search['fields']['keywords']       = $lang->story->keywords;
$config->product->search['fields']['pri']            = $lang->story->pri;
$config->product->search['fields']['plan']           = $lang->story->plan;
$config->product->search['fields']['origin']         = $lang->story->origin;
$config->product->search['fields']['assignedTo']     = $lang->story->assignedTo;
$config->product->search['fields']['product']        = $lang->story->product;
$config->product->search['fields']['openedBy']       = $lang->story->openedBy;
$config->product->search['fields']['reviewedBy']     = $lang->story->reviewedBy;
$config->product->search['fields']['closedBy']       = $lang->story->closedBy;
$config->product->search['fields']['lastEditedBy']   = $lang->story->lastEditedBy;
$config->product->search['fields']['status']         = $lang->story->status;
$config->product->search['fields']['stage']          = $lang->story->stage;
$config->product->search['fields']['closedReason']   = $lang->story->closedReason;

$config->product->search['params']['title']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->search['params']['keywords']     = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->product->search['params']['pri']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->priList);
$config->product->search['params']['plan']         = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['origin']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->originList);
$config->product->search['params']['product']      = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->product->search['params']['assignedTo']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['resolvedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['openedBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['reviewedBy']   = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->product->search['params']['closedBy']     = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['lastEditedBy'] = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->product->search['params']['status']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->statusList);
$config->product->search['params']['stage']        = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->stageList);
$config->product->search['params']['closedReason'] = array('operator' => '=',       'control' => 'select', 'values' => $lang->story->reasonList);
$config->product->search['params']['mailto']       = array('operator' => 'include', 'control' => 'select', 'values' => 'users');

$config->product->create->requiredFields = 'name,code';
$config->product->edit->requiredFields   = 'name,code';

$config->product->editor->create = array('id' => 'desc', 'tools' => 'simpleTools');
$config->product->editor->edit   = array('id' => 'desc', 'tools' => 'simpleTools');
