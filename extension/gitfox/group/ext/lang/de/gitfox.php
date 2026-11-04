<?php
global $config;

if($config->vision == 'gitfox')
{
    $lang->group->organization = 'Organisation';

    $lang->mainNav->organization = "{$lang->group->organization}|company|dynamic|";
}
