<?php
global $lang;

$config->personnel->accessible->dtable = new stdclass();

$config->personnel->accessible->dtable->fieldList['id']['name']  = 'id';
$config->personnel->accessible->dtable->fieldList['id']['title'] = $lang->idAB;
$config->personnel->accessible->dtable->fieldList['id']['type']  = 'ID';
$config->personnel->accessible->dtable->fieldList['id']['align'] = 'left';
$config->personnel->accessible->dtable->fieldList['id']['fixed'] = 'left';

$config->personnel->accessible->dtable->fieldList['realname']['name']     = 'realname';
$config->personnel->accessible->dtable->fieldList['realname']['title']    = $lang->personnel->realName;
$config->personnel->accessible->dtable->fieldList['realname']['type']     = 'title';
$config->personnel->accessible->dtable->fieldList['realname']['minWidth'] = '200';
$config->personnel->accessible->dtable->fieldList['realname']['fixed']    = 'left';
$config->personnel->accessible->dtable->fieldList['realname']['sortType'] = false;

$config->personnel->accessible->dtable->fieldList['dept']['name']   = 'dept';
$config->personnel->accessible->dtable->fieldList['dept']['title']  = $lang->personnel->department;
$config->personnel->accessible->dtable->fieldList['dept']['type']   = 'category';
$config->personnel->accessible->dtable->fieldList['dept']['map']    = array();

$config->personnel->accessible->dtable->fieldList['role']['name']   = 'role';
$config->personnel->accessible->dtable->fieldList['role']['title']  = $lang->personnel->job;
$config->personnel->accessible->dtable->fieldList['role']['type']   = 'category';
$config->personnel->accessible->dtable->fieldList['role']['map']    = $lang->user->roleList;

$config->personnel->accessible->dtable->fieldList['account']['name']   = 'account';
$config->personnel->accessible->dtable->fieldList['account']['title']  = $lang->personnel->userName;
$config->personnel->accessible->dtable->fieldList['account']['type']   = 'text';

$config->personnel->accessible->dtable->fieldList['gender']['name']   = 'gender';
$config->personnel->accessible->dtable->fieldList['gender']['title']  = $lang->personnel->genders;
$config->personnel->accessible->dtable->fieldList['gender']['type']   = 'category';
$config->personnel->accessible->dtable->fieldList['gender']['map']    = $lang->user->genderList;

$config->personnel->whitelist         = new stdclass();
$config->personnel->whitelist->dtable = new stdclass();

$config->personnel->whitelist->actionList['unbindWhitelist']['icon']         = 'unlink';
$config->personnel->whitelist->actionList['unbindWhitelist']['hint']         = $lang->personnel->delete;
$config->personnel->whitelist->actionList['unbindWhitelist']['text']         = $lang->personnel->delete;
$config->personnel->whitelist->actionList['unbindWhitelist']['show']         = true;
$config->personnel->whitelist->actionList['unbindWhitelist']['url']          = array('module' => 'personnel', 'method' => 'unbindWhitelist', 'params' => 'userID={id}&confim=yes');
$config->personnel->whitelist->actionList['unbindWhitelist']['className']    = 'ajax-submit';
$config->personnel->whitelist->actionList['unbindWhitelist']['data-confirm'] = json_encode(array('message' => array('html' => "<strong><i class='icon icon-exclamation-sign text-warning text-lg mr-2'></i>{$lang->personnel->confirmDelete}</strong>")));

$config->personnel->whitelist->dtable->fieldList['id']['name']  = 'id';
$config->personnel->whitelist->dtable->fieldList['id']['title'] = $lang->idAB;
$config->personnel->whitelist->dtable->fieldList['id']['type']  = 'ID';
$config->personnel->whitelist->dtable->fieldList['id']['align'] = 'left';
$config->personnel->whitelist->dtable->fieldList['id']['fixed'] = 'left';

$config->personnel->whitelist->dtable->fieldList['realname']['name']     = 'realname';
$config->personnel->whitelist->dtable->fieldList['realname']['title']    = $lang->user->realname;
$config->personnel->whitelist->dtable->fieldList['realname']['type']     = 'title';
$config->personnel->whitelist->dtable->fieldList['realname']['minWidth'] = '200';
$config->personnel->whitelist->dtable->fieldList['realname']['fixed']    = 'left';
$config->personnel->whitelist->dtable->fieldList['realname']['sortType'] = false;

$config->personnel->whitelist->dtable->fieldList['dept']['name']   = 'dept';
$config->personnel->whitelist->dtable->fieldList['dept']['title']  = $lang->user->dept;
$config->personnel->whitelist->dtable->fieldList['dept']['type']   = 'category';
$config->personnel->whitelist->dtable->fieldList['dept']['map']    = array();
$config->personnel->whitelist->dtable->fieldList['dept']['align']  = 'left';

$config->personnel->whitelist->dtable->fieldList['role']['name']   = 'role';
$config->personnel->whitelist->dtable->fieldList['role']['title']  = $lang->user->role;
$config->personnel->whitelist->dtable->fieldList['role']['type']   = 'category';
$config->personnel->whitelist->dtable->fieldList['role']['map']    = $lang->user->roleList;

$config->personnel->whitelist->dtable->fieldList['phone']['name']   = 'phone';
$config->personnel->whitelist->dtable->fieldList['phone']['title']  = $lang->user->phone;
$config->personnel->whitelist->dtable->fieldList['phone']['type']   = 'text';

$config->personnel->whitelist->dtable->fieldList['qq']['name']   = 'qq';
$config->personnel->whitelist->dtable->fieldList['qq']['title']  = $lang->user->qq;
$config->personnel->whitelist->dtable->fieldList['qq']['type']   = 'text';

$config->personnel->whitelist->dtable->fieldList['weixin']['name']   = 'weixin';
$config->personnel->whitelist->dtable->fieldList['weixin']['title']  = $lang->user->weixin;
$config->personnel->whitelist->dtable->fieldList['weixin']['type']   = 'text';

$config->personnel->whitelist->dtable->fieldList['email']['name']   = 'email';
$config->personnel->whitelist->dtable->fieldList['email']['title']  = $lang->user->email;
$config->personnel->whitelist->dtable->fieldList['email']['type']   = 'text';

$config->personnel->whitelist->dtable->fieldList['actions']['name']       = 'actions';
$config->personnel->whitelist->dtable->fieldList['actions']['title']      = $lang->actions;
$config->personnel->whitelist->dtable->fieldList['actions']['type']       = 'actions';
$config->personnel->whitelist->dtable->fieldList['actions']['fixed']      = 'right';
$config->personnel->whitelist->dtable->fieldList['actions']['sortType']   = false;
$config->personnel->whitelist->dtable->fieldList['actions']['list']       = $config->personnel->whitelist->actionList;
$config->personnel->whitelist->dtable->fieldList['actions']['menu']       = array('unbindWhitelist');
