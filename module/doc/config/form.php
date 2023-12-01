<?php
$now = helper::now();

global $app, $config;

$config->doc->form = new stdclass();

$config->doc->form->createlib['name']      = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->createlib['baseUrl']   = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->createlib['acl']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->createlib['type']      = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->createlib['product']   = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->createlib['project']   = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->createlib['execution'] = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->createlib['groups']    = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->createlib['users']     = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->createlib['vision']    = array('type' => 'string',   'required' => false, 'default' => $config->vision);
$config->doc->form->createlib['addedBy']   = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->doc->form->createlib['addedDate'] = array('type' => 'datetime', 'required' => false, 'default' => $now);

$config->doc->form->editlib['name']   = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->editlib['acl']    = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->editlib['groups'] = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->editlib['users']  = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
