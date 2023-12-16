<?php
$config->project->form = new stdclass();

$config->project->form->create    = array();
$config->project->form->edit      = array();
$config->project->form->batchedit = array();
$config->project->form->close     = array();
$config->project->form->start     = array();
$config->project->form->suspend   = array();
$config->project->form->activate  = array();

$config->project->form->manageProducts = array();

$config->project->form->create['parent']     = array('type' => 'int',    'required' => false, 'default' => 0);
$config->project->form->create['name']       = array('type' => 'string', 'required' => true,  'filter'  => 'trim');
$config->project->form->create['multiple']   = array('type' => 'int',    'required' => false, 'default' => 1);
$config->project->form->create['hasProduct'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['PM']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['budget']     = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['budgetUnit'] = array('type' => 'string', 'required' => false, 'default' => 'CNY');
$config->project->form->create['begin']      = array('type' => 'date',   'required' => true);
$config->project->form->create['end']        = array('type' => 'date',   'required' => true,  'default' => null);
$config->project->form->create['days']       = array('type' => 'int',    'required' => false, 'default' => 0);
$config->project->form->create['desc']       = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->project->form->create['acl']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['whitelist']  = array('type' => 'array',  'required' => false, 'default' => '');
$config->project->form->create['auth']       = array('type' => 'array',  'required' => false, 'default' => '');
$config->project->form->create['model']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['vision']     = array('type' => 'string', 'required' => false, 'default' => $config->vision);
if(isset($this->config->setCode) && $this->config->setCode == 1) $config->project->form->create['code'] = array('type' => 'string', 'required' => true,  'filter'  => 'trim');

$config->project->form->edit = $config->project->form->create;
$config->project->form->edit['products'] = array('type' => 'array', 'required' => false, 'default' => array());
$config->project->form->edit['branch']   = array('type' => 'array', 'required' => false, 'default' => array());
$config->project->form->edit['plans']    = array('type' => 'array', 'required' => false, 'default' => array());

$config->project->form->start['realBegan'] = array('type' => 'date', 'required' => true, 'filter' => 'trim');

$config->project->form->close['realEnd'] = array('type' => 'date', 'required' => true, 'filter' => 'trim');

$config->project->form->activate['begin']        = array('type' => 'date',   'required' => true);
$config->project->form->activate['end']          = array('type' => 'date',   'required' => true);
$config->project->form->activate['readjustTime'] = array('type' => 'int',    'required' => false, 'default' => '');
$config->project->form->activate['readjustTask'] = array('type' => 'array',  'required' => false, 'default' => '');

$config->project->form->batchedit['id']      = array('type' => 'int',    'required' => false, 'base' => true);
$config->project->form->batchedit['parent']  = array('type' => 'int',    'required' => false);
$config->project->form->batchedit['name']    = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->project->form->batchedit['PM']      = array('type' => 'string', 'required' => false);
$config->project->form->batchedit['begin']   = array('type' => 'date',   'required' => true);
$config->project->form->batchedit['end']     = array('type' => 'date',   'required' => true);
$config->project->form->batchedit['day']     = array('type' => 'int',    'required' => false);
$config->project->form->batchedit['acl']     = array('type' => 'string', 'required' => false);
if(isset($config->setCode) and $config->setCode == 1) $config->project->form->batchedit['code'] = array('type' => 'string', 'required' => true, 'filter' => 'trim');

$config->project->form->manageMembers['account'] = array('type' => 'string', 'required' => false, 'base' => true);
$config->project->form->manageMembers['role']    = array('type' => 'string', 'required' => false, 'filter' => 'trim');
$config->project->form->manageMembers['days']    = array('type' => 'int',    'required' => false);
$config->project->form->manageMembers['hours']   = array('type' => 'float',  'required' => false);
$config->project->form->manageMembers['limited'] = array('type' => 'date',   'required' => false);
