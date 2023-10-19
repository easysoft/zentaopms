<?php
global $lang;

$config->zahost->dtable = new stdclass();

$config->zahost->dtable->fieldList['id']['title'] = $lang->idAB;
$config->zahost->dtable->fieldList['id']['name']  = 'hostID';
$config->zahost->dtable->fieldList['id']['type']  = 'id';

$config->zahost->dtable->fieldList['name']['type'] = 'name';
$config->zahost->dtable->fieldList['name']['link'] = array('module' => 'zahost', 'method' => 'view', 'params' => 'id={hostID}');

$config->zahost->dtable->fieldList['type']['name']     = 'hostType';
$config->zahost->dtable->fieldList['type']['title']    = $lang->zahost->type;
$config->zahost->dtable->fieldList['type']['map']      = $lang->zahost->zaHostTypeList;
$config->zahost->dtable->fieldList['type']['sortType'] = true;

$config->zahost->dtable->fieldList['extranet']['name'] = 'extranet';

$config->zahost->dtable->fieldList['cpuCores']['name']     = 'cpuCores';
$config->zahost->dtable->fieldList['cpuCores']['sortType'] = true;

$config->zahost->dtable->fieldList['memory']['name']     = 'memory';
$config->zahost->dtable->fieldList['memory']['sortType'] = true;

$config->zahost->dtable->fieldList['diskSize']['name']     = 'diskSize';
$config->zahost->dtable->fieldList['diskSize']['sortType'] = true;

$config->zahost->dtable->fieldList['vsoft']['name'] = 'vsoft';
$config->zahost->dtable->fieldList['vsoft']['map']  = $lang->zahost->softwareList;

$config->zahost->dtable->fieldList['status']['name']     = 'status';
$config->zahost->dtable->fieldList['status']['map']      = $lang->zahost->statusList;
$config->zahost->dtable->fieldList['status']['sortType'] = true;

$config->zahost->dtable->fieldList['heartbeat']['title'] = $lang->zahost->registerDate;
$config->zahost->dtable->fieldList['heartbeat']['type']  = 'datetime';

$config->zahost->dtable->fieldList['actions']['name']  = 'actions';
$config->zahost->dtable->fieldList['actions']['title'] = $lang->actions;
$config->zahost->dtable->fieldList['actions']['type']  = 'actions';
$config->zahost->dtable->fieldList['actions']['menu']  = array('browseImage', 'edit', 'delete');

$config->zahost->dtable->fieldList['actions']['list']['browseImage']['icon'] = 'mirror';
$config->zahost->dtable->fieldList['actions']['list']['browseImage']['hint'] = $lang->zahost->image->browseImage;
$config->zahost->dtable->fieldList['actions']['list']['browseImage']['className'] = 'browseImage';

$config->zahost->dtable->fieldList['actions']['list']['edit']['icon'] = 'edit';
$config->zahost->dtable->fieldList['actions']['list']['edit']['hint'] = $lang->zahost->edit;
$config->zahost->dtable->fieldList['actions']['list']['edit']['url']  = array('module' => 'zahost', 'method' => 'edit', 'params' => 'hostID={hostID}');

$config->zahost->dtable->fieldList['actions']['list']['delete']['icon']      = 'trash';
$config->zahost->dtable->fieldList['actions']['list']['delete']['hint']      = $lang->zahost->delete;
$config->zahost->dtable->fieldList['actions']['list']['delete']['url']       = array('module' => 'zahost', 'method' => 'delete', 'params' => 'hostID={hostID}');
$config->zahost->dtable->fieldList['actions']['list']['delete']['className'] = 'ajax-submit';
