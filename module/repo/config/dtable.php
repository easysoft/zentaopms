<?php
global $lang;

$config->repo->dtable = new stdclass();

$config->repo->dtable->fieldList['id']['name']  = 'id';
$config->repo->dtable->fieldList['id']['title'] = $lang->idAB;
$config->repo->dtable->fieldList['id']['type']  = 'id';
$config->repo->dtable->fieldList['id']['group'] = 1;

$config->repo->dtable->fieldList['scm']['name']  = 'SCM';
$config->repo->dtable->fieldList['scm']['title'] = $lang->repo->type;
$config->repo->dtable->fieldList['scm']['type']  = 'status';
$config->repo->dtable->fieldList['scm']['map']   = $lang->repo->scmList;
$config->repo->dtable->fieldList['scm']['group'] = 1;

$config->repo->dtable->fieldList['name']['name']  = 'name';
$config->repo->dtable->fieldList['name']['title'] = $lang->repo->name;
$config->repo->dtable->fieldList['name']['type']  = 'text';
$config->repo->dtable->fieldList['name']['group'] = 2;

$config->repo->dtable->fieldList['product']['name']  = 'product';
$config->repo->dtable->fieldList['product']['title'] = $lang->repo->product;
$config->repo->dtable->fieldList['product']['type']  = 'text';

$config->repo->dtable->fieldList['path']['name']  = 'codePath';
$config->repo->dtable->fieldList['path']['title'] = $lang->repo->path;
$config->repo->dtable->fieldList['path']['type']  = 'text';

$config->repo->dtable->fieldList['job']['name']  = 'job';
$config->repo->dtable->fieldList['job']['hidden'] = true;

$config->repo->dtable->fieldList['actions']['name']  = 'actions';
$config->repo->dtable->fieldList['actions']['title'] = $lang->actions;
$config->repo->dtable->fieldList['actions']['type']  = 'actions';
$config->repo->dtable->fieldList['actions']['menu']  = array('edit', 'execJob', 'reportView', 'delete');

$config->repo->dtable->fieldList['actions']['list']['edit']['icon'] = 'edit';
$config->repo->dtable->fieldList['actions']['list']['edit']['hint'] = $lang->repo->edit;

$config->repo->dtable->fieldList['actions']['list']['execJob']['icon']        = 'sonarqube';
$config->repo->dtable->fieldList['actions']['list']['execJob']['hint']        = $lang->repo->execJob;
$config->repo->dtable->fieldList['actions']['list']['execJob']['url']         = helper::createLink('sonarqube', 'execJob', "jobID={jobID}");
$config->repo->dtable->fieldList['actions']['list']['execJob']['data-toggle'] = 'modal';

$config->repo->dtable->fieldList['actions']['list']['reportView']['icon']        = 'audit';
$config->repo->dtable->fieldList['actions']['list']['reportView']['hint']        = $lang->repo->reportView;
$config->repo->dtable->fieldList['actions']['list']['reportView']['url']         = helper::createLink('sonarqube', 'reportView', "jobID={jobID}");
$config->repo->dtable->fieldList['actions']['list']['reportView']['data-toggle'] = 'modal';

$config->repo->dtable->fieldList['actions']['list']['delete']['icon']        = 'trash';
$config->repo->dtable->fieldList['actions']['list']['delete']['hint']        = $lang->repo->delete;
$config->repo->dtable->fieldList['actions']['list']['delete']['data-toggle'] = 'modal';
