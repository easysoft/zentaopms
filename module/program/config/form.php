<?php
$now = helper::now();

global $app;
$config->program->form = new stdclass();

$config->program->form->close['realEnd']        = array('type' => 'string', 'required' => true,  'default' => $now, 'filter' => 'trim');
$config->program->form->close['status']         = array('type' => 'string', 'required' => false, 'default' => 'closed');
$config->program->form->close['closedDate']     = array('type' => 'string', 'required' => false, 'default' => $now);
$config->program->form->close['closedBy']       = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->program->form->close['lastEditedDate'] = array('type' => 'string', 'required' => false, 'default' => $now);
$config->program->form->close['lastEditedBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
