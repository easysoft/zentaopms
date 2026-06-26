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

$lang->epic->categoryList = array();
$lang->epic->categoryList['feature']     = 'Feature';
$lang->epic->categoryList['interface']   = 'API';
$lang->epic->categoryList['performance'] = 'Performance';
$lang->epic->categoryList['safe']        = 'Safety';
$lang->epic->categoryList['experience']  = 'User Experience';
$lang->epic->categoryList['improve']     = 'Improvement';
$lang->epic->categoryList['other']       = 'Others';

$lang->epic->stageList = array();
$lang->epic->stageList[''] = '';
$lang->epic->stageList['wait'] = 'Wait';
if($config->edition == 'ipd')
{
    $lang->epic->stageList['inroadmap'] = 'In Roadmap';
    $lang->epic->stageList['incharter'] = 'In Charter';
}
$lang->epic->stageList['planned']    = 'Planned';
$lang->epic->stageList['projected']  = 'Projected';
$lang->epic->stageList['developing'] = 'Developing';
$lang->epic->stageList['delivering'] = 'Delivering';
$lang->epic->stageList['delivered']  = 'Delivered';
$lang->epic->stageList['closed']     = 'Closed';

$lang->epic->reasonList = array();
$lang->epic->reasonList['']           = '';
$lang->epic->reasonList['done']       = 'Completed';
$lang->epic->reasonList['subdivided'] = 'Decomposed';
$lang->epic->reasonList['duplicate']  = 'Duplicate';
$lang->epic->reasonList['postponed']  = 'Postponed';
$lang->epic->reasonList['willnotdo']  = "Won't Do";
$lang->epic->reasonList['cancel']     = 'Cancelled';
$lang->epic->reasonList['bydesign']   = 'As Designed';
