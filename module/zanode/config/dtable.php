<?php
global $lang, $config, $app;
$app->loadLang('zahost');

$config->zanode->dtable = new stdclass();

$config->zanode->dtable->fieldList['id']['title'] = $lang->idAB;
$config->zanode->dtable->fieldList['id']['type']  = 'id';

$config->zanode->dtable->fieldList['name']['type'] = 'title';
$config->zanode->dtable->fieldList['name']['link'] = array('module' => 'zanode', 'method' => 'view', 'params' => 'id={id}');

$config->zanode->dtable->fieldList['type']['name']  = 'hostType';
$config->zanode->dtable->fieldList['type']['title'] = $lang->zahost->type;
$config->zanode->dtable->fieldList['type']['sortType'] = true;

$config->zanode->dtable->fieldList['extranet']['name'] = 'extranet';

$config->zanode->dtable->fieldList['cpuCores']['name']     = 'cpuCores';
$config->zanode->dtable->fieldList['cpuCores']['map']      = $config->zanode->os->cpuCores;
$config->zanode->dtable->fieldList['cpuCores']['sortType'] = true;

$config->zanode->dtable->fieldList['memory']['name']     = 'memory';
$config->zanode->dtable->fieldList['memory']['sortType'] = true;

$config->zanode->dtable->fieldList['diskSize']['name']     = 'diskSize';
$config->zanode->dtable->fieldList['diskSize']['sortType'] = true;

$config->zanode->dtable->fieldList['osName']['name'] = 'osName';

$config->zanode->dtable->fieldList['status']['name']     = 'status';
$config->zanode->dtable->fieldList['status']['map']      = $lang->zanode->statusList;
$config->zanode->dtable->fieldList['status']['sortType'] = true;

$config->zanode->dtable->fieldList['hostName']['name']     = 'hostName';
$config->zanode->dtable->fieldList['hostName']['sortType'] = true;
