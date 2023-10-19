<?php
global $lang;
$config->group->dtable = new stdclass();

$config->group->dtable->fieldList['id']['title']    = $lang->idAB;
$config->group->dtable->fieldList['id']['name']     = 'id';
$config->group->dtable->fieldList['id']['type']     = 'id';
$config->group->dtable->fieldList['id']['sortType'] = false;

$config->group->dtable->fieldList['name']['title']    = $lang->group->name;
$config->group->dtable->fieldList['name']['name']     = 'name';
$config->group->dtable->fieldList['name']['type']     = 'title';
$config->group->dtable->fieldList['name']['sortType'] = false;

$config->group->dtable->fieldList['desc']['title'] = $lang->group->desc;
$config->group->dtable->fieldList['desc']['name']  = 'desc';
$config->group->dtable->fieldList['desc']['type']  = 'desc';
$config->group->dtable->fieldList['desc']['hint']  = true;

$config->group->dtable->fieldList['users']['title'] = $lang->group->users;
$config->group->dtable->fieldList['users']['name']  = 'users';
$config->group->dtable->fieldList['users']['type']  = 'desc';
$config->group->dtable->fieldList['users']['hint']  = true;

$config->group->dtable->fieldList['actions']['title'] = $lang->actions;
$config->group->dtable->fieldList['actions']['name']  = 'actions';
$config->group->dtable->fieldList['actions']['type']  = 'actions';
$config->group->dtable->fieldList['actions']['list']  = $config->group->actionList;
$config->group->dtable->fieldList['actions']['menu']  = array('manageView', 'managePriv', 'manageProjectAdmin|manageMember', 'edit', 'copy', 'delete');
