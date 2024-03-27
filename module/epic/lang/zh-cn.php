<?php
global $app;
$app->loadLang('story');
$lang->epic = clone $lang->story;
$lang->epic->common = $lang->ERCommon;

foreach($lang->epic as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->epic->$key = str_replace($lang->SRCommon, $lang->ERCommon, $value);
}

$lang->epic->stageList = array();
$lang->epic->stageList[''] = '';
$lang->epic->stageList['defining']   = '定义中';
$lang->epic->stageList['planning']   = '规划中';
$lang->epic->stageList['developing'] = '研发中';
$lang->epic->stageList['delivering'] = '交付中';
$lang->epic->stageList['closed']     = '已关闭';
