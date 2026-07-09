<?php
$config->company->user = new stdclass();
$config->company->user->actionList = array();

if(!empty($config->sso->turnon))
{
    $config->company->user->actionList['unbind']['icon']         = 'unlink';
    $config->company->user->actionList['unbind']['text']         = $lang->user->unbind;
    $config->company->user->actionList['unbind']['hint']         = $lang->user->unbind;
    $config->company->user->actionList['unbind']['url']          = array('module' => 'user', 'method' => 'unbind', 'params' => 'userID={id}');
    $config->company->user->actionList['unbind']['className']    = 'ajax-submit';
    $config->company->user->actionList['unbind']['data-confirm'] = $lang->user->confirmUnbind;
}

$config->company->user->actionList['unlock']['icon']         = 'unlock';
$config->company->user->actionList['unlock']['text']         = $lang->user->unlock;
$config->company->user->actionList['unlock']['hint']         = $lang->user->unlock;
$config->company->user->actionList['unlock']['url']          = array('module' => 'user', 'method' => 'unlock', 'params' => 'userID={id}');
$config->company->user->actionList['unlock']['className']    = 'ajax-submit';
$config->company->user->actionList['unlock']['data-confirm'] = $lang->user->confirmUnlock;

$config->company->user->actionList['edit']['icon'] = 'edit';
$config->company->user->actionList['edit']['text'] = $lang->user->edit;
$config->company->user->actionList['edit']['hint'] = $lang->user->edit;
$config->company->user->actionList['edit']['url']  = array('module' => 'user', 'method' => 'edit', 'params' => 'userID={id}&from=company');

$config->company->user->actionList['delete']['icon']        = 'trash';
$config->company->user->actionList['delete']['text']        = $lang->user->delete;
$config->company->user->actionList['delete']['hint']        = $lang->user->delete;
$config->company->user->actionList['delete']['url']         = array('module' => 'user', 'method' => 'delete', 'params' => 'userID={id}');
$config->company->user->actionList['delete']['data-toggle'] = 'modal';

$config->company->user->dtable = new stdclass();
$config->company->user->dtable->requiredFields = array();
$config->company->user->dtable->defaultField   = array();
$config->company->user->dtable->fieldList['id']['name']     = 'id';
$config->company->user->dtable->fieldList['id']['title']    = $lang->idAB;
$config->company->user->dtable->fieldList['id']['type']     = 'checkID';
$config->company->user->dtable->fieldList['id']['fixed']    = 'left';
$config->company->user->dtable->fieldList['id']['sortType'] = true;
$config->company->user->dtable->fieldList['id']['checkbox'] = true;
$config->company->user->dtable->fieldList['id']['group']    = 1;

$config->company->user->dtable->fieldList['realname']['name']     = 'realname';
$config->company->user->dtable->fieldList['realname']['title']    = $lang->user->realname;
$config->company->user->dtable->fieldList['realname']['type']     = 'text';
$config->company->user->dtable->fieldList['realname']['fixed']    = 'left';
$config->company->user->dtable->fieldList['realname']['sortType'] = true;
$config->company->user->dtable->fieldList['realname']['group']    = 1;

$config->company->user->dtable->fieldList['account']['name']     = 'account';
$config->company->user->dtable->fieldList['account']['title']    = $lang->user->account;
$config->company->user->dtable->fieldList['account']['type']     = 'text';
$config->company->user->dtable->fieldList['account']['sortType'] = true;
$config->company->user->dtable->fieldList['account']['group']    = 1;

$config->company->user->dtable->fieldList['gender']['name']     = 'gender';
$config->company->user->dtable->fieldList['gender']['title']    = $lang->user->gender;
$config->company->user->dtable->fieldList['gender']['type']     = 'category';
$config->company->user->dtable->fieldList['gender']['map']      = $lang->user->genderList;
$config->company->user->dtable->fieldList['gender']['sortType'] = true;
$config->company->user->dtable->fieldList['gender']['group']    = 2;

$config->company->user->dtable->fieldList['role']['name']     = 'role';
$config->company->user->dtable->fieldList['role']['title']    = $lang->user->role;
$config->company->user->dtable->fieldList['role']['type']     = 'category';
$config->company->user->dtable->fieldList['role']['map']      = $lang->user->roleList;
$config->company->user->dtable->fieldList['role']['sortType'] = true;
$config->company->user->dtable->fieldList['role']['group']    = 3;
if($app->getClientLang() == 'en') $config->company->user->dtable->fieldList['role']['width'] = '120';

$config->company->user->dtable->fieldList['dept']['name']     = 'dept';
$config->company->user->dtable->fieldList['dept']['title']    = $lang->user->dept;
$config->company->user->dtable->fieldList['dept']['type']     = 'category';
$config->company->user->dtable->fieldList['dept']['sortType'] = true;
$config->company->user->dtable->fieldList['dept']['width']    = '120';
$config->company->user->dtable->fieldList['dept']['group']    = 3;

$config->company->user->dtable->fieldList['type']['name']     = 'type';
$config->company->user->dtable->fieldList['type']['title']    = $lang->user->type;
$config->company->user->dtable->fieldList['type']['type']     = 'category';
$config->company->user->dtable->fieldList['type']['map']      = $lang->user->typeList;
$config->company->user->dtable->fieldList['type']['sortType'] = true;
$config->company->user->dtable->fieldList['type']['width']    = '100';
$config->company->user->dtable->fieldList['type']['group']    = 3;

$config->company->user->dtable->fieldList['join']['name']     = 'join';
$config->company->user->dtable->fieldList['join']['title']    = $lang->user->join;
$config->company->user->dtable->fieldList['join']['type']     = 'date';
$config->company->user->dtable->fieldList['join']['sortType'] = false;
$config->company->user->dtable->fieldList['join']['width']    = '100';
$config->company->user->dtable->fieldList['join']['group']    = 3;

$config->company->user->dtable->fieldList['phone']['name']     = 'phone';
$config->company->user->dtable->fieldList['phone']['title']    = $lang->user->phone;
$config->company->user->dtable->fieldList['phone']['type']     = 'text';
$config->company->user->dtable->fieldList['phone']['sortType'] = true;
$config->company->user->dtable->fieldList['phone']['width']    = '120';
$config->company->user->dtable->fieldList['phone']['group']    = 4;

$config->company->user->dtable->fieldList['mobile']['name']     = 'mobile';
$config->company->user->dtable->fieldList['mobile']['title']    = $lang->user->mobile;
$config->company->user->dtable->fieldList['mobile']['type']     = 'text';
$config->company->user->dtable->fieldList['mobile']['sortType'] = true;
$config->company->user->dtable->fieldList['mobile']['width']    = '120';
$config->company->user->dtable->fieldList['mobile']['group']    = 4;

$config->company->user->dtable->fieldList['qq']['name']     = 'qq';
$config->company->user->dtable->fieldList['qq']['title']    = $lang->user->qq;
$config->company->user->dtable->fieldList['qq']['type']     = 'text';
$config->company->user->dtable->fieldList['qq']['sortType'] = true;
$config->company->user->dtable->fieldList['qq']['width']    = '100';
$config->company->user->dtable->fieldList['qq']['group']    = 4;

$config->company->user->dtable->fieldList['dingding']['name']     = 'dingding';
$config->company->user->dtable->fieldList['dingding']['title']    = $lang->user->dingding;
$config->company->user->dtable->fieldList['dingding']['type']     = 'text';
$config->company->user->dtable->fieldList['dingding']['sortType'] = true;
$config->company->user->dtable->fieldList['dingding']['width']    = '120';
$config->company->user->dtable->fieldList['dingding']['group']    = 4;

$config->company->user->dtable->fieldList['weixin']['name']     = 'weixin';
$config->company->user->dtable->fieldList['weixin']['title']    = $lang->user->weixin;
$config->company->user->dtable->fieldList['weixin']['type']     = 'text';
$config->company->user->dtable->fieldList['weixin']['sortType'] = true;
$config->company->user->dtable->fieldList['weixin']['width']    = '120';
$config->company->user->dtable->fieldList['weixin']['group']    = 4;

$config->company->user->dtable->fieldList['skype']['name']     = 'skype';
$config->company->user->dtable->fieldList['skype']['title']    = $lang->user->skype;
$config->company->user->dtable->fieldList['skype']['type']     = 'text';
$config->company->user->dtable->fieldList['skype']['sortType'] = true;
$config->company->user->dtable->fieldList['skype']['width']    = '120';
$config->company->user->dtable->fieldList['skype']['group']    = 4;

$config->company->user->dtable->fieldList['whatsapp']['name']     = 'whatsapp';
$config->company->user->dtable->fieldList['whatsapp']['title']    = $lang->user->whatsapp;
$config->company->user->dtable->fieldList['whatsapp']['type']     = 'text';
$config->company->user->dtable->fieldList['whatsapp']['sortType'] = true;
$config->company->user->dtable->fieldList['whatsapp']['width']    = '120';
$config->company->user->dtable->fieldList['whatsapp']['group']    = 4;

$config->company->user->dtable->fieldList['slack']['name']     = 'slack';
$config->company->user->dtable->fieldList['slack']['title']    = $lang->user->slack;
$config->company->user->dtable->fieldList['slack']['type']     = 'text';
$config->company->user->dtable->fieldList['slack']['sortType'] = true;
$config->company->user->dtable->fieldList['slack']['width']    = '120';
$config->company->user->dtable->fieldList['slack']['group']    = 4;

$config->company->user->dtable->fieldList['email']['name']     = 'email';
$config->company->user->dtable->fieldList['email']['title']    = $lang->user->email;
$config->company->user->dtable->fieldList['email']['type']     = 'text';
$config->company->user->dtable->fieldList['email']['width']    = '200';
$config->company->user->dtable->fieldList['email']['sortType'] = true;
$config->company->user->dtable->fieldList['email']['group']    = 4;

$config->company->user->dtable->fieldList['last']['name']     = 'last';
$config->company->user->dtable->fieldList['last']['title']    = $lang->user->last;
$config->company->user->dtable->fieldList['last']['type']     = 'date';
$config->company->user->dtable->fieldList['last']['sortType'] = true;
$config->company->user->dtable->fieldList['last']['group']    = 5;

$config->company->user->dtable->fieldList['visits']['name']     = 'visits';
$config->company->user->dtable->fieldList['visits']['title']    = $lang->user->visits;
$config->company->user->dtable->fieldList['visits']['type']     = 'text';
$config->company->user->dtable->fieldList['visits']['sortType'] = true;
$config->company->user->dtable->fieldList['visits']['group']    = 5;

$config->company->user->dtable->fieldList['actions']['name']     = 'actions';
$config->company->user->dtable->fieldList['actions']['title']    = $lang->actions;
$config->company->user->dtable->fieldList['actions']['type']     = 'actions';
$config->company->user->dtable->fieldList['actions']['width']    = '120';
$config->company->user->dtable->fieldList['actions']['fixed']    = 'right';
$config->company->user->dtable->fieldList['actions']['sortType'] = false;
$config->company->user->dtable->fieldList['actions']['list']     = $config->company->user->actionList;
$config->company->user->dtable->fieldList['actions']['menu']     = array_keys($config->company->user->actionList);

$config->company->user->dtable->defaultField = array('id', 'realname', 'account', 'gender', 'role', 'dept', 'email', 'mobile', 'phone');
$config->company->user->dtable->defaultField = array_merge($config->company->user->dtable->defaultField, array(!empty($this->config->isINT) ? 'skype' : 'qq'));
$config->company->user->dtable->defaultField = array_merge($config->company->user->dtable->defaultField, array('last', 'visits', 'actions'));

$config->company->user->dtable->requiredFields = array('id', 'realname', 'account', 'gender', 'role', 'dept', 'email', 'mobile', 'phone', 'last', 'visits', 'actions');
foreach($config->company->user->dtable->defaultField as $field)
{
    $config->company->user->dtable->fieldList[$field]['show'] = true;
}
foreach($config->company->user->dtable->requiredFields as $field)
{
    $config->company->user->dtable->fieldList[$field]['required'] = true;
    $config->company->user->dtable->fieldList[$field]['show']     = true;
}

$userFields = array('id', 'realname', 'account', 'gender', 'role', 'dept', 'superior', 'type', 'join', 'email', 'mobile', 'phone', 'qq', 'dingding', 'weixin', 'skype', 'whatsapp', 'slack', 'last', 'visits', 'actions');
$fieldList = array();
foreach($userFields as $field)
{
    if(isset($config->company->user->dtable->fieldList[$field])) $fieldList[$field] = $config->company->user->dtable->fieldList[$field];
}
foreach($config->company->user->dtable->fieldList as $field => $fieldConfig)
{
    if(!isset($fieldList[$field])) $fieldList[$field] = $fieldConfig;
}
$config->company->user->dtable->fieldList = $fieldList;

if(!isset($config->company->browse)) $config->company->browse = new stdclass();
$config->company->browse->dtable = $config->company->user->dtable;
