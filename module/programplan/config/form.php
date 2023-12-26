<?php
/* Batch stages creation. */
global $app, $lang;
$app->loadLang('execution');
$app->loadLang('stage');
$config->programplan->form = new stdClass();
$config->programplan->form->create = common::formConfig('programplan', 'create');
$config->programplan->form->create['id']         = array('label' => '',                            'type' => 'int',    'control' => 'text',     'required' => false, 'default' => 0);
$config->programplan->form->create['order']      = array('label' => '',                            'type' => 'int',    'control' => 'text',     'required' => false, 'default' => 0);
$config->programplan->form->create['type']       = array('label' => $lang->execution->method,      'type' => 'string', 'control' => 'picker',   'required' => true,  'default' => 'stage', 'options' => $lang->execution->typeList);
$config->programplan->form->create['name']       = array('label' => $lang->nameAB,                 'type' => 'string', 'control' => 'text',     'required' => true,  'default' => '',     'base' => true, 'filter' => 'trim');
$config->programplan->form->create['code']       = array('label' => $lang->code,                   'type' => 'string', 'control' => 'text',     'required' => true,  'default' => '',     'filter' => 'trim');
$config->programplan->form->create['PM']         = array('label' => $lang->programplan->PMAB,      'type' => 'string', 'control' => 'picker',   'required' => false, 'default' => '',     'options' => '');
$config->programplan->form->create['percent']    = array('label' => $lang->programplan->percent,   'type' => 'float',  'control' => 'text',     'required' => false, 'default' => 0);
$config->programplan->form->create['attribute']  = array('label' => $lang->programplan->attribute, 'type' => 'string', 'control' => 'picker',   'required' => false, 'default' => 0,      'options' => $lang->stage->typeList);
$config->programplan->form->create['acl']        = array('label' => $lang->programplan->acl,       'type' => 'string', 'control' => 'picker',   'required' => false, 'default' => 'open', 'options' => $lang->execution->aclList);
$config->programplan->form->create['begin']      = array('label' => $lang->programplan->begin,     'type' => 'date',   'control' => 'date',     'required' => true,  'default' => null);
$config->programplan->form->create['end']        = array('label' => $lang->programplan->end,       'type' => 'date',   'control' => 'date',     'required' => true,  'default' => null);
$config->programplan->form->create['realBegan']  = array('label' => $lang->programplan->realBegan, 'type' => 'date',   'control' => 'date',     'required' => false, 'default' => null);
$config->programplan->form->create['realEnd']    = array('label' => $lang->programplan->realEnd,   'type' => 'date',   'control' => 'date',     'required' => false, 'default' => null);
$config->programplan->form->create['milestone']  = array('label' => $lang->programplan->milestone, 'type' => 'int',    'control' => 'radioList','required' => false, 'default' => 0,      'options' => $lang->programplan->milestoneList);
$config->programplan->form->create['desc']       = array('label' => $lang->programplan->desc,      'type' => 'string', 'control' => 'textarea', 'required' => false, 'default' => '', 'filter' => 'trim');
$config->programplan->form->create['output']     = array('label' => '',                            'type' => 'string', 'control' => 'text',     'required' => false, 'default' => '');

$config->programplan->form->edit = common::formConfig('programplan', 'edit');
$config->programplan->form->edit['parent']    = array('required' => false, 'type' => 'int',    'default' => 0);
$config->programplan->form->edit['name']      = array('required' => true,  'type' => 'string', 'default' => '');
$config->programplan->form->edit['code']      = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->form->edit['PM']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->form->edit['percent']   = array('required' => false, 'type' => 'float',  'default' => 0);
$config->programplan->form->edit['attribute'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->form->edit['acl']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->form->edit['begin']     = array('required' => false, 'type' => 'string', 'default' => null);
$config->programplan->form->edit['end']       = array('required' => false, 'type' => 'string', 'default' => null);
$config->programplan->form->edit['realBegan'] = array('required' => false, 'type' => 'string', 'default' => null);
$config->programplan->form->edit['realEnd']   = array('required' => false, 'type' => 'string', 'default' => null);
$config->programplan->form->edit['milestone'] = array('required' => false, 'type' => 'int',    'default' => 0);
$config->programplan->form->edit['output']    = array('required' => false, 'type' => 'array',  'default' => '', 'filter' => 'join');

$config->programplan->form->ajaxCustom = array();
$config->programplan->form->ajaxCustom['zooming']     = array('required' => false, 'type' => 'string');
$config->programplan->form->ajaxCustom['stageCustom'] = array('required' => false, 'type' => 'array');
$config->programplan->form->ajaxCustom['ganttFields'] = array('required' => false, 'type' => 'array');

$config->programplan->form->updateDateByGantt['id']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->programplan->form->updateDateByGantt['startDate'] = array('required' => false, 'type' => 'string', 'default' => null);
$config->programplan->form->updateDateByGantt['endDate']   = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->form->updateDateByGantt['type']      = array('required' => false, 'type' => 'string', 'default' => '');

$config->programplan->form->updateTaskOrderByGantt['id']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->form->updateTaskOrderByGantt['tasks'] = array('required' => false, 'type' => 'array',  'default' => array());

if(empty($config->setCode)) unset($config->programplan->form->create['code']);
