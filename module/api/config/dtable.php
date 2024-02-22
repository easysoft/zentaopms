<?php
global $lang;
$config->api->dtable = new stdclass();
$config->api->dtable->struct = new stdclass();

$config->api->dtable->struct->fieldList['id']['title']    = $lang->idAB;
$config->api->dtable->struct->fieldList['id']['type']     = 'checkID';
$config->api->dtable->struct->fieldList['id']['sortType'] = 'desc';
$config->api->dtable->struct->fieldList['id']['name']     = 'id';
$config->api->dtable->struct->fieldList['id']['checkbox'] = false;
$config->api->dtable->struct->fieldList['id']['width']    = '60';

$config->api->dtable->struct->fieldList['type']['title']    = $lang->api->structType;
$config->api->dtable->struct->fieldList['type']['type']     = 'category';
$config->api->dtable->struct->fieldList['type']['name']     = 'type';
$config->api->dtable->struct->fieldList['type']['fixed']    = 'left';
$config->api->dtable->struct->fieldList['type']['width']    = '60';
$config->api->dtable->struct->fieldList['type']['sortType'] = true;

$config->api->dtable->struct->fieldList['name']['title']    = $lang->api->structName;
$config->api->dtable->struct->fieldList['name']['type']     = 'title';
$config->api->dtable->struct->fieldList['name']['name']     = 'name';
$config->api->dtable->struct->fieldList['name']['fixed']    = 'left';
$config->api->dtable->struct->fieldList['name']['sortType'] = false;

$config->api->dtable->struct->fieldList['addedName']['title'] = $lang->api->addedBy;
$config->api->dtable->struct->fieldList['addedName']['type']  = 'user';
$config->api->dtable->struct->fieldList['addedName']['name']  = 'addedBy';

$config->api->dtable->struct->fieldList['addedDate']['title'] = $lang->api->structAddedDate;
$config->api->dtable->struct->fieldList['addedDate']['type']  = 'date';
$config->api->dtable->struct->fieldList['addedDate']['name']  = 'addedDate';

$config->api->dtable->struct->fieldList['actions']['title']    = $lang->actions;
$config->api->dtable->struct->fieldList['actions']['type']     = 'actions';
$config->api->dtable->struct->fieldList['actions']['width']    = '100';
$config->api->dtable->struct->fieldList['actions']['sortType'] = false;
$config->api->dtable->struct->fieldList['actions']['fixed']    = 'right';
$config->api->dtable->struct->fieldList['actions']['name']     = 'actions';
$config->api->dtable->struct->fieldList['actions']['menu']     = array('editStruct', 'deleteStruct');

$config->api->dtable->struct->fieldList['actions']['list']['editStruct']['icon'] = 'edit';
$config->api->dtable->struct->fieldList['actions']['list']['editStruct']['hint'] = $lang->api->editStruct;
$config->api->dtable->struct->fieldList['actions']['list']['editStruct']['url']  = helper::createLink('api', 'editStruct', 'lib={lib}&id={id}');

$config->api->dtable->struct->fieldList['actions']['list']['deleteStruct']['icon']         = 'trash';
$config->api->dtable->struct->fieldList['actions']['list']['deleteStruct']['hint']         = $lang->api->deleteStruct;
$config->api->dtable->struct->fieldList['actions']['list']['deleteStruct']['url']          = helper::createLink('api', 'deleteStruct', 'lib={lib}&id={id}');
$config->api->dtable->struct->fieldList['actions']['list']['deleteStruct']['className']    = 'ajax-submit';
$config->api->dtable->struct->fieldList['actions']['list']['deleteStruct']['data-confirm'] = array('message' => $lang->api->confirmDeleteStruct, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');

$config->api->dtable->release = new stdclass();
$config->api->dtable->release->fieldList['id']['name']  = 'id';
$config->api->dtable->release->fieldList['id']['title'] = $lang->idAB;
$config->api->dtable->release->fieldList['id']['type']  = 'id';

$config->api->dtable->release->fieldList['version']['name']  = 'version';
$config->api->dtable->release->fieldList['version']['title'] = $lang->api->version;
$config->api->dtable->release->fieldList['version']['width'] = '100px';
$config->api->dtable->release->fieldList['version']['type']  = 'title';

$config->api->dtable->release->fieldList['desc']['name']  = 'desc';
$config->api->dtable->release->fieldList['desc']['title'] = $lang->api->desc;
$config->api->dtable->release->fieldList['desc']['type']  = 'desc';

$config->api->dtable->release->fieldList['addedBy']['name']  = 'addedBy';
$config->api->dtable->release->fieldList['addedBy']['title'] = $lang->api->addedBy;
$config->api->dtable->release->fieldList['addedBy']['type']  = 'user';

$config->api->dtable->release->fieldList['addedDate']['name']  = 'addedDate';
$config->api->dtable->release->fieldList['addedDate']['title'] = $lang->api->structAddedDate;
$config->api->dtable->release->fieldList['addedDate']['type']  = 'date';

$config->api->dtable->release->fieldList['actions']['name']  = 'actions';
$config->api->dtable->release->fieldList['actions']['title'] = $lang->actions;
$config->api->dtable->release->fieldList['actions']['type']  = 'actions';
$config->api->dtable->release->fieldList['actions']['menu']  = array('deleteRelease');
$config->api->dtable->release->fieldList['actions']['list']['deleteRelease']['icon']         = 'trash';
$config->api->dtable->release->fieldList['actions']['list']['deleteRelease']['hint']         = $lang->delete;
$config->api->dtable->release->fieldList['actions']['list']['deleteRelease']['url']          = helper::createLink('api', 'deleteRelease', 'lib={lib}&id={id}');
$config->api->dtable->release->fieldList['actions']['list']['deleteRelease']['data-confirm'] = array('message' => $lang->custom->notice->confirmDelete, 'icon' => 'icon-exclamation-sign', 'iconClass' => 'warning-pale rounded-full icon-2x');
$config->api->dtable->release->fieldList['actions']['list']['deleteRelease']['className']    = 'ajax-submit';
