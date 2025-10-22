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
