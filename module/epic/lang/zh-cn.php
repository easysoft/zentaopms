<?php
global $app;
$app->loadLang('story');
$lang->epic = clone $lang->story;
$lang->epic->common = '业务需求';

foreach($lang->epic as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->epic->$key = str_replace($lang->SRCommon, '业务需求', $value);
}
