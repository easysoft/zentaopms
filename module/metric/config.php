<?php
$config->metric = new stdclass();
$config->metric->scopeList   = array('global', 'program', 'project', 'execution', 'product', 'user', 'dept');
$config->metric->purposeList = array('scale', 'qc', 'hour', 'cost', 'rate', 'time');
$config->metric->dateList    = array('year', 'month', 'week', 'day');

global $lang;
$config->metric->actionList = array();
$config->metric->actionList['edit']['icon'] = 'edit';
$config->metric->actionList['edit']['text'] = $lang->edit;
$config->metric->actionList['edit']['hint'] = $lang->edit;
$config->metric->actionList['edit']['url']  = array('module' => 'metric', 'method' => 'edit', 'params' => "metricID={id}");

$config->metric->actionList['delete']['icon']         = 'trash';
$config->metric->actionList['delete']['hint']         = $lang->delete;
$config->metric->actionList['delete']['url']          = helper::createLink('metric', 'delete', 'metricID={id}');
$config->metric->actionList['delete']['class']        = 'ajax-submit';
$config->metric->actionList['delete']['data-confirm'] = $lang->metric->confirmDelete;
