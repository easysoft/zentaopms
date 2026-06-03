<?php
$config->my->project = new stdclass();
$config->my->project->dtable = $config->project->dtable;

if($isEn)
{
    $config->my->project->dtable->fieldList['executionCount']['width'] = '120';
    $config->my->project->dtable->fieldList['begin']['width']          = '110';
    $config->my->project->dtable->fieldList['end']['width']            = '120';
    $config->my->project->dtable->fieldList['progress']['width']       = '80';
}
