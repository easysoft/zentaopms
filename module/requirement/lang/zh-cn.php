<?php
global $app;
$app->loadLang('story');
$lang->requirement = clone $lang->story;
$lang->requirement->common = $lang->URCommon;

foreach($lang->requirement as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->requirement->$key = str_replace($lang->SRCommon, $lang->URCommon, $value);
}

$lang->requirement->stageList = array();
$lang->requirement->stageList[''] = '';
$lang->requirement->stageList['defining']   = '定义中';
$lang->requirement->stageList['planning']   = '规划中';
$lang->requirement->stageList['developing'] = '研发中';
$lang->requirement->stageList['delivering'] = '交付中';
$lang->requirement->stageList['closed']     = '已关闭';
