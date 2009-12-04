<?php
global $lang;
$config->bug->search['module']               = 'bug';
$config->bug->search['fields']['id']         = $lang->bug->id;
$config->bug->search['fields']['title']      = $lang->bug->title;
$config->bug->search['fields']['status']     = $lang->bug->status;
$config->bug->search['fields']['openedBy']   = $lang->bug->openedBy;
$config->bug->search['fields']['resolution'] = $lang->bug->resolution;
$config->bug->search['groupItems']           = 3;
$config->bug->search['params']['title']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['status']     = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->statusList);
$config->bug->search['params']['openedBy']   = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['resolution'] = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->resolutionList);
