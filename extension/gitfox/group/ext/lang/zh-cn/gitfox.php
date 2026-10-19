<?php
global $config;

if($config->vision == 'gitfox')
{
    $lang->group->organization = '组织';

    $lang->mainNav->organization = "{$lang->group->organization}|company|dynamic|";
}
