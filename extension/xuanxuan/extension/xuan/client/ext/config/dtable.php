<?php
global $lang;

$config->client->actionList = [];
$config->client->actionList['edit']['icon']        = 'edit';
$config->client->actionList['edit']['text']        = $lang->edit;
$config->client->actionList['edit']['hint']        = $lang->edit;
$config->client->actionList['edit']['url']         = array('module'  => 'client', 'method' => 'edit', 'params' => "clientID={id}");
$config->client->actionList['edit']['className']   = 'ajax-submit';
$config->client->actionList['edit']['data-toggle'] = 'modal';

$config->client->actionList['delete']['icon']         = 'trash';
$config->client->actionList['delete']['text']         = $lang->delete;
$config->client->actionList['delete']['hint']         = $lang->delete;
$config->client->actionList['delete']['url']          = array('module'  => 'client', 'method' => 'delete', 'params' => "clientID={id}");
$config->client->actionList['delete']['className']    = 'ajax-submit';
$config->client->actionList['delete']['data-confirm'] = $lang->confirmDelete;

$config->client->dtable = new stdclass();
$config->client->dtable->fieldList['id']['title'] = $lang->client->id;
$config->client->dtable->fieldList['id']['type']  = 'id';
$config->client->dtable->fieldList['id']['sort']  = true;
$config->client->dtable->fieldList['id']['fixed'] = 'left';

$config->client->dtable->fieldList['version']['title']    = $lang->client->version;
$config->client->dtable->fieldList['version']['align']    = 'center';
$config->client->dtable->fieldList['version']['width']    = 150;
$config->client->dtable->fieldList['version']['sort']     = true;
$config->client->dtable->fieldList['version']['required'] = true;

$config->client->dtable->fieldList['strategy']['title'] = $lang->client->strategy;
$config->client->dtable->fieldList['strategy']['map']   = $lang->client->strategies;
$config->client->dtable->fieldList['strategy']['align'] = 'center';
$config->client->dtable->fieldList['strategy']['width'] = 150;
$config->client->dtable->fieldList['strategy']['sort']  = true;
$config->client->dtable->fieldList['strategy']['show']  = true;

$config->client->dtable->fieldList['status']['title'] = $lang->client->releaseStatus;
$config->client->dtable->fieldList['status']['map']   = $lang->client->status;
$config->client->dtable->fieldList['status']['align'] = 'center';
$config->client->dtable->fieldList['status']['width'] = 150;
$config->client->dtable->fieldList['status']['sort']  = true;
$config->client->dtable->fieldList['status']['show']  = true;

$config->client->dtable->fieldList['desc']['title'] = $lang->client->desc;
$config->client->dtable->fieldList['desc']['flex']  = 2;
$config->client->dtable->fieldList['desc']['show']  = true;

$config->client->dtable->fieldList['actions']['title'] = $lang->actions;
$config->client->dtable->fieldList['actions']['type']  = 'actions';
$config->client->dtable->fieldList['actions']['list']  = $config->client->actionList;
$config->client->dtable->fieldList['actions']['menu']  = array('edit', 'delete');
$config->client->dtable->fieldList['actions']['fixed'] = 'right';
