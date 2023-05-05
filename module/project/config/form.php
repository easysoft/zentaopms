<?php
$config->project->form = new stdclass();

$config->project->form->create =  array();
$config->project->form->edit   =  array();
$config->project->form->close  =  array();
$config->project->form->start  =  array();

$config->project->form->suspend =  array();

$config->project->form->create['parent']     = array('type' => 'int',    'required' => false, 'default' => '');
$config->project->form->create['name']       = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->project->form->create['code']       = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->project->form->create['multiple']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['hasProduct'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['PM']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['budget']     = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['budgetUnit'] = array('type' => 'string', 'required' => false, 'default' => 'CNY');
$config->project->form->create['begin']      = array('type' => 'date',   'required' => true);
$config->project->form->create['end']        = array('type' => 'date',   'required' => true);
$config->project->form->create['desc']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['acl']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->create['whitelist']  = array('type' => 'array',  'required' => false, 'default' => '');
$config->project->form->create['auth']       = array('type' => 'array',  'required' => false, 'default' => '');
$config->project->form->create['model']      = array('type' => 'string', 'required' => false, 'default' => '');

$config->project->form->edit['parent']     = array('type' => 'int',    'required' => false, 'default' => '');
$config->project->form->edit['name']       = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->project->form->edit['code']       = array('type' => 'string', 'required' => true, 'filter' => 'trim');
$config->project->form->edit['multiple']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->edit['hasProduct'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->edit['PM']         = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->edit['budget']     = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->edit['budgetUnit'] = array('type' => 'string', 'required' => false, 'default' => 'CNY');
$config->project->form->edit['begin']      = array('type' => 'date',   'required' => true);
$config->project->form->edit['end']        = array('type' => 'date',   'required' => true);
$config->project->form->edit['desc']       = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->edit['acl']        = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->edit['whitelist']  = array('type' => 'array',  'required' => false, 'default' => '', 'filter' => 'join');
$config->project->form->edit['auth']       = array('type' => 'array',  'required' => false, 'default' => '');
$config->project->form->edit['model']      = array('type' => 'string', 'required' => false, 'default' => '');
$config->project->form->edit['plans']      = array('type' => 'array',  'required' => false, 'default' => '');
$config->project->form->edit['products']   = array('type' => 'array',  'required' => false, 'default' => '');
$config->project->form->edit['product']    = array('type' => 'array',  'required' => false, 'default' => '');

$config->project->form->start['realBegan'] = array('type' => 'date', 'required' => true, 'filter' => 'trim');

$config->project->form->close['realEnd'] = array('type' => 'date', 'required' => true, 'filter' => 'trim');
