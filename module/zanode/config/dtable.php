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

$config->zanode->dtable->fieldList['actions']['name']  = 'actions';
$config->zanode->dtable->fieldList['actions']['title'] = $lang->actions;
$config->zanode->dtable->fieldList['actions']['type']  = 'actions';
$config->zanode->dtable->fieldList['actions']['menu']  = array('getVNC', 'resume|suspend', 'start|close', 'reboot', 'createSnapshot', 'more' => array('edit'));

$config->zanode->dtable->fieldList['actions']['list']['getVNC']['icon']   = 'remote';
$config->zanode->dtable->fieldList['actions']['list']['getVNC']['hint']   = $lang->zanode->getVNC;
$config->zanode->dtable->fieldList['actions']['list']['getVNC']['target'] = '_blank';
$config->zanode->dtable->fieldList['actions']['list']['getVNC']['url']    = array('module' => 'zanode', 'method' => 'getVNC', 'params' => 'id={id}');

$config->zanode->dtable->fieldList['actions']['list']['resume']['icon']      = 'resume';
$config->zanode->dtable->fieldList['actions']['list']['resume']['hint']      = $lang->zanode->resume;
$config->zanode->dtable->fieldList['actions']['list']['resume']['url']       = array('module' => 'zanode', 'method' => 'resume', 'params' => 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['resume']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['suspend']['icon']      = 'moon';
$config->zanode->dtable->fieldList['actions']['list']['suspend']['hint']      = $lang->zanode->suspend;
$config->zanode->dtable->fieldList['actions']['list']['suspend']['url']       = array('module' => 'zanode', 'method' => 'suspend', 'params' => 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['suspend']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['start']['icon']      = 'play';
$config->zanode->dtable->fieldList['actions']['list']['start']['hint']      = $lang->zanode->start;
$config->zanode->dtable->fieldList['actions']['list']['start']['url']       = array('module' => 'zanode', 'method' => 'start', 'params' => 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['start']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['close']['icon']      = 'off';
$config->zanode->dtable->fieldList['actions']['list']['close']['hint']      = $lang->zanode->close;
$config->zanode->dtable->fieldList['actions']['list']['close']['url']       = array('module' => 'zanode', 'method' => 'close', 'params' => 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['close']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['reboot']['icon']      = 'restart';
$config->zanode->dtable->fieldList['actions']['list']['reboot']['hint']      = $lang->zanode->reboot;
$config->zanode->dtable->fieldList['actions']['list']['reboot']['url']       = array('module' => 'zanode', 'method' => 'reboot', 'params' => 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['reboot']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['icon']        = "plus";
$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['hint']        = $lang->zanode->createSnapshot;
$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['url']         = array('module' => 'zanode', 'method' => 'createSnapshot', 'params' => 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['data-toggle'] = 'modal';
$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['className']   = 'create-snapshot';

$config->zanode->dtable->fieldList['actions']['list']['more']['icon']      = 'ellipsis-v';
$config->zanode->dtable->fieldList['actions']['list']['more']['hint']      = 'more';
$config->zanode->dtable->fieldList['actions']['list']['more']['type']      = 'dropdown';
$config->zanode->dtable->fieldList['actions']['list']['more']['caret']      = false;

$config->zanode->dtable->fieldList['actions']['list']['edit']['icon']      = 'edit';
$config->zanode->dtable->fieldList['actions']['list']['edit']['hint']      = $lang->zanode->edit;
$config->zanode->dtable->fieldList['actions']['list']['edit']['url']       = array('module' => 'zanode', 'method' => 'edit', 'params' => 'zanodeID={id}');
