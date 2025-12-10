<?php
$now   = helper::now();
$today = helper::today();

$config->program->form = new stdclass();

$config->program->form->create['parent']     = array('type' => 'int',      'control' => 'picker',     'required' => false, 'default' => 0);
$config->program->form->create['name']       = array('type' => 'string',   'control' => 'text',       'required' => true,  'default' => '', 'filter' => 'trim');
$config->program->form->create['PM']         = array('type' => 'string',   'control' => 'picker',     'required' => false, 'default' => 0);
$config->program->form->create['budget']     = array('type' => 'float',    'control' => 'text',       'required' => false, 'default' => '');
$config->program->form->create['budgetUnit'] = array('type' => 'string',   'control' => 'text',       'required' => false, 'default' => 'CNY');
$config->program->form->create['begin']      = array('type' => 'date',     'control' => 'datepicker', 'required' => true,  'default' => '');
$config->program->form->create['end']        = array('type' => 'date',     'control' => 'datepicker', 'required' => false, 'default' => '');
$config->program->form->create['desc']       = array('type' => 'string',   'control' => 'editor',     'required' => false, 'default' => '');
$config->program->form->create['status']     = array('type' => 'string',   'control' => 'text',       'required' => false, 'default' => 'wait');
$config->program->form->create['acl']        = array('type' => 'string',   'control' => 'radio',      'required' => false, 'default' => '');
$config->program->form->create['whitelist']  = array('type' => 'array',    'control' => 'picker',     'required' => false, 'default' => '', 'filter' => 'join');

$config->program->form->edit['parent']     = array('type' => 'int',      'control' => 'picker',     'required' => false, 'default' => 0);
$config->program->form->edit['name']       = array('type' => 'string',   'control' => 'text',       'required' => true,  'default' => '', 'filter' => 'trim');
$config->program->form->edit['PM']         = array('type' => 'string',   'control' => 'picker',     'required' => false, 'default' => 0);
$config->program->form->edit['budget']     = array('type' => 'float',    'control' => 'text',       'required' => false, 'default' => '');
$config->program->form->edit['budgetUnit'] = array('type' => 'string',   'control' => 'text',       'required' => false, 'default' => 'CNY');
$config->program->form->edit['begin']      = array('type' => 'date',     'control' => 'datepicker', 'required' => true,  'default' => '');
$config->program->form->edit['end']        = array('type' => 'date',     'control' => 'datepicker', 'required' => false, 'default' => '');
$config->program->form->edit['desc']       = array('type' => 'string',   'control' => 'editor',     'required' => false, 'default' => '');
$config->program->form->edit['acl']        = array('type' => 'string',   'control' => 'radio',      'required' => false, 'default' => '');
$config->program->form->edit['whitelist']  = array('type' => 'array',    'control' => 'picker',     'required' => false, 'default' => '', 'filter' => 'join');

$config->program->form->close['realEnd']        = array('type' => 'date',     'required' => true,  'default' => $now, 'filter' => 'trim');
$config->program->form->close['status']         = array('type' => 'string',   'required' => false, 'default' => 'closed');
$config->program->form->close['closedDate']     = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->program->form->close['closedBy']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->program->form->close['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->program->form->close['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => '');

$config->program->form->activate['begin']          = array('type' => 'date',     'required' => false, 'default' => null, 'filter' => 'trim');
$config->program->form->activate['end']            = array('type' => 'date',     'required' => false, 'default' => null, 'filter' => 'trim');
$config->program->form->activate['status']         = array('type' => 'string',   'required' => false, 'default' => 'doing');
$config->program->form->activate['realEnd']        = array('type' => 'date',     'required' => false, 'default' => null);
$config->program->form->activate['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->program->form->activate['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => '');

$config->program->form->start['realBegan'] = array('type' => 'date',   'required' => true,  'default' => $today, 'filter'  => 'trim');
$config->program->form->start['comment']   = array('type' => 'string', 'required' => false, 'default' => '',     'control' => 'editor');
$config->program->form->start['uid']       = array('type' => 'string', 'required' => false, 'default' => '',     'filter'  => 'trim');
