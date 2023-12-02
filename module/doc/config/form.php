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

$config->doc->form->create['title']        = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->create['version']      = array('type' => 'int',      'required' => false, 'default' => 1);
$config->doc->form->create['product']      = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['project']      = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['execution']    = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['module']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['lib']          = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->create['status']       = array('type' => 'string',   'required' => false, 'default' => 'normal');
$config->doc->form->create['type']         = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['keywords']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['acl']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['vision']       = array('type' => 'string',   'required' => false, 'default' => $config->vision);
$config->doc->form->create['addedBy']      = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->doc->form->create['addedDate']    = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->doc->form->create['editedBy']     = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->doc->form->create['editedDate']   = array('type' => 'datetime', 'required' => false, 'default' => $now);
$config->doc->form->create['groups']       = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->create['users']        = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->create['mailto']       = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->create['contentType']  = array('type' => 'string',   'required' => false, 'default' => 'html');
$config->doc->form->create['content']      = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->doc->form->create['template']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['templateType'] = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->create['chapterType']  = array('type' => 'string',   'required' => false, 'default' => '');

$config->doc->form->edit['title']        = array('type' => 'string',   'required' => true,  'default' => '', 'filter' => 'trim');
$config->doc->form->edit['product']      = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->edit['project']      = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->edit['execution']    = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->edit['module']       = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->edit['lib']          = array('type' => 'int',      'required' => false, 'default' => 0);
$config->doc->form->edit['status']       = array('type' => 'string',   'required' => false, 'default' => 'normal');
$config->doc->form->edit['type']         = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->edit['keywords']     = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->edit['acl']          = array('type' => 'string',   'required' => false, 'default' => '');
$config->doc->form->edit['content']      = array('type' => 'string',   'required' => false, 'default' => '', 'control' => 'editor');
$config->doc->form->edit['groups']       = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->edit['users']        = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->edit['mailto']       = array('type' => 'array',    'required' => false, 'default' => '', 'filter' => 'join');
$config->doc->form->edit['editedBy']     = array('type' => 'string',   'required' => false, 'default' => $app->user->account);
$config->doc->form->edit['editedDate']   = array('type' => 'datetime', 'required' => false, 'default' => $now);
