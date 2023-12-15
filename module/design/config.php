<?php
global $lang;
$config->design = new stdclass();
$config->design->editor = new stdclass();
$config->design->editor->create   = array('id' => 'desc',    'tools' => 'simpleTools');
$config->design->editor->edit     = array('id' => 'desc',    'tools' => 'simpleTools');
$config->design->editor->assignto = array('id' => 'comment', 'tools' => 'simpleTools');

$config->design->create      = new stdclass();
$config->design->batchcreate = new stdclass();
$config->design->edit        = new stdclass();
$config->design->view        = new stdclass();
$config->design->create->requiredFields      = 'name,type';
$config->design->batchcreate->requiredFields = 'name,type';
$config->design->edit->requiredFields        = 'name,type';

$config->design->affectedFixedNum = 7;

global $lang;
$config->design->search['module']                = 'design';
$config->design->search['fields']['id']          = $lang->design->id;
$config->design->search['fields']['type']        = $lang->design->type;
$config->design->search['fields']['name']        = $lang->design->name;
$config->design->search['fields']['commit']      = $lang->design->submission;
$config->design->search['fields']['createdBy']   = $lang->design->createdBy;
$config->design->search['fields']['createdDate'] = $lang->design->createdDate;
$config->design->search['fields']['assignedTo']  = $lang->design->assignedTo;
$config->design->search['fields']['story']       = $lang->design->story;

$config->design->search['params']['type']        = array('operator' => '=', 'control' => 'select',  'values' => $lang->design->typeList);
$config->design->search['params']['name']        = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->design->search['params']['commit']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->design->search['params']['createdBy']   = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->design->search['params']['createdDate'] = array('operator' => '=', 'control' => 'date',  'values' => '');
$config->design->search['params']['assignedTo']  = array('operator' => '=', 'control' => 'select',  'values' => 'users');
$config->design->search['params']['story']       = array('operator' => '=', 'control' => 'select',  'values' => '');

$config->design->actionList['edit']['icon'] = 'alter';
$config->design->actionList['edit']['hint'] = $lang->design->edit;
$config->design->actionList['edit']['url']  = helper::createLink('design', 'edit', 'designID={id}');

$config->design->actionList['viewCommit']['icon']          = 'list-alt';
$config->design->actionList['viewCommit']['hint']          = $lang->design->viewCommit;
$config->design->actionList['viewCommit']['url']           = helper::createLink('design', 'viewCommit', 'designID={id}');
$config->design->actionList['viewCommit']['data-toggle']   = 'modal';
$config->design->actionList['viewCommit']['data-position'] = 'center';
$config->design->actionList['viewCommit']['data-id']       = 'viewCommitModal';

$config->design->actionList['delete']['icon']         = 'trash';
$config->design->actionList['delete']['hint']         = $lang->design->delete;
$config->design->actionList['delete']['url']          = helper::createLink('design', 'delete', 'designID={id}');
$config->design->actionList['delete']['className']    = 'ajax-submit';
$config->design->actionList['delete']['data-confirm'] = $lang->design->confirmDelete;

$config->design->actionList['assignTo']['icon']        = 'hand-right';
$config->design->actionList['assignTo']['text']        = $lang->design->assignTo;
$config->design->actionList['assignTo']['url']         = helper::createLink('design', 'assignTo', 'designID={id}');
$config->design->actionList['assignTo']['data-toggle'] = 'modal';

$config->design->actionList['linkCommit']['icon']          = 'link';
$config->design->actionList['linkCommit']['text']          = $lang->design->linkCommit;
$config->design->actionList['linkCommit']['url']           = helper::createLink('design', 'linkCommit', 'designID={id}');
$config->design->actionList['linkCommit']['data-toggle']   = 'modal';
$config->design->actionList['linkCommit']['data-position'] = 'center';
$config->design->actionList['linkCommit']['data-size']     = 'lg';

$config->design->view->operateList['main']   = array('assignTo', 'linkCommit');
$config->design->view->operateList['common'] = array('edit', 'delete');
