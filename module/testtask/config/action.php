<?php
global $lang;

$config->testtask->actionList = array();
$config->testtask->actionList['start']['icon'] = 'play';
$config->testtask->actionList['start']['text'] = $lang->testtask->start;
$config->testtask->actionList['start']['hint'] = $lang->testtask->start;
$config->testtask->actionList['start']['url']  = array('module' => 'testtask', 'method' => 'start', 'params' => 'taskID={id}');

$config->testtask->actionList['close']['icon'] = 'off';
$config->testtask->actionList['close']['text'] = $lang->testtask->close;
$config->testtask->actionList['close']['hint'] = $lang->testtask->close;
$config->testtask->actionList['close']['url']  = array('module' => 'testtask', 'method' => 'close', 'params' => 'taskID={id}');

$config->testtask->actionList['block']['icon'] = 'pause';
$config->testtask->actionList['block']['text'] = $lang->testtask->block;
$config->testtask->actionList['block']['hint'] = $lang->testtask->block;
$config->testtask->actionList['block']['url']  = array('module' => 'testtask', 'method' => 'block', 'params' => 'taskID={id}');

$config->testtask->actionList['activate']['icon']        = 'magic';
$config->testtask->actionList['activate']['text']        = $lang->testtask->activate;
$config->testtask->actionList['activate']['hint']        = $lang->testtask->activate;
$config->testtask->actionList['activate']['url']         = array('module' => 'testtask', 'method' => 'activate', 'params' => 'taskID={id}');
$config->testtask->actionList['activate']['data-toggle'] = 'modal';

$config->testtask->actionList['cases']['icon'] = 'sitemap';
$config->testtask->actionList['cases']['text'] = $lang->testtask->cases;
$config->testtask->actionList['cases']['hint'] = $lang->testtask->cases;
$config->testtask->actionList['cases']['url']  = array('module' => 'testtask', 'method' => 'cases', 'params' => 'taskID={id}');

$config->testtask->actionList['linkCase']['icon'] = 'link';
$config->testtask->actionList['linkCase']['text'] = $lang->testtask->linkCase;
$config->testtask->actionList['linkCase']['hint'] = $lang->testtask->linkCase;
$config->testtask->actionList['linkCase']['url']  = array('module' => 'testtask', 'method' => 'linkCase', 'params' => 'taskID={id}&type=all&param=myQueryID');

$config->testtask->actionList['report']['icon'] = 'summary';
$config->testtask->actionList['report']['text'] = $lang->testtask->testreport;
$config->testtask->actionList['report']['hint'] = $lang->testtask->testreport;
$config->testtask->actionList['report']['url']  = array('module' => 'testreport', 'method' => 'browse', 'params' => 'objectID={product}&objectType=product&extra={id}');

$config->testtask->actionList['view']['icon']        = 'list-alt';
$config->testtask->actionList['view']['text']        = $lang->testtask->view;
$config->testtask->actionList['view']['hint']        = $lang->testtask->view;
$config->testtask->actionList['view']['url']         = array('module' => 'testtask', 'method' => 'view', 'params' => 'taskID={id}');
$config->testtask->actionList['view']['data-toggle'] = 'modal';

$config->testtask->actionList['edit']['icon'] = 'edit';
$config->testtask->actionList['edit']['text'] = $lang->testtask->edit;
$config->testtask->actionList['edit']['hint'] = $lang->testtask->edit;
$config->testtask->actionList['edit']['url']  = array('module' => 'testtask', 'method' => 'edit', 'params' => 'taskID={id}');

$config->testtask->actionList['delete']['icon'] = 'trash';
$config->testtask->actionList['delete']['text'] = $lang->testtask->delete;
$config->testtask->actionList['delete']['hint'] = $lang->testtask->delete;
$config->testtask->actionList['delete']['url']  = array('module' => 'testtask', 'method' => 'delete', 'params' => 'taskID={id}');
