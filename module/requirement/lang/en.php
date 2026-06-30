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

$lang->requirement->sourceList = array();
$lang->requirement->sourceList['']           = '';
$lang->requirement->sourceList['customer']   = 'Customer';
$lang->requirement->sourceList['user']       = 'User';
$lang->requirement->sourceList['po']         = $lang->productCommon . ' Owner';
$lang->requirement->sourceList['market']     = 'Marketing';
$lang->requirement->sourceList['service']    = 'Customer Service';
$lang->requirement->sourceList['operation']  = 'Operations';
$lang->requirement->sourceList['support']    = 'Technical Support';
$lang->requirement->sourceList['competitor'] = 'Competitor';
$lang->requirement->sourceList['partner']    = 'Partner';
$lang->requirement->sourceList['dev']        = 'Dev Team';
$lang->requirement->sourceList['tester']     = 'Test Team';
$lang->requirement->sourceList['bug']        = 'Bug';
$lang->requirement->sourceList['forum']      = 'Forum';
$lang->requirement->sourceList['other']      = 'Others';

$lang->requirement->priList = array();
$lang->requirement->priList[0] = '';
$lang->requirement->priList[1] = '1';
$lang->requirement->priList[2] = '2';
$lang->requirement->priList[3] = '3';
$lang->requirement->priList[4] = '4';

$lang->requirement->categoryList = array();
$lang->requirement->categoryList['feature']     = 'Feature';
$lang->requirement->categoryList['interface']   = 'API';
$lang->requirement->categoryList['performance'] = 'Performance';
$lang->requirement->categoryList['safe']        = 'Safety';
$lang->requirement->categoryList['experience']  = 'User Experience';
$lang->requirement->categoryList['improve']     = 'Improvement';
$lang->requirement->categoryList['other']       = 'Others';

$lang->requirement->stageList = array();
$lang->requirement->stageList[''] = '';
$lang->requirement->stageList['wait'] = 'Not Started';
if($config->edition == 'ipd')
{
    $lang->requirement->stageList['inroadmap'] = 'In Roadmap';
    $lang->requirement->stageList['incharter'] = 'In Charter';
}
$lang->requirement->stageList['planned']    = 'Planned';
$lang->requirement->stageList['projected']  = 'Initiated';
$lang->requirement->stageList['developing'] = 'In Development';
$lang->requirement->stageList['delivering'] = 'In Delivery';
$lang->requirement->stageList['delivered']  = 'Delivered';
$lang->requirement->stageList['closed']     = 'Closed';

$lang->requirement->reasonList = array();
$lang->requirement->reasonList['']           = '';
$lang->requirement->reasonList['done']       = 'Completed';
$lang->requirement->reasonList['subdivided'] = 'Decomposed';
$lang->requirement->reasonList['duplicate']  = 'Duplicate';
$lang->requirement->reasonList['postponed']  = 'Postponed';
$lang->requirement->reasonList['willnotdo']  = "Won't Do";
$lang->requirement->reasonList['cancel']     = 'Cancelled';
$lang->requirement->reasonList['bydesign']   = 'As Designed';

$lang->requirement->linkStory = 'Link Story';
