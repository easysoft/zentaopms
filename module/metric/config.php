<?php
$config->metric = new stdclass();
$config->metric->scopeList     = array('system', 'program', 'project', 'execution', 'product', 'user', 'dept');
$config->metric->purposeList   = array('scale', 'qc', 'hour', 'cost', 'rate', 'time');
$config->metric->dateList      = array('year', 'month', 'week', 'day');
$config->metric->excludeGlobal = array('program', 'project', 'product', 'execution', 'user', 'dept');

global $lang;
$config->metric->actionList = array();
$config->metric->actionList['edit']['icon'] = 'edit';
$config->metric->actionList['edit']['text'] = $lang->edit;
$config->metric->actionList['edit']['hint'] = $lang->edit;
$config->metric->actionList['edit']['url']  = helper::createLink('metric', 'edit', 'metricID={id}');

$config->metric->actionList['implement']['icon'] = 'code';
$config->metric->actionList['implement']['text'] = $lang->metric->implement;
$config->metric->actionList['implement']['hint'] = $lang->metric->implement;
$config->metric->actionList['implement']['url']  = helper::createLink('metric', 'implement', 'metricID={id}');

$config->metric->actionList['delist']['icon'] = 'code';
$config->metric->actionList['delist']['text'] = $lang->metric->implement;
$config->metric->actionList['delist']['hint'] = $lang->metric->implement;
$config->metric->actionList['delist']['url']  = helper::createLink('metric', 'delist', 'metricID={id}');

$config->metric->actionList['delete']['icon']         = 'trash';
$config->metric->actionList['delete']['hint']         = $lang->delete;
$config->metric->actionList['delete']['url']          = helper::createLink('metric', 'delete', 'metricID={id}');
$config->metric->actionList['delete']['class']        = 'ajax-submit';
$config->metric->actionList['delete']['data-confirm'] = $lang->metric->confirmDelete;

$config->metric->necessaryMethodList = array('getStatement', 'calculator', 'getResult');
