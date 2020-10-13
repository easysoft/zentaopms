<?php

global $lang;
$config->personnel->accessible = new stdClass();
$config->personnel->accessible->search['module']              = 'accessible';
$config->personnel->accessible->search['fields']['department'] = $lang->personnel->department;
$config->personnel->accessible->search['fields']['realName']   = $lang->personnel->realName;
$config->personnel->accessible->search['fields']['userName']   = $lang->personnel->userName;
$config->personnel->accessible->search['fields']['job']        = $lang->personnel->job;
$config->personnel->accessible->search['fields']['genders']    = $lang->personnel->genders;

$config->personnel->accessible->search['params']['department'] = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->personnel->accessible->search['params']['realName']   = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->personnel->accessible->search['params']['userName']   = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->personnel->accessible->search['params']['job']        = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->personnel->accessible->search['params']['genders']    = array('operator' => '=', 'control' => 'input', 'values' => '');
