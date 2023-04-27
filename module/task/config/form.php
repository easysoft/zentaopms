<?php
$config->task->form = new stdclass();

global $app;
$config->task->form->assign = array();
$config->task->form->assign['assignedTo']     = array('type' => 'string', 'required' => false, 'default' => '');
$config->task->form->assign['left']           = array('type' => 'float', 'required' => true);
$config->task->form->assign['lastEditedBy']   = array('type' => 'string', 'required' => false, 'default' => $app->user->account);
$config->task->form->assign['lastEditedDate'] = array('type' => 'date', 'required' => false, 'default' => helper::now());
$config->task->form->assign['assignedDate']   = array('type' => 'date', 'required' => false, 'default' => helper::now());
$config->task->form->assign['comment']        = array('type' => 'text', 'required' => false, 'default' => '');
