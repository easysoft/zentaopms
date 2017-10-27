<?php
$config->score = new stdClass();

$config->score->model['user']['login']          = array('num' => 3, 'time' => 24, 'score' => 1, 'other' => '');
$config->score->model['user']['changePassword'] = array('num' => 1, 'time' => 0, 'score' => 10, 'other' => array(1 => 2, 2 => 5));
$config->score->model['user']['editProfile']    = array('num' => 1, 'time' => 0, 'score' => 10, 'other' => '');

$config->score->model['tutorial']['keepAll'] = array('num' => 1, 'time' => 0, 'score' => 100, 'other' => '');

$config->score->model['ajax']['selectTheme']       = array('num' => 1, 'time' => 0, 'score' => 10, 'other' => '');
$config->score->model['ajax']['selectLang']        = array('num' => 1, 'time' => 0, 'score' => 10, 'other' => '');
$config->score->model['ajax']['showSearchMenu']    = array('num' => 1, 'time' => 0, 'score' => 10, 'other' => '');
$config->score->model['ajax']['dragSelected']      = array('num' => 1, 'time' => 0, 'score' => 20, 'other' => '');
$config->score->model['ajax']['lastNext']          = array('num' => 1, 'time' => 0, 'score' => 20, 'other' => '');
$config->score->model['ajax']['switchToDataTable'] = array('num' => 1, 'time' => 0, 'score' => 1, 'other' => '');
$config->score->model['ajax']['submitPage']        = array('num' => 1, 'time' => 0, 'score' => 1, 'other' => '');
$config->score->model['ajax']['customMenu']        = array('num' => 1, 'time' => 0, 'score' => 1, 'other' => '');
$config->score->model['ajax']['quickDump']         = array('num' => 1, 'time' => 0, 'score' => 10, 'other' => '');
$config->score->model['ajax']['batchCreate']       = array('num' => 1, 'time' => 0, 'score' => 20, 'other' => '');
$config->score->model['ajax']['batchEdit']         = array('num' => 1, 'time' => 0, 'score' => 20, 'other' => '');

$config->score->model['doc']['create'] = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => '');

$config->score->model['todo']['create'] = array('num' => 5, 'time' => 24, 'score' => 1, 'other' => '');

$config->score->model['story']['create'] = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => '');
$config->score->model['story']['close']  = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => array('createID' => 2));//每个需求被完成关闭，需求的创建者额外增加2分，关闭者增加1分

$config->score->model['task']['create'] = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => '');
$config->score->model['task']['close']  = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => '');
$config->score->model['task']['finish'] = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => array(1 => 2, 2 => 1, 3 => 0));

$config->score->model['bug']['create']         = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => '');
$config->score->model['bug']['confirmBug']     = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => array(1 => 3, 2 => 2, 3 => 1));
$config->score->model['bug']['createFormCase'] = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => '');
$config->score->model['bug']['resolve']        = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => array(1 => 3, 2 => 2, 3 => 1));
$config->score->model['bug']['saveTplModal']   = array('num' => 0, 'time' => 0, 'score' => 20, 'other' => '');

$config->score->model['testTask']['runCase'] = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => '');

$config->score->model['testcase']['create'] = array('num' => 0, 'time' => 0, 'score' => 1, 'other' => '');

$config->score->model['build']['create'] = array('num' => 0, 'time' => 0, 'score' => 10, 'other' => '');

$config->score->model['project']['create'] = array('num' => 0, 'time' => 0, 'score' => 10, 'other' => '');
$config->score->model['project']['close']  = array('num' => 0, 'time' => 0, 'score' => 0, 'other' => array('manager' => array(20, 10), 'member' => array(5, 5)));

$config->score->model['productplan']['create'] = array('num' => 0, 'time' => 0, 'score' => 10, 'other' => '');

$config->score->model['release']['create'] = array('num' => 0, 'time' => 0, 'score' => 10, 'other' => '');

$config->score->model['block']['set'] = array('num' => 1, 'time' => 0, 'score' => 20, 'other' => '');

$config->score->model['search']['saveQuery']         = array('num' => 1, 'time' => 0, 'score' => 1, 'other' => '');
$config->score->model['search']['saveQueryAdvanced'] = array('num' => 1, 'time' => 0, 'score' => 1, 'other' => '');
