<?php

$config->jenkins->form = new stdclass();

$config->jenkins->form->create = common::formConfig('jenkins', 'create');

$config->jenkins->form->create['type']     = array('type' => 'string',   'required' => true,  'default' => 'jenkins');
$config->jenkins->form->create['name']     = array('type' => 'string',   'required' => true, 'default' => '');
$config->jenkins->form->create['url']      = array('type' => 'string',   'required' => true, 'default' => '');
$config->jenkins->form->create['account']  = array('type' => 'string',   'required' => true, 'default' => '');
$config->jenkins->form->create['token']    = array('type' => 'string',   'required' => false,  'default' => '');
$config->jenkins->form->create['password'] = array('type' => 'string',   'required' => false,  'default' => '');
