<?php
$config->testsuite->dtable = new stdclass();

global $lang;

$config->testsuite->actionList['linkCase']['icon']  = 'link';
$config->testsuite->actionList['linkCase']['hint']  = $lang->testsuite->linkCase;
$config->testsuite->actionList['linkCase']['text']  = $lang->testsuite->linkCase;
$config->testsuite->actionList['linkCase']['url']   = helper::createLink('testsuite', 'linkCase', 'suiteID={id}');
$config->testsuite->actionList['linkCase']['order'] = 5;
$config->testsuite->actionList['linkCase']['show']  = 'clickable';

$config->testsuite->actionList['edit']['icon']        = 'edit';
$config->testsuite->actionList['edit']['hint']        = $lang->testsuite->edit;
$config->testsuite->actionList['edit']['text']        = $lang->testsuite->edit;
$config->testsuite->actionList['edit']['url']         = helper::createLink('testsuite', 'edit', 'suiteID={id}', '', true);
$config->testsuite->actionList['edit']['data-toggle'] = 'modal';
$config->testsuite->actionList['edit']['order']       = 5;
$config->testsuite->actionList['edit']['show']        = 'clickable';

$config->testsuite->actionList['delete']['icon']  = 'trash';
$config->testsuite->actionList['delete']['hint']  = $lang->testsuite->delete;
$config->testsuite->actionList['delete']['text']  = $lang->testsuite->delete;
$config->testsuite->actionList['delete']['url']   = helper::createLink('testsuite', 'delete', 'suiteID={id}');
$config->testsuite->actionList['delete']['order'] = 10;
$config->testsuite->actionList['delete']['show']  = 'clickable';

$config->testsuite->dtable->fieldList['id']['name']  = 'id';
$config->testsuite->dtable->fieldList['id']['title'] = $lang->idAB;
$config->testsuite->dtable->fieldList['id']['type']  = 'ID';
$config->testsuite->dtable->fieldList['id']['align'] = 'left';
$config->testsuite->dtable->fieldList['id']['fixed'] = 'left';

$config->testsuite->dtable->fieldList['name']['name']     = 'name';
$config->testsuite->dtable->fieldList['name']['title']    = $lang->testsuite->name;
$config->testsuite->dtable->fieldList['name']['type']     = 'title';
$config->testsuite->dtable->fieldList['name']['minWidth'] = '200';
$config->testsuite->dtable->fieldList['name']['fixed']    = 'left';

$config->testsuite->dtable->fieldList['desc']['name']  = 'desc';
$config->testsuite->dtable->fieldList['desc']['title'] = $lang->testsuite->desc;
$config->testsuite->dtable->fieldList['desc']['type']  = 'text';

$config->testsuite->dtable->fieldList['addedBy']['name']     = 'addedBy';
$config->testsuite->dtable->fieldList['addedBy']['title']    = $lang->testsuite->addedBy;
$config->testsuite->dtable->fieldList['addedBy']['type']     = 'user';
$config->testsuite->dtable->fieldList['addedBy']['sortType'] = true;
$config->testsuite->dtable->fieldList['addedBy']['align']    = 'left';

$config->testsuite->dtable->fieldList['addedDate']['name']     = 'addedDate';
$config->testsuite->dtable->fieldList['addedDate']['title']    = $lang->testsuite->addedDate;
$config->testsuite->dtable->fieldList['addedDate']['type']     = 'datetime';
$config->testsuite->dtable->fieldList['addedDate']['sortType'] = true;

$config->testsuite->dtable->fieldList['actions']['name']       = 'actions';
$config->testsuite->dtable->fieldList['actions']['title']      = $lang->actions;
$config->testsuite->dtable->fieldList['actions']['type']       = 'actions';
$config->testsuite->dtable->fieldList['actions']['width']      = '140';
$config->testsuite->dtable->fieldList['actions']['fixed']      = 'right';
$config->testsuite->dtable->fieldList['actions']['sortType']   = false;
$config->testsuite->dtable->fieldList['actions']['list']       = $config->testsuite->actionList;
$config->testsuite->dtable->fieldList['actions']['menu']       = array('linkCase', 'edit', 'delete');
