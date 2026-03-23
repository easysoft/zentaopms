<?php
global $app, $config;
$app->loadLang('story');
$lang->epic = clone $lang->story;

foreach($lang->epic as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->epic->$key = str_replace($lang->SRCommon, $lang->ERCommon, $value);
}

$lang->epic->common = $lang->ERCommon;

$lang->epic->stageList = array();
$lang->epic->stageList[''] = '';
$lang->epic->stageList['wait'] = 'Not started';
if($config->edition == 'ipd')
{
    $lang->epic->stageList['inroadmap'] = 'In Roadmap';
    $lang->epic->stageList['incharter'] = 'In Charter';
}
$lang->epic->stageList['planned']    = 'Planned';
$lang->epic->stageList['projected']  = 'Projected';
$lang->epic->stageList['developing'] = 'In development';
$lang->epic->stageList['delivering'] = 'Delivering';
$lang->epic->stageList['delivered']  = 'Delivered';
$lang->epic->stageList['closed']     = 'Closed';
