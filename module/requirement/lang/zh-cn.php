<?php
global $app, $config;
$app->loadLang('story');
$lang->requirement = clone $lang->story;

foreach($lang->requirement as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->requirement->$key = str_replace($lang->SRCommon, $lang->URCommon, $value);
}

$lang->requirement->common = $lang->URCommon;

$lang->requirement->stageList = array();
$lang->requirement->stageList[''] = '';
$lang->requirement->stageList['wait']       = '未开始';
$lang->requirement->stageList['inroadmap']  = '已设路标';
$lang->requirement->stageList['incharter']  = 'Charter立项';
$lang->requirement->stageList['planned']    = '已计划';
$lang->requirement->stageList['projected']  = '研发立项';
$lang->requirement->stageList['developing'] = '研发中';
$lang->requirement->stageList['delivering'] = '交付中';
$lang->requirement->stageList['delivered']  = '已交付';
$lang->requirement->stageList['closed']     = '已关闭';

if($config->edition != 'ipd') unset($lang->requirement->stageList['inroadmap'], $lang->requirement->stageList['incharter']);
