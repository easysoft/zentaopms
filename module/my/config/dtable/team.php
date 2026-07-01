<?php
$config->my->team = new stdclass();
$config->my->team->dtable = clone $config->company->user->dtable;
$config->my->team->dtable->fieldList['realname']['link'] = array('module' => 'user', 'method' => 'view', 'params' => 'userid={id}&from=my');
if(isset($config->my->team->dtable->fieldList['actions']['list']['edit']['url']['params'])) $config->my->team->dtable->fieldList['actions']['list']['edit']['url']['params'] = 'userID={id}&from=my';

$config->my->team->dtable->fieldList['dept']['name']     = 'dept';
$config->my->team->dtable->fieldList['dept']['title']    = $lang->user->dept;
$config->my->team->dtable->fieldList['dept']['type']     = 'category';
$config->my->team->dtable->fieldList['dept']['sortType'] = true;
$config->my->team->dtable->fieldList['dept']['width']    = '120';
$config->my->team->dtable->fieldList['dept']['group']    = 3;

$config->my->team->dtable->fieldList['type']['name']     = 'type';
$config->my->team->dtable->fieldList['type']['title']    = $lang->user->type;
$config->my->team->dtable->fieldList['type']['type']     = 'category';
$config->my->team->dtable->fieldList['type']['map']      = $lang->user->typeList;
$config->my->team->dtable->fieldList['type']['sortType'] = true;
$config->my->team->dtable->fieldList['type']['width']    = '100';
$config->my->team->dtable->fieldList['type']['group']    = 3;

$config->my->team->dtable->fieldList['join']['name']     = 'join';
$config->my->team->dtable->fieldList['join']['title']    = $lang->user->join;
$config->my->team->dtable->fieldList['join']['type']     = 'date';
$config->my->team->dtable->fieldList['join']['sortType'] = false;
$config->my->team->dtable->fieldList['join']['width']    = '100';
$config->my->team->dtable->fieldList['join']['group']    = 3;

$config->my->team->dtable->fieldList['email']['width'] = '200';

$config->my->team->dtable->fieldList['mobile']['name']     = 'mobile';
$config->my->team->dtable->fieldList['mobile']['title']    = $lang->user->mobile;
$config->my->team->dtable->fieldList['mobile']['type']     = 'text';
$config->my->team->dtable->fieldList['mobile']['sortType'] = true;
$config->my->team->dtable->fieldList['mobile']['width']    = '120';
$config->my->team->dtable->fieldList['mobile']['group']    = 4;

$config->my->team->dtable->fieldList['phone']['width'] = '120';

$config->my->team->dtable->fieldList['qq']['name']     = 'qq';
$config->my->team->dtable->fieldList['qq']['title']    = $lang->user->qq;
$config->my->team->dtable->fieldList['qq']['type']     = 'text';
$config->my->team->dtable->fieldList['qq']['sortType'] = true;
$config->my->team->dtable->fieldList['qq']['width']    = '100';
$config->my->team->dtable->fieldList['qq']['group']    = 4;

$config->my->team->dtable->fieldList['dingding']['name']     = 'dingding';
$config->my->team->dtable->fieldList['dingding']['title']    = $lang->user->dingding;
$config->my->team->dtable->fieldList['dingding']['type']     = 'text';
$config->my->team->dtable->fieldList['dingding']['sortType'] = true;
$config->my->team->dtable->fieldList['dingding']['width']    = '120';
$config->my->team->dtable->fieldList['dingding']['group']    = 4;

$config->my->team->dtable->fieldList['weixin']['name']     = 'weixin';
$config->my->team->dtable->fieldList['weixin']['title']    = $lang->user->weixin;
$config->my->team->dtable->fieldList['weixin']['type']     = 'text';
$config->my->team->dtable->fieldList['weixin']['sortType'] = true;
$config->my->team->dtable->fieldList['weixin']['width']    = '120';
$config->my->team->dtable->fieldList['weixin']['group']    = 4;

$config->my->team->dtable->fieldList['skype']['name']     = 'skype';
$config->my->team->dtable->fieldList['skype']['title']    = $lang->user->skype;
$config->my->team->dtable->fieldList['skype']['type']     = 'text';
$config->my->team->dtable->fieldList['skype']['sortType'] = true;
$config->my->team->dtable->fieldList['skype']['width']    = '120';
$config->my->team->dtable->fieldList['skype']['group']    = 4;

$config->my->team->dtable->fieldList['whatsapp']['name']     = 'whatsapp';
$config->my->team->dtable->fieldList['whatsapp']['title']    = $lang->user->whatsapp;
$config->my->team->dtable->fieldList['whatsapp']['type']     = 'text';
$config->my->team->dtable->fieldList['whatsapp']['sortType'] = true;
$config->my->team->dtable->fieldList['whatsapp']['width']    = '120';
$config->my->team->dtable->fieldList['whatsapp']['group']    = 4;

$config->my->team->dtable->fieldList['slack']['name']     = 'slack';
$config->my->team->dtable->fieldList['slack']['title']    = $lang->user->slack;
$config->my->team->dtable->fieldList['slack']['type']     = 'text';
$config->my->team->dtable->fieldList['slack']['sortType'] = true;
$config->my->team->dtable->fieldList['slack']['width']    = '120';
$config->my->team->dtable->fieldList['slack']['group']    = 4;

$config->my->team->dtable->requiredFields = array('id', 'realname', 'account', 'gender', 'role', 'dept');
if(isset($config->my->team->dtable->fieldList['superior'])) $config->my->team->dtable->requiredFields[] = 'superior';
$config->my->team->dtable->requiredFields = array_merge($config->my->team->dtable->requiredFields, array('email', 'mobile', 'phone', 'last', 'visits', 'actions'));

$teamFields = array('id', 'realname', 'account', 'gender', 'role', 'dept', 'superior', 'type', 'join', 'email', 'mobile', 'phone', 'qq', 'dingding', 'weixin', 'skype', 'whatsapp', 'slack', 'last', 'visits', 'actions');

$defaultFields = array();
foreach($teamFields as $field)
{
    if(!isset($config->my->team->dtable->fieldList[$field])) continue;
    if(!in_array($field, $config->my->team->dtable->defaultField) && !in_array($field, $config->my->team->dtable->requiredFields)) continue;
    $defaultFields[] = $field;
}
$config->my->team->dtable->defaultField = $defaultFields;

foreach($config->my->team->dtable->defaultField as $field)
{
    $config->my->team->dtable->fieldList[$field]['show'] = true;
}
foreach($config->my->team->dtable->requiredFields as $field)
{
    if(!isset($config->my->team->dtable->fieldList[$field])) continue;
    $config->my->team->dtable->fieldList[$field]['required'] = true;
    $config->my->team->dtable->fieldList[$field]['show']     = true;
}

$fieldList = array();
foreach($teamFields as $field)
{
    if(isset($config->my->team->dtable->fieldList[$field])) $fieldList[$field] = $config->my->team->dtable->fieldList[$field];
}
foreach($config->my->team->dtable->fieldList as $field => $fieldConfig)
{
    if(!isset($fieldList[$field])) $fieldList[$field] = $fieldConfig;
}
$config->my->team->dtable->fieldList = $fieldList;
