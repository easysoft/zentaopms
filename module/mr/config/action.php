<?php
$config->mr->actionList = array();
$config->mr->actionList['edit']['icon'] = 'edit';
$config->mr->actionList['edit']['text'] = $lang->mr->edit;
$config->mr->actionList['edit']['hint'] = $lang->mr->edit;
$config->mr->actionList['edit']['url']  = array('module' => 'mr', 'method' => 'edit', 'params' => 'MRID={id}');

$config->mr->actionList['review'] = array();
$config->mr->actionList['review']['icon']        = 'review';
$config->mr->actionList['review']['text']        = $lang->mr->review;
$config->mr->actionList['review']['url']         = helper::createLink($module, 'review', "id={id}");
$config->mr->actionList['review']['data-toggle'] = 'modal';
$config->mr->actionList['review']['data-app']    = $app->tab;

$config->mr->actionList['close']['icon'] = 'close';
$config->mr->actionList['close']['text'] = $lang->mr->close;
$config->mr->actionList['close']['hint'] = $lang->mr->close;
$config->mr->actionList['close']['url']  = array('module' => 'mr', 'method' => 'close', 'params' => 'MRID={id}');

$config->mr->actionList['reopen'] = array();
$config->mr->actionList['reopen']['icon']      = 'restart';
$config->mr->actionList['reopen']['text']      = $lang->mr->reopen;
$config->mr->actionList['reopen']['url']       = helper::createLink($module, 'reopen', "MRID={id}");
$config->mr->actionList['reopen']['className'] = 'ajax-submit';
$config->mr->actionList['reopen']['data-app']  = $app->tab;
