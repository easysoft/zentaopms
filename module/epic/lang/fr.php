<?php
global $app;
$app->loadLang('story');
$lang->requirement = clone $lang->story;
$lang->requirement->common = $lang->URCommon;

foreach($lang->requirement as $key => $value)
{
    if(!is_string($value)) continue;
    if(strpos($value, $lang->SRCommon) !== false) $lang->requirement->$key = str_replace($lang->SRCommon, $lang->URCommon, $value);
}
