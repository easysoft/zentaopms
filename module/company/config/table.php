<?php
$config->company->user = new stdclass();
$config->company->user->actionList = array();

if(!empty($config->sso->turnon))
{
    $config->company->user->actionList['unbind']['icon'] = 'unlink';
    $config->company->user->actionList['unbind']['text'] = '';
    $config->company->user->actionList['unbind']['hint'] = '';
    $config->company->user->actionList['unbind']['url']  = array('module' => 'user', 'method' => 'unbind', 'params' => 'userID={id}');
}

$config->company->user->actionList['unlock']['icon'] = 'unlock';
$config->company->user->actionList['unlock']['text'] = '';
$config->company->user->actionList['unlock']['hint'] = '';
$config->company->user->actionList['unlock']['url']  = array('module' => 'user', 'method' => 'unlock', 'params' => 'userID={id}');

$config->company->user->actionList['edit']['icon'] = 'edit';
$config->company->user->actionList['edit']['text'] = '';
$config->company->user->actionList['edit']['hint'] = '';
$config->company->user->actionList['edit']['url']  = array('module' => 'user', 'method' => 'edit', 'params' => 'userID={id}&from=company');

$config->company->user->actionList['delete']['icon']        = 'trash';
$config->company->user->actionList['delete']['text']        = '';
$config->company->user->actionList['delete']['hint']        = '';
$config->company->user->actionList['delete']['url']         = array('module' => 'user', 'method' => 'delete', 'params' => 'userID={id}');
$config->company->user->actionList['delete']['data-toggle'] = 'modal';

$config->company->user->dtable = new stdclass();
$config->company->user->dtable->fieldList['id']['name']     = 'id';
$config->company->user->dtable->fieldList['id']['title']    = $lang->idAB;
$config->company->user->dtable->fieldList['id']['type']     = 'checkID';
$config->company->user->dtable->fieldList['id']['fixed']    = 'left';
$config->company->user->dtable->fieldList['id']['sortType'] = true;
$config->company->user->dtable->fieldList['id']['checkbox'] = true;
$config->company->user->dtable->fieldList['id']['show']     = true;
$config->company->user->dtable->fieldList['id']['group']    = 1;

$config->company->user->dtable->fieldList['realname']['name']     = 'realname';
$config->company->user->dtable->fieldList['realname']['title']    = $lang->user->realname;
$config->company->user->dtable->fieldList['realname']['type']     = 'text';
$config->company->user->dtable->fieldList['realname']['group']    = '1';
$config->company->user->dtable->fieldList['realname']['fixed']    = 'left';
$config->company->user->dtable->fieldList['realname']['sortType'] = true;
$config->company->user->dtable->fieldList['realname']['group']    = '1';

$config->company->user->dtable->fieldList['account']['name']     = 'account';
$config->company->user->dtable->fieldList['account']['title']    = $lang->user->account;
$config->company->user->dtable->fieldList['account']['type']     = 'text';
$config->company->user->dtable->fieldList['account']['group']    = '1';
$config->company->user->dtable->fieldList['account']['sortType'] = true;

$config->company->user->dtable->fieldList['gender']['name']     = 'gender';
$config->company->user->dtable->fieldList['gender']['title']    = $lang->user->gender;
$config->company->user->dtable->fieldList['gender']['type']     = 'category';
$config->company->user->dtable->fieldList['gender']['map']      = $lang->user->genderList;
$config->company->user->dtable->fieldList['gender']['sortType'] = true;
$config->company->user->dtable->fieldList['gender']['group']    = '2';

$config->company->user->dtable->fieldList['role']['name']     = 'role';
$config->company->user->dtable->fieldList['role']['title']    = $lang->user->role;
$config->company->user->dtable->fieldList['role']['type']     = 'category';
$config->company->user->dtable->fieldList['role']['map']      = $lang->user->roleList;
$config->company->user->dtable->fieldList['role']['sortType'] = true;
$config->company->user->dtable->fieldList['role']['group']    = '3';

$config->company->user->dtable->fieldList['phone']['name']     = 'phone';
$config->company->user->dtable->fieldList['phone']['title']    = $lang->user->phone;
$config->company->user->dtable->fieldList['phone']['type']     = 'text';
$config->company->user->dtable->fieldList['phone']['sortType'] = true;
$config->company->user->dtable->fieldList['phone']['group']    = '4';

if(!empty($this->config->isINT))
{
    $config->company->user->dtable->fieldList['skype']['name']     = 'skype';
    $config->company->user->dtable->fieldList['skype']['title']    = $lang->user->skype;
    $config->company->user->dtable->fieldList['skype']['type']     = 'text';
    $config->company->user->dtable->fieldList['skype']['sortType'] = true;
    $config->company->user->dtable->fieldList['skype']['group']    = '4';
}
else
{
    $config->company->user->dtable->fieldList['qq']['name']     = 'qq';
    $config->company->user->dtable->fieldList['qq']['title']    = $lang->user->qq;
    $config->company->user->dtable->fieldList['qq']['type']     = 'text';
    $config->company->user->dtable->fieldList['qq']['sortType'] = true;
    $config->company->user->dtable->fieldList['qq']['group']    = '4';
}

$config->company->user->dtable->fieldList['email']['name']     = 'email';
$config->company->user->dtable->fieldList['email']['title']    = $lang->user->email;
$config->company->user->dtable->fieldList['email']['type']     = 'text';
$config->company->user->dtable->fieldList['email']['width']    = 200;
$config->company->user->dtable->fieldList['email']['sortType'] = true;
$config->company->user->dtable->fieldList['email']['group']    = '4';

$config->company->user->dtable->fieldList['last']['name']     = 'last';
$config->company->user->dtable->fieldList['last']['title']    = $lang->user->last;
$config->company->user->dtable->fieldList['last']['type']     = 'date';
$config->company->user->dtable->fieldList['last']['sortType'] = true;
$config->company->user->dtable->fieldList['last']['group']    = '5';

$config->company->user->dtable->fieldList['visits']['name']     = 'visits';
$config->company->user->dtable->fieldList['visits']['title']    = $lang->user->visits;
$config->company->user->dtable->fieldList['visits']['type']     = 'text';
$config->company->user->dtable->fieldList['visits']['sortType'] = true;
$config->company->user->dtable->fieldList['visits']['group']    = '5';

$config->company->user->dtable->fieldList['actions']['title'] = $lang->actions;
$config->company->user->dtable->fieldList['actions']['type']  = 'actions';
$config->company->user->dtable->fieldList['actions']['list']  = $config->company->user->actionList;
$config->company->user->dtable->fieldList['actions']['menu']  = array_keys($config->company->user->actionList);
