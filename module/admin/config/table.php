<?php
global $lang, $app;
$app->loadLang('admin');
$app->loadLang('user');
$config->admin->checkWeak->dtable = new stdclass();
$config->admin->checkWeak->dtable->fieldList['id']['title']    = $lang->idAB;
$config->admin->checkWeak->dtable->fieldList['id']['type']     = 'ID';
$config->admin->checkWeak->dtable->fieldList['id']['required'] = true;
$config->admin->checkWeak->dtable->fieldList['id']['flex']    = 'left';
$config->admin->checkWeak->dtable->fieldList['id']['group']    = '1';

$config->admin->checkWeak->dtable->fieldList['realname']['title'] = $lang->user->realname;
$config->admin->checkWeak->dtable->fieldList['realname']['type']  = 'text';
$config->admin->checkWeak->dtable->fieldList['realname']['group'] = '1';

$config->admin->checkWeak->dtable->fieldList['account']['title'] = $lang->user->account;
$config->admin->checkWeak->dtable->fieldList['account']['type']  = 'text';
$config->admin->checkWeak->dtable->fieldList['account']['width'] = '200px';
$config->admin->checkWeak->dtable->fieldList['account']['group'] = '1';

$config->admin->checkWeak->dtable->fieldList['phone']['title'] = $lang->user->phone;
$config->admin->checkWeak->dtable->fieldList['phone']['type']  = 'text';
$config->admin->checkWeak->dtable->fieldList['phone']['group'] = '2';

$config->admin->checkWeak->dtable->fieldList['mobile']['title'] = $lang->user->mobile;
$config->admin->checkWeak->dtable->fieldList['mobile']['type']  = 'text';
$config->admin->checkWeak->dtable->fieldList['mobile']['group'] = '2';

$config->admin->checkWeak->dtable->fieldList['weakReason']['title'] = $lang->admin->safe->reason;
$config->admin->checkWeak->dtable->fieldList['weakReason']['type']  = 'text';
$config->admin->checkWeak->dtable->fieldList['weakReason']['map']   = $lang->admin->safe->reasonList;
$config->admin->checkWeak->dtable->fieldList['weakReason']['group'] = '2';

$config->admin->checkWeak->dtable->fieldList['actions']['title']    = $lang->actions;
$config->admin->checkWeak->dtable->fieldList['actions']['type']     = 'actions';
$config->admin->checkWeak->dtable->fieldList['actions']['width']    = '60';
$config->admin->checkWeak->dtable->fieldList['actions']['sortType'] = false;
$config->admin->checkWeak->dtable->fieldList['actions']['fixed']    = 'right';
$config->admin->checkWeak->dtable->fieldList['actions']['list']     = $config->admin->checkWeak->actionList;
$config->admin->checkWeak->dtable->fieldList['actions']['menu']     = array('edit');
