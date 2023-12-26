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

$config->zahost->dtable->fieldList['actions']['list']['browseImage']['icon']        = 'mirror';
$config->zahost->dtable->fieldList['actions']['list']['browseImage']['hint']        = $lang->zahost->image->browseImage;
$config->zahost->dtable->fieldList['actions']['list']['browseImage']['text']        = $lang->zahost->image->browseImage;
$config->zahost->dtable->fieldList['actions']['list']['browseImage']['className']   = 'browseImage';
$config->zahost->dtable->fieldList['actions']['list']['browseImage']['data-toggle'] = 'modal';
$config->zahost->dtable->fieldList['actions']['list']['browseImage']['url']         = helper::createLink('zahost', 'browseImage', 'hostID={hostID}');

$config->zahost->dtable->fieldList['actions']['list']['edit']['icon'] = 'edit';
$config->zahost->dtable->fieldList['actions']['list']['edit']['hint'] = $lang->zahost->edit;
$config->zahost->dtable->fieldList['actions']['list']['edit']['url']  = helper::createLink('zahost', 'edit', 'hostID={hostID}');

$config->zahost->dtable->fieldList['actions']['list']['delete']['icon']         = 'trash';
$config->zahost->dtable->fieldList['actions']['list']['delete']['hint']         = $lang->zahost->delete;
$config->zahost->dtable->fieldList['actions']['list']['delete']['url']          = helper::createLink('zahost', 'delete', 'hostID={hostID}');
$config->zahost->dtable->fieldList['actions']['list']['delete']['className']    = 'ajax-submit';
$config->zahost->dtable->fieldList['actions']['list']['delete']['data-confirm'] = $lang->zahost->confirmDelete;

$config->zahost->imageDtable = new stdclass();

$config->zahost->imageDtable->fieldList['name']['title'] = $lang->zahost->image->name;

$config->zahost->imageDtable->fieldList['osName']['title'] = $lang->zahost->image->os;

$config->zahost->imageDtable->fieldList['status']['title'] = $lang->zahost->status;
$config->zahost->imageDtable->fieldList['status']['map']   = $lang->zahost->image->statusList;

$config->zahost->imageDtable->fieldList['path']['title'] = $lang->zahost->image->path;

$config->zahost->imageDtable->fieldList['progress']['title'] = $lang->zahost->image->progress;

$config->zahost->imageDtable->fieldList['actions']['name']  = 'actions';
$config->zahost->imageDtable->fieldList['actions']['title'] = $lang->actions;
$config->zahost->imageDtable->fieldList['actions']['type']  = 'actions';
$config->zahost->imageDtable->fieldList['actions']['menu']  = array('downloadImage', 'cancelDownload');

$config->zahost->imageDtable->fieldList['actions']['list']['downloadImage']['icon']      = 'download';
$config->zahost->imageDtable->fieldList['actions']['list']['downloadImage']['hint']      = $lang->zahost->image->downloadImage;
$config->zahost->imageDtable->fieldList['actions']['list']['downloadImage']['url']       = array('module' => 'zahost', 'method' => 'downloadImage', 'params' => 'hostID={hostID}&imageID={id}');
$config->zahost->imageDtable->fieldList['actions']['list']['downloadImage']['className'] = 'ajax-submit';

$config->zahost->imageDtable->fieldList['actions']['list']['cancelDownload']['icon']         = 'ban-circle';
$config->zahost->imageDtable->fieldList['actions']['list']['cancelDownload']['hint']         = $lang->zahost->cancel;
$config->zahost->imageDtable->fieldList['actions']['list']['cancelDownload']['url']          = array('module' => 'zahost', 'method' => 'cancelDownload', 'params' => 'imageID={id}');
$config->zahost->imageDtable->fieldList['actions']['list']['cancelDownload']['className']    = 'ajax-submit';
$config->zahost->imageDtable->fieldList['actions']['list']['cancelDownload']['data-confirm'] = $lang->zahost->cancelDelete;
