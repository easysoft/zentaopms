<?php
global $lang;
$config->personnel->accessible = new stdClass();
$config->personnel->accessible->search['module']             = 'accessible';
$config->personnel->accessible->search['fields']['realname'] = $lang->personnel->realName;
$config->personnel->accessible->search['fields']['role']     = $lang->personnel->job;
$config->personnel->accessible->search['fields']['account']  = $lang->personnel->userName;
$config->personnel->accessible->search['fields']['gender']   = $lang->personnel->genders;

$config->personnel->accessible->search['params']['realname'] = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->personnel->accessible->search['params']['account']  = array('operator' => '=', 'control' => 'input', 'values' => '');
$config->personnel->accessible->search['params']['role']     = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->personnel->accessible->search['params']['gender']   = array('operator' => '=', 'control' => 'select', 'values' => '');
