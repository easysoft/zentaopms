<?php
declare(strict_types=1);

if(!isset($config->programplan->create)) $config->programplan->create = new stdclass;
$config->programplan->create->form       = array();
$config->programplan->create->formFields = array('planIDList', 'names', 'PM', 'percents', 'attributes', 'acl', 'milestone', 'begin', 'end', 'realBegan', 'realEnd', 'desc', 'orders', 'type');
foreach($config->programplan->create->formFields as $field) $config->programplan->create->form[$field] = array('required' => false, 'type' => 'array');

if(!isset($config->programplan->edit)) $config->programplan->edit = new stdClass();
$config->programplan->edit->form = array();
$config->programplan->edit->form['parent']    = array('required' => false, 'type' => 'int',    'default' => 0);
$config->programplan->edit->form['name']      = array('required' => true,  'type' => 'string', 'default' => '');
$config->programplan->edit->form['code']      = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->edit->form['PM']        = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->edit->form['percent']   = array('required' => false, 'type' => 'float',  'default' => 0);
$config->programplan->edit->form['attribute'] = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->edit->form['acl']       = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->edit->form['begin']     = array('required' => false, 'type' => 'string', 'default' => '0000-00-00');
$config->programplan->edit->form['end']       = array('required' => false, 'type' => 'string', 'default' => '0000-00-00');
$config->programplan->edit->form['realBegan'] = array('required' => false, 'type' => 'string', 'default' => '0000-00-00');
$config->programplan->edit->form['realEnd']   = array('required' => false, 'type' => 'string', 'default' => '0000-00-00');
$config->programplan->edit->form['milestone'] = array('required' => false, 'type' => 'int',    'default' => 0);
$config->programplan->edit->form['output']    = array('required' => false, 'type' => 'array',  'default' => array());

$config->programplan->ajaxCustom = new stdClass();
$config->programplan->ajaxCustom->form = array();
$config->programplan->ajaxCustom->form['zooming']     = array('required' => false, 'type' => 'string');
$config->programplan->ajaxCustom->form['stageCustom'] = array('required' => false, 'type' => 'array');
$config->programplan->ajaxCustom->form['ganttFields'] = array('required' => false, 'type' => 'array');

/* Batch stages creation. */
global $app, $lang;
$app->loadLang('execution');
$app->loadLang('stage');
!isset($config->programplan->form) && $config->programplan->form = new stdClass();
$config->programplan->form->create = common::formConfig('programplan', 'create');
$config->programplan->form->create['planIDList'] = array('label' => '',                            'type' => 'array', 'control' => 'text',     'required' => false, 'default' => '');
$config->programplan->form->create['orders']     = array('label' => '',                            'type' => 'array', 'control' => 'text',     'required' => false, 'default' => '');
$config->programplan->form->create['type']       = array('label' => $lang->execution->method,      'type' => 'array', 'control' => 'picker',   'required' => true,  'default' => '',     'options' => $lang->execution->typeList);
$config->programplan->form->create['name']       = array('label' => $lang->nameAB,                 'type' => 'array', 'control' => 'text',     'required' => true,  'default' => '',     'base' => true);
$config->programplan->form->create['code']       = array('label' => $lang->code,                   'type' => 'array', 'control' => 'text',     'required' => false, 'default' => '',     'options' => array());
$config->programplan->form->create['PM']         = array('label' => $lang->programplan->PMAB,      'type' => 'array', 'control' => 'picker',   'required' => false, 'default' => '',     'options' => array());
$config->programplan->form->create['percent']    = array('label' => $lang->programplan->percent,   'type' => 'array', 'control' => 'number',   'required' => false, 'default' => 0,      'options' => array());
$config->programplan->form->create['attribute']  = array('label' => $lang->programplan->attribute, 'type' => 'array', 'control' => 'picker',   'required' => false, 'default' => 0,      'options' => $lang->stage->typeList);
$config->programplan->form->create['acl']        = array('label' => $lang->programplan->acl,       'type' => 'array', 'control' => 'picker',   'required' => false, 'default' => 'open', 'options' => $lang->execution->aclList);
$config->programplan->form->create['milestone']  = array('label' => $lang->programplan->milestone, 'type' => 'array', 'control' => 'radioList','required' => false, 'default' => 0,      'options' => $lang->programplan->milestoneList);
$config->programplan->form->create['begin']      = array('label' => $lang->programplan->begin,     'type' => 'array', 'control' => 'date',     'required' => true,  'default' => '');
$config->programplan->form->create['end']        = array('label' => $lang->programplan->end,       'type' => 'array', 'control' => 'date',     'required' => true,  'default' => '');
$config->programplan->form->create['realBegan']  = array('label' => $lang->programplan->realBegan, 'type' => 'array', 'control' => 'date',     'required' => false, 'default' => '');
$config->programplan->form->create['realEnd']    = array('label' => $lang->programplan->realEnd,   'type' => 'array', 'control' => 'date',     'required' => false, 'default' => '');
$config->programplan->form->create['desc']       = array('label' => $lang->programplan->desc,      'type' => 'array', 'control' => 'textarea', 'required' => false, 'default' => '');

$config->programplan->form->updateDateByGantt['id']        = array('required' => false, 'type' => 'int',    'default' => 0);
$config->programplan->form->updateDateByGantt['startDate'] = array('required' => false, 'type' => 'string', 'default' => null);
$config->programplan->form->updateDateByGantt['endDate']   = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->form->updateDateByGantt['type']      = array('required' => false, 'type' => 'string', 'default' => '');

$config->programplan->form->updateTaskOrderByGantt['id']    = array('required' => false, 'type' => 'string', 'default' => '');
$config->programplan->form->updateTaskOrderByGantt['tasks'] = array('required' => false, 'type' => 'array',  'default' => array());
