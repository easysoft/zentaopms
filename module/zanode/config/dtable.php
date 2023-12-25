<?php
global $lang, $config, $app;
$app->loadLang('zahost');

$config->zanode->dtable = new stdclass();

$config->zanode->dtable->fieldList['id']['title'] = $lang->idAB;
$config->zanode->dtable->fieldList['id']['type']  = 'id';

$config->zanode->dtable->fieldList['name']['type'] = 'name';
$config->zanode->dtable->fieldList['name']['link'] = array('module' => 'zanode', 'method' => 'view', 'params' => 'id={id}');

$config->zanode->dtable->fieldList['type']['name']     = 'hostType';
$config->zanode->dtable->fieldList['type']['title']    = $lang->zahost->type;
$config->zanode->dtable->fieldList['type']['map']      = array('physics' => $this->lang->zanode->typeList['physics'], 'physical' => $this->lang->zanode->typeList['node']);
$config->zanode->dtable->fieldList['type']['sortType'] = true;

$config->zanode->dtable->fieldList['extranet']['name'] = 'extranet';

$config->zanode->dtable->fieldList['cpuCores']['name']     = 'cpuCores';
$config->zanode->dtable->fieldList['cpuCores']['map']      = $config->zanode->os->cpuCores;
$config->zanode->dtable->fieldList['cpuCores']['sortType'] = true;

$config->zanode->dtable->fieldList['memory']['name']     = 'memory';
$config->zanode->dtable->fieldList['memory']['sortType'] = true;

$config->zanode->dtable->fieldList['diskSize']['name']     = 'diskSize';
$config->zanode->dtable->fieldList['diskSize']['sortType'] = true;

$config->zanode->dtable->fieldList['osName']['name']  = 'osName';

$config->zanode->dtable->fieldList['status']['name']     = 'status';
$config->zanode->dtable->fieldList['status']['map']      = $lang->zanode->statusList;
$config->zanode->dtable->fieldList['status']['sortType'] = true;

$config->zanode->dtable->fieldList['hostName']['name']     = 'hostName';
$config->zanode->dtable->fieldList['hostName']['sortType'] = true;

$config->zanode->dtable->fieldList['actions']['name']  = 'actions';
$config->zanode->dtable->fieldList['actions']['title'] = $lang->actions;
$config->zanode->dtable->fieldList['actions']['type']  = 'actions';
$config->zanode->dtable->fieldList['actions']['menu']  = array('getVNC', 'resume|suspend', 'start|close', 'reboot', 'createSnapshot', 'more' => array('edit', 'createImage', 'destroy'));

$config->zanode->dtable->fieldList['actions']['list']['getVNC']['icon']   = 'remote';
$config->zanode->dtable->fieldList['actions']['list']['getVNC']['hint']   = $lang->zanode->getVNC;
$config->zanode->dtable->fieldList['actions']['list']['getVNC']['text']   = $lang->zanode->getVNC;
$config->zanode->dtable->fieldList['actions']['list']['getVNC']['target'] = '_blank';
$config->zanode->dtable->fieldList['actions']['list']['getVNC']['url']    = helper::createLink('zanode', 'getVNC', 'id={id}');

$config->zanode->dtable->fieldList['actions']['list']['resume']['icon']      = 'resume';
$config->zanode->dtable->fieldList['actions']['list']['resume']['hint']      = $lang->zanode->resume;
$config->zanode->dtable->fieldList['actions']['list']['resume']['text']      = $lang->zanode->resumeNode;
$config->zanode->dtable->fieldList['actions']['list']['resume']['url']       = helper::createLink('zanode', 'resume', 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['resume']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['suspend']['icon']      = 'moon';
$config->zanode->dtable->fieldList['actions']['list']['suspend']['hint']      = $lang->zanode->suspend;
$config->zanode->dtable->fieldList['actions']['list']['suspend']['text']      = $lang->zanode->suspendNode;
$config->zanode->dtable->fieldList['actions']['list']['suspend']['url']       = helper::createLink('zanode', 'suspend', 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['suspend']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['start']['icon']      = 'play';
$config->zanode->dtable->fieldList['actions']['list']['start']['hint']      = $lang->zanode->boot;
$config->zanode->dtable->fieldList['actions']['list']['start']['text']      = $lang->zanode->bootNode;
$config->zanode->dtable->fieldList['actions']['list']['start']['url']       = helper::createLink('zanode', 'start', 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['start']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['close']['icon']      = 'off';
$config->zanode->dtable->fieldList['actions']['list']['close']['hint']      = $lang->zanode->shutdown;
$config->zanode->dtable->fieldList['actions']['list']['close']['text']      = $lang->zanode->shutdownNode;
$config->zanode->dtable->fieldList['actions']['list']['close']['url']       = helper::createLink('zanode', 'close', 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['close']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['reboot']['icon']      = 'restart';
$config->zanode->dtable->fieldList['actions']['list']['reboot']['hint']      = $lang->zanode->reboot;
$config->zanode->dtable->fieldList['actions']['list']['reboot']['text']      = $lang->zanode->rebootNode;
$config->zanode->dtable->fieldList['actions']['list']['reboot']['url']       = helper::createLink('zanode', 'reboot', 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['reboot']['className'] = 'ajax-submit';

$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['icon']        = "plus";
$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['hint']        = $lang->zanode->createSnapshot;
$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['text']        = $lang->zanode->createSnapshot;
$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['url']         = helper::createLink('zanode', 'createSnapshot', 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['data-toggle'] = 'modal';
$config->zanode->dtable->fieldList['actions']['list']['createSnapshot']['className']   = 'create-snapshot';

$config->zanode->dtable->fieldList['actions']['list']['edit']['icon'] = 'edit';
$config->zanode->dtable->fieldList['actions']['list']['edit']['hint'] = $lang->zanode->edit;
$config->zanode->dtable->fieldList['actions']['list']['edit']['url']  = helper::createLink('zanode', 'edit', 'zanodeID={id}');

$config->zanode->dtable->fieldList['actions']['list']['createImage']['icon']        = 'export';
$config->zanode->dtable->fieldList['actions']['list']['createImage']['hint']        = $lang->zanode->createImage;
$config->zanode->dtable->fieldList['actions']['list']['createImage']['url']         = array('module' => 'zanode', 'method' => 'createImage', 'params' => 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['createImage']['data-toggle'] = 'modal';

$config->zanode->dtable->fieldList['actions']['list']['destroy']['icon']         = 'trash';
$config->zanode->dtable->fieldList['actions']['list']['destroy']['hint']         = $lang->zanode->destroy;
$config->zanode->dtable->fieldList['actions']['list']['destroy']['url']          = array('module' => 'zanode', 'method' => 'destory', 'params' => 'zanodeID={id}');
$config->zanode->dtable->fieldList['actions']['list']['destroy']['class']        = 'ajax-submit';
$config->zanode->dtable->fieldList['actions']['list']['destroy']['data-confirm'] = $lang->zanode->confirmDelete;

$config->zanode->snapshotDtable = new stdclass();

$config->zanode->snapshotDtable->fieldList['name']['title'] = $lang->zahost->image->name;
$config->zanode->snapshotDtable->fieldList['name']['sort']  = true;

$config->zanode->snapshotDtable->fieldList['status']['title'] = $lang->zahost->status;
$config->zanode->snapshotDtable->fieldList['status']['map']   = $lang->zanode->snapshot->statusList;
$config->zanode->snapshotDtable->fieldList['status']['sort']  = true;

$config->zanode->snapshotDtable->fieldList['createdBy']['title'] = $lang->zahost->createdBy;
$config->zanode->snapshotDtable->fieldList['createdBy']['sort']  = true;

$config->zanode->snapshotDtable->fieldList['createdDate']['title'] = $lang->zahost->createdDate;
$config->zanode->snapshotDtable->fieldList['createdDate']['type']  = 'datetime';
$config->zanode->snapshotDtable->fieldList['createdDate']['sort']  = true;

$config->zanode->snapshotDtable->fieldList['actions']['name']  = 'actions';
$config->zanode->snapshotDtable->fieldList['actions']['title'] = $lang->actions;
$config->zanode->snapshotDtable->fieldList['actions']['type']  = 'actions';
$config->zanode->snapshotDtable->fieldList['actions']['menu']  = array('editSnapshot', 'restoreSnapshot', 'deleteSnapshot');

$config->zanode->snapshotDtable->fieldList['actions']['list']['editSnapshot']['icon']        = 'edit';
$config->zanode->snapshotDtable->fieldList['actions']['list']['editSnapshot']['hint']        = $lang->zanode->editSnapshot;
$config->zanode->snapshotDtable->fieldList['actions']['list']['editSnapshot']['className']   = 'editSnapshot';
$config->zanode->snapshotDtable->fieldList['actions']['list']['editSnapshot']['url']         = array('module' => 'zanode', 'method' => 'editSnapshot', 'params' => 'snapshotID={id}');

$config->zanode->snapshotDtable->fieldList['actions']['list']['restoreSnapshot']['icon']      = 'restart';
$config->zanode->snapshotDtable->fieldList['actions']['list']['restoreSnapshot']['hint']      = $lang->zanode->restoreSnapshot;
$config->zanode->snapshotDtable->fieldList['actions']['list']['restoreSnapshot']['className'] = 'ajax-submit';
$config->zanode->snapshotDtable->fieldList['actions']['list']['restoreSnapshot']['url']       = array('module' => 'zanode', 'method' => 'restoreSnapshot', 'params' => 'nodeID={nodeID}&snapshotID={id}');

$config->zanode->snapshotDtable->fieldList['actions']['list']['deleteSnapshot']['icon']         = 'trash';
$config->zanode->snapshotDtable->fieldList['actions']['list']['deleteSnapshot']['hint']         = $lang->zanode->deleteSnapshot;
$config->zanode->snapshotDtable->fieldList['actions']['list']['deleteSnapshot']['class']        = 'ajax-submit';
$config->zanode->snapshotDtable->fieldList['actions']['list']['deleteSnapshot']['url']          = array('module' => 'zanode', 'method' => 'deleteSnapshot', 'params' => 'snapshotID={id}');
$config->zanode->snapshotDtable->fieldList['actions']['list']['deleteSnapshot']['data-confirm'] = $lang->zanode->confirmDeleteSnapshot;
