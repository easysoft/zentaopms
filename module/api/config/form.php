<?php
$config->api->form = new stdclass();
$config->api->form->createRelease = array();
$config->api->form->createRelease['version'] = array('type' => 'string', 'required' => true,  'default' => '');
$config->api->form->createRelease['desc']    = array('type' => 'string', 'required' => false, 'default' => '');

$config->api->form->createStruct = array();
$config->api->form->createStruct['name']      = array('type' => 'string', 'required' => true,  'default' => '');
$config->api->form->createStruct['type']      = array('type' => 'string', 'required' => false, 'default' => 'formData');
$config->api->form->createStruct['attribute'] = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->api->form->createStruct['desc']      = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->api->form->editStruct = array();
$config->api->form->editStruct['name']      = array('type' => 'string', 'required' => true,  'default' => '');
$config->api->form->editStruct['type']      = array('type' => 'string', 'required' => false, 'default' => 'formData');
$config->api->form->editStruct['attribute'] = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');
$config->api->form->editStruct['desc']      = array('type' => 'string', 'required' => false, 'default' => '', 'control' => 'editor');

$config->api->form->createLib = array();
$config->api->form->createLib['libType']   = array('type' => 'string', 'required' => true,  'default' => 'nolink');
$config->api->form->createLib['product']   = array('type' => 'int',    'required' => true,  'default' => 0);
$config->api->form->createLib['project']   = array('type' => 'int',    'required' => true,  'default' => 0);
$config->api->form->createLib['execution'] = array('type' => 'int',    'required' => true,  'default' => 0);
$config->api->form->createLib['name']      = array('type' => 'string', 'required' => true,  'default' => '');
$config->api->form->createLib['baseUrl']   = array('type' => 'string', 'required' => false, 'default' => '');
$config->api->form->createLib['acl']       = array('type' => 'string', 'required' => false, 'default' => 'open');
$config->api->form->createLib['groups']    = array('type' => 'array',  'required' => false, 'default' => array(''), 'filter' => 'join');
$config->api->form->createLib['users']     = array('type' => 'array',  'required' => false, 'default' => array(''), 'filter' => 'join');

$config->api->form->editLib = array();
$config->api->form->editLib['type']    = array('type' => 'string', 'required' => true,  'default' => 'nolink');
$config->api->form->editLib['name']    = array('type' => 'string', 'required' => true,  'default' => '');
$config->api->form->editLib['baseUrl'] = array('type' => 'string', 'required' => false, 'default' => '');
$config->api->form->editLib['acl']     = array('type' => 'string', 'required' => false, 'default' => 'open');
$config->api->form->editLib['groups']  = array('type' => 'array',  'required' => false, 'default' => array(''), 'filter' => 'join');
$config->api->form->editLib['users']   = array('type' => 'array',  'required' => false, 'default' => array(''), 'filter' => 'join');
