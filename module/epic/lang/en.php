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
$lang->epic->stageList['wait']       = 'Wait';
$lang->epic->stageList['inroadmap']  = 'In Roadmap';
$lang->epic->stageList['incharter']  = 'In Charter';
$lang->epic->stageList['planned']    = 'Planned';
$lang->epic->stageList['projected']  = 'Projected';
$lang->epic->stageList['developing'] = 'Developing';
$lang->epic->stageList['delivering'] = 'Delivering';
$lang->epic->stageList['delivered']  = 'Delivered';
$lang->epic->stageList['closed']     = 'Closed';

if($config->edition != 'ipd') unset($lang->epic->stageList['inroadmap'], $lang->epic->stageList['incharter']);
