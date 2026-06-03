<?php
global $app, $lang;
$config->artifact->actionList = array();
$config->artifact->actionList['edit']['icon']        = 'edit';
$config->artifact->actionList['edit']['text']        = $lang->artifact->editArtifact;
$config->artifact->actionList['edit']['hint']        = $lang->artifact->editArtifact;
$config->artifact->actionList['edit']['url']         = array('module' => 'artifact', 'method' => 'editArtifact', 'params' => 'id={id}&artifactLibID={artifactLibID}');
$config->artifact->actionList['edit']['data-toggle'] = 'modal';
$config->artifact->actionList['edit']['data-app']    = $app->tab;

$config->artifact->actionList['history']['icon']        = 'history';
$config->artifact->actionList['history']['text']        = $lang->artifact->history;
$config->artifact->actionList['history']['hint']        = $lang->artifact->history;
$config->artifact->actionList['history']['url']         = array('module' => 'artifact', 'method' => 'history', 'params' => "id={id}&artifactLibID={artifactLibID}");
$config->artifact->actionList['history']['data-toggle'] = 'modal';
$config->artifact->actionList['history']['data-app']    = $app->tab;

$config->artifact->actionList['download']['icon']         = 'download';
$config->artifact->actionList['download']['text']         = $lang->artifact->download;
$config->artifact->actionList['download']['hint']         = $lang->artifact->download;
$config->artifact->actionList['download']['url']          = array('module' => 'artifact', 'method' => 'downloadArtifact', 'params' => 'assetID={id}&artifactLibID={artifactLibID}');
$config->artifact->actionList['download']['className']    = 'ajax-submit';

$config->artifact->actionList['copyCMD']['icon']      = 'copy';
$config->artifact->actionList['copyCMD']['text']      = $lang->artifact->copyCMD;
$config->artifact->actionList['copyCMD']['hint']      = $lang->artifact->copyCMD;
$config->artifact->actionList['copyCMD']['url']       = array('module' => 'artifact', 'method' => 'copyCMD', 'params' => 'assetID={id}');
$config->artifact->actionList['copyCMD']['className'] = 'ajax-submit';

$config->artifact->actionList['move']['icon']        = 'move';
$config->artifact->actionList['move']['text']        = $lang->artifact->move;
$config->artifact->actionList['move']['hint']        = $lang->artifact->move;
$config->artifact->actionList['move']['url']         = array('module' => 'artifact', 'method' => 'moveArtifact', 'params' => "assetID={id}&artifactLibID={artifactLibID}");
$config->artifact->actionList['move']['data-app']    = $app->tab;
$config->artifact->actionList['move']['data-toggle'] = 'modal';

$config->artifact->actionList['delete']['icon']         = 'trash';
$config->artifact->actionList['delete']['text']         = $lang->artifact->deleteArtifact;
$config->artifact->actionList['delete']['hint']         = $lang->artifact->deleteArtifact;
$config->artifact->actionList['delete']['url']          = array('module' => 'artifact', 'method' => 'deleteArtifact', 'params' => 'assetID={id}&artifactLibID={artifactLibID}');
$config->artifact->actionList['delete']['className']    = 'ajax-submit';
$config->artifact->actionList['delete']['data-confirm'] = array('message' => $lang->artifact->notice->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
