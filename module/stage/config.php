<?php
$config->stage->create = new stdclass();
$config->stage->edit   = new stdclass();
if(isset($config->setPercent) and $config->setPercent == 1)
{
    $config->stage->create->requiredFields = 'name,percent,type';
    $config->stage->edit->requiredFields   = 'name,percent,type';
}
else
{
    $config->stage->create->requiredFields = 'name,type';
    $config->stage->edit->requiredFields   = 'name,type';
}
