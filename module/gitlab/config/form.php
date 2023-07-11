<?php

$config->gitlab->form = new stdclass();

$config->gitlab->form->create = common::formConfig('gitlab', 'create');

$config->gitlab->form->create['type']  = array('type' => 'string',   'required' => true,  'default' => 'gitlab');
$config->gitlab->form->create['name']  = array('type' => 'string',   'required' => true, 'default' => '');
$config->gitlab->form->create['url']   = array('type' => 'string',   'required' => true, 'default' => '');
$config->gitlab->form->create['token'] = array('type' => 'string',   'required' => true,  'default' => '');
