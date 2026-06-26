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

$lang->epic->sourceList = array();
$lang->epic->sourceList['']           = '';
$lang->epic->sourceList['customer']   = 'Customer';
$lang->epic->sourceList['user']       = 'User';
$lang->epic->sourceList['po']         = $lang->productCommon . ' Owner';
$lang->epic->sourceList['market']     = 'Marketing';
$lang->epic->sourceList['service']    = 'Customer Service';
$lang->epic->sourceList['operation']  = 'Operations';
$lang->epic->sourceList['support']    = 'Technical Support';
$lang->epic->sourceList['competitor'] = 'Competitor';
$lang->epic->sourceList['partner']    = 'Partner';
$lang->epic->sourceList['dev']        = 'Dev Team';
$lang->epic->sourceList['tester']     = 'Test Team';
$lang->epic->sourceList['bug']        = 'Bug';
$lang->epic->sourceList['forum']      = 'Forum';
$lang->epic->sourceList['other']      = 'Others';

$lang->epic->priList = array();
$lang->epic->priList[0] = '';
$lang->epic->priList[1] = '1';
$lang->epic->priList[2] = '2';
$lang->epic->priList[3] = '3';
$lang->epic->priList[4] = '4';

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
