<?php
global $app;
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
$lang->requirement->stageList['wait']       = 'Wait';
$lang->requirement->stageList['planned']    = 'Planned';
$lang->requirement->stageList['projected']  = 'Projected';
$lang->requirement->stageList['developing'] = 'Developing';
$lang->requirement->stageList['delivering'] = 'Delivering';
$lang->requirement->stageList['delivered']  = 'Delivered';
$lang->requirement->stageList['closed']     = 'Closed';
