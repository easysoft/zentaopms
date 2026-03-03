<?php
$config->ppm->actionList = array();
$config->ppm->actionList['edit']['icon']     = 'edit';
$config->ppm->actionList['edit']['text']     = $lang->ppm->edit;
$config->ppm->actionList['edit']['hint']     = $lang->ppm->edit;
$config->ppm->actionList['edit']['url']      = array('module' => 'ppm', 'method' => 'edit', 'params' => 'id={id}');
$config->ppm->actionList['edit']['data-app'] = $app->tab;

$config->ppm->actionList['review'] = array();
$config->ppm->actionList['review']['icon']        = 'review';
$config->ppm->actionList['review']['text']        = $lang->ppm->review;
$config->ppm->actionList['review']['url']         = helper::createLink('ppm', 'review', "id={id}");
$config->ppm->actionList['review']['data-toggle'] = 'modal';
$config->ppm->actionList['review']['data-app']    = $app->tab;

$config->ppm->actionList['close']['icon']         = 'close';
$config->ppm->actionList['close']['text']         = $lang->ppm->close;
$config->ppm->actionList['close']['hint']         = $lang->ppm->close;
$config->ppm->actionList['close']['url']          = array('module' => 'ppm', 'method' => 'close', 'params' => 'id={id}');
$config->ppm->actionList['close']['className']    = 'ajax-submit';
$config->ppm->actionList['close']['data-confirm'] = array('message' => $lang->ppm->notice->confirmClose, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->ppm->actionList['reopen'] = array();
$config->ppm->actionList['reopen']['icon']         = 'restart';
$config->ppm->actionList['reopen']['text']         = $lang->ppm->reopen;
$config->ppm->actionList['reopen']['url']          = helper::createLink('ppm', 'reopen', "id={id}");
$config->ppm->actionList['reopen']['className']    = 'ajax-submit';
$config->ppm->actionList['reopen']['data-app']     = $app->tab;
$config->ppm->actionList['reopen']['data-confirm'] = array('message' => $lang->ppm->notice->confirmReopen, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
