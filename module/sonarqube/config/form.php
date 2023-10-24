<?php

$config->sonarqube->form = new stdclass();

$config->sonarqube->form->create = common::formConfig('sonarqube', 'create');

$config->sonarqube->form->create['type']     = array('type' => 'string',   'required' => true,  'default' => 'sonarqube');
$config->sonarqube->form->create['name']     = array('type' => 'string',   'required' => true, 'default' => '');
$config->sonarqube->form->create['url']      = array('type' => 'string',   'required' => true, 'default' => '');
$config->sonarqube->form->create['account']  = array('type' => 'string',   'required' => true, 'default' => '');
$config->sonarqube->form->create['password'] = array('type' => 'string',   'required' => true,  'default' => '');
