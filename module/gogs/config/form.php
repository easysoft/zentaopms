<?php

$config->gogs->form = new stdclass();

$config->gogs->form->create = common::formConfig('gogs', 'create');

$config->gogs->form->create['type']  = array('type' => 'string',   'required' => true,  'default' => 'gogs');
$config->gogs->form->create['name']  = array('type' => 'string',   'required' => true, 'default' => '');
$config->gogs->form->create['url']   = array('type' => 'string',   'required' => true, 'default' => '');
$config->gogs->form->create['token'] = array('type' => 'string',   'required' => true,  'default' => '');
