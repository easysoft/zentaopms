<?php
$now = helper::now();

global $app;
$config->program->form = new stdclass();

$config->program->form->close['realEnd']        = array('type' => 'date',     'required' => true,  'default' => $now, 'filter' => 'trim');
$config->program->form->close['status']         = array('type' => 'string',   'required' => false, 'default' => 'closed');
$config->program->form->close['closedDate']     = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->program->form->close['closedBy']       = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->program->form->close['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->program->form->close['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);

$config->program->form->activate['begin']          = array('type' => 'date',     'required' => false, 'default' => null, 'filter' => 'trim');
$config->program->form->activate['end']            = array('type' => 'date',     'required' => false, 'default' => null, 'filter' => 'trim');
$config->program->form->activate['status']         = array('type' => 'string',   'required' => false, 'default' => 'doing');
$config->program->form->activate['realEnd']        = array('type' => 'date',     'required' => false, 'default' => null);
$config->program->form->activate['lastEditedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->program->form->activate['lastEditedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
