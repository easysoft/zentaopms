<?php
$config->devopsspace->dtable = new stdclass();

$config->devopsspace->dtable->fieldList['id']['title']    = 'ID';
$config->devopsspace->dtable->fieldList['id']['name']     = 'id';
$config->devopsspace->dtable->fieldList['id']['type']     = 'id';
$config->devopsspace->dtable->fieldList['id']['sortType'] = false;

$config->devopsspace->dtable->fieldList['name']['title']    = $lang->devopsspace->name;
$config->devopsspace->dtable->fieldList['name']['name']     = 'name';
$config->devopsspace->dtable->fieldList['name']['type']     = 'title';
$config->devopsspace->dtable->fieldList['name']['flex']     = 4;
$config->devopsspace->dtable->fieldList['name']['hint']     = true;
$config->devopsspace->dtable->fieldList['name']['sortType'] = false;
$config->devopsspace->dtable->fieldList['name']['url']      = helper::createLink('devopsspace', 'view', 'id={id}');;
$config->devopsspace->dtable->fieldList['name']['group']    = 1;

$config->devopsspace->dtable->fieldList['owner']['title']    = $lang->devopsspace->owner;
$config->devopsspace->dtable->fieldList['owner']['name']     = 'owner';
$config->devopsspace->dtable->fieldList['owner']['type']     = 'user';
$config->devopsspace->dtable->fieldList['owner']['sortType'] = false;
$config->devopsspace->dtable->fieldList['owner']['group']    = 2;

$config->devopsspace->dtable->fieldList['desc']['title'] = $lang->devopsspace->desc;
$config->devopsspace->dtable->fieldList['desc']['name']  = 'desc';
$config->devopsspace->dtable->fieldList['desc']['type']  = 'desc';
$config->devopsspace->dtable->fieldList['desc']['width'] = '200';
$config->devopsspace->dtable->fieldList['desc']['group'] = 3;

$config->devopsspace->dtable->fieldList['createdDate']['title']      = $lang->devopsspace->createdDate;
$config->devopsspace->dtable->fieldList['createdDate']['name']       = 'createdDate';
$config->devopsspace->dtable->fieldList['createdDate']['type']       = 'datetime';
$config->devopsspace->dtable->fieldList['createdDate']['formatDate'] = 'YYYY-MM-dd hh:mm';
$config->devopsspace->dtable->fieldList['createdDate']['sortType']   = false;
$config->devopsspace->dtable->fieldList['createdDate']['group']      = 4;

$config->devopsspace->dtable->fieldList['actions']['name']     = 'actions';
$config->devopsspace->dtable->fieldList['actions']['title']    = $lang->actions;
$config->devopsspace->dtable->fieldList['actions']['type']     = 'actions';
$config->devopsspace->dtable->fieldList['actions']['sortType'] = false;
$config->devopsspace->dtable->fieldList['actions']['fixed']    = 'right';
$config->devopsspace->dtable->fieldList['actions']['menu']     = array('repo', 'artifactrepo', 'edit');
$config->devopsspace->dtable->fieldList['actions']['list']     = $config->devopsspace->actionList;
$config->devopsspace->dtable->fieldList['actions']['width']    = 100;
