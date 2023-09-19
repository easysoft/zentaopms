<?php
global $lang, $app;
$app->loadLang('serverroom');

$config->account->dtable = new stdclass();

$config->account->dtable->fieldList['id']['title'] = 'ID';
$config->account->dtable->fieldList['id']['name']  = 'id';
$config->account->dtable->fieldList['id']['type']  = 'id';

$config->account->dtable->fieldList['name']['title']    = $lang->account->name;
$config->account->dtable->fieldList['name']['name']     = 'name';
$config->account->dtable->fieldList['name']['type']     = 'desc';
$config->account->dtable->fieldList['name']['sortType'] = true;
$config->account->dtable->fieldList['name']['flex']     = 4;
$config->account->dtable->fieldList['name']['hint']     = true;
$config->account->dtable->fieldList['name']['link']     = array('module' => 'account', 'method' => 'view', 'params' => 'id={id}');
$config->account->dtable->fieldList['name']['data-toggle'] = 'modal';

$config->account->dtable->fieldList['provider']['title']    = $lang->account->provider;
$config->account->dtable->fieldList['provider']['name']     = 'provider';
$config->account->dtable->fieldList['provider']['type']     = 'text';
$config->account->dtable->fieldList['provider']['sortType'] = true;
$config->account->dtable->fieldList['provider']['width']    = 80;
$config->account->dtable->fieldList['provider']['map']      = $lang->serverroom->providerList;

$config->account->dtable->fieldList['account']['title']    = $lang->account->account;
$config->account->dtable->fieldList['account']['name']     = 'account';
$config->account->dtable->fieldList['account']['type']     = 'text';
$config->account->dtable->fieldList['account']['sortType'] = true;

$config->account->dtable->fieldList['email']['title']    = $lang->account->email;
$config->account->dtable->fieldList['email']['name']     = 'email';
$config->account->dtable->fieldList['email']['type']     = 'text';
$config->account->dtable->fieldList['email']['sortType'] = true;

$config->account->dtable->fieldList['mobile']['title']    = $lang->account->mobile;
$config->account->dtable->fieldList['mobile']['name']     = 'mobile';
$config->account->dtable->fieldList['mobile']['type']     = 'text';
$config->account->dtable->fieldList['mobile']['sortType'] = true;

$config->account->dtable->fieldList['createdBy']['title']    = $lang->account->createdBy;
$config->account->dtable->fieldList['createdBy']['name']     = 'createdBy';
$config->account->dtable->fieldList['createdBy']['type']     = 'user';
$config->account->dtable->fieldList['createdBy']['width']    = '100px';
$config->account->dtable->fieldList['createdBy']['sortType'] = true;

$config->account->actionList = array();
$config->account->actionList['edit']['icon']        = 'edit';
$config->account->actionList['edit']['text']        = $lang->edit;
$config->account->actionList['edit']['hint']        = $lang->edit;
$config->account->actionList['edit']['data-toggle'] = 'modal';
$config->account->actionList['edit']['data-size']   = 'sm';
$config->account->actionList['edit']['showText']    = true;
$config->account->actionList['edit']['url']         = array('module' => 'account', 'method' => 'edit', 'params' => 'id={id}');

$config->account->actionList['delete']['icon']       = 'trash';
$config->account->actionList['delete']['text']       = $lang->delete;
$config->account->actionList['delete']['hint']       = $lang->delete;
$config->account->actionList['delete']['ajaxSubmit'] = true;
$config->account->actionList['delete']['url']        = array('module' => 'account', 'method' => 'delete', 'params' => 'id={id}');

$config->account->dtable->fieldList['actions']['name']     = 'actions';
$config->account->dtable->fieldList['actions']['title']    = $lang->actions;
$config->account->dtable->fieldList['actions']['type']     = 'actions';
$config->account->dtable->fieldList['actions']['sortType'] = false;
$config->account->dtable->fieldList['actions']['fixed']    = 'right';
$config->account->dtable->fieldList['actions']['menu']     = array('edit', 'delete');
$config->account->dtable->fieldList['actions']['list']     = $config->account->actionList;
