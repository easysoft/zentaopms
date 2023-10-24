<?php

$config->gitea->form = new stdclass();

$config->gitea->form->create = common::formConfig('gitea', 'create');

$config->gitea->form->create['type']  = array('type' => 'string',   'required' => true,  'default' => 'gitea');
$config->gitea->form->create['name']  = array('type' => 'string',   'required' => true, 'default' => '');
$config->gitea->form->create['url']   = array('type' => 'string',   'required' => true, 'default' => '');
$config->gitea->form->create['token'] = array('type' => 'string',   'required' => true,  'default' => '');
