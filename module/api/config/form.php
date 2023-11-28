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
