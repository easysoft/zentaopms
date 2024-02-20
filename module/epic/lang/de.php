<?php
global $app;
$app->loadLang('story');
$lang->epic = clone $lang->story;
$lang->epic->common = 'Epic';

foreach($lang->epic as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->epic->$key = str_replace($lang->SRCommon, $lang->URCommon, $value);
}
