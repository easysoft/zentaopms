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
$lang->epic->stageList['defining']   = 'Defining';
$lang->epic->stageList['planning']   = 'Planning';
$lang->epic->stageList['developing'] = 'Developing';
$lang->epic->stageList['delivering'] = 'Delivering';
$lang->epic->stageList['closed']     = 'Closed';
