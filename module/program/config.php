<?php
$config->program = new stdclass();
$config->program->showAllProjects = 0;

$config->program->editor = new stdclass();
$config->program->editor->create   = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->edit     = array('id' => 'desc',    'tools' => 'simpleTools');
$config->program->editor->close    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->start    = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->activate = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->finish   = array('id' => 'comment', 'tools' => 'simpleTools');
$config->program->editor->suspend  = array('id' => 'comment', 'tools' => 'simpleTools');

$config->program->list = new stdclass();
$config->program->list->exportFields = 'id,name,code,template,product,status,begin,end,budget,PM,end,desc';

$config->program->create = new stdclass();
$config->program->edit   = new stdclass();
$config->program->create->requiredFields = 'name,begin,end';
$config->program->edit->requiredFields   = 'name,begin,end';

$config->program->sortFields        = new stdclass();
$config->program->sortFields->id    = 'id';
$config->program->sortFields->begin = 'begin';
$config->program->sortFields->end   = 'end';

global $lang;
$config->program->search['module']                   = 'program';
$config->program->search['fields']['name']           = $lang->program->name;
$config->program->search['fields']['status']         = $lang->program->status;
$config->program->search['fields']['desc']           = $lang->program->desc;
$config->program->search['fields']['PM']             = $lang->program->PM;
$config->program->search['fields']['openedDate']     = $lang->program->openedDate;
$config->program->search['fields']['begin']          = $lang->program->begin;
$config->program->search['fields']['end']            = $lang->program->end;
$config->program->search['fields']['openedBy']       = $lang->program->openedBy;
$config->program->search['fields']['lastEditedDate'] = $lang->program->lastEditedDate;
$config->program->search['fields']['realBegan']      = $lang->program->realBegin;
$config->program->search['fields']['realEnd']        = $lang->program->realEnd;
$config->program->search['fields']['closedDate']     = $lang->program->closedDate;

$config->program->search['params']['name']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->program->search['params']['status']         = array('operator' => '=',       'control' => 'select', 'values' => array('' => '') + $lang->program->statusList);
$config->program->search['params']['desc']           = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->program->search['params']['PM']             = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->program->search['params']['openedDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->program->search['params']['begin']          = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->program->search['params']['end']            = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->program->search['params']['openedBy']       = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->program->search['params']['lastEditedDate'] = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->program->search['params']['realBegan']      = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->program->search['params']['realEnd']        = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->program->search['params']['closedDate']     = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
