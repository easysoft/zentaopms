<?php
global $config;

if($config->vision == 'gitfox')
{
    $lang->group->organization = 'Organization';

    $lang->mainNav->organization = "{$lang->group->organization}|company|dynamic|";
}
