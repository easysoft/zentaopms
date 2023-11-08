<?php
global $lang, $app;
$app->loadLang('sonarqube');

$config->sonarqube->dtable = new stdclass();

$config->sonarqube->dtable->browse = new stdclass();
$config->sonarqube->dtable->browse->fieldList['id']['title']    = 'ID';
$config->sonarqube->dtable->browse->fieldList['id']['name']     = 'id';
$config->sonarqube->dtable->browse->fieldList['id']['type']     = 'number';
$config->sonarqube->dtable->browse->fieldList['id']['sortType'] = 'desc';
$config->sonarqube->dtable->browse->fieldList['id']['checkbox'] = false;
$config->sonarqube->dtable->browse->fieldList['id']['width']    = '80';

$config->sonarqube->dtable->browse->fieldList['name']['title']    = $lang->sonarqube->name;
$config->sonarqube->dtable->browse->fieldList['name']['name']     = 'name';
$config->sonarqube->dtable->browse->fieldList['name']['type']     = 'desc';
$config->sonarqube->dtable->browse->fieldList['name']['sortType'] = true;
$config->sonarqube->dtable->browse->fieldList['name']['hint']     = true;
$config->sonarqube->dtable->browse->fieldList['name']['minWidth'] = '356';

$config->sonarqube->dtable->browse->fieldList['url']['title']    = $lang->sonarqube->url;
$config->sonarqube->dtable->browse->fieldList['url']['name']     = 'url';
$config->sonarqube->dtable->browse->fieldList['url']['type']     = 'desc';
$config->sonarqube->dtable->browse->fieldList['url']['sortType'] = true;
$config->sonarqube->dtable->browse->fieldList['url']['hint']     = true;
$config->sonarqube->dtable->browse->fieldList['url']['minWidth'] = '356';

$config->sonarqube->dtable->browse->fieldList['actions']['name']     = 'actions';
$config->sonarqube->dtable->browse->fieldList['actions']['title']    = $lang->actions;
$config->sonarqube->dtable->browse->fieldList['actions']['type']     = 'actions';
$config->sonarqube->dtable->browse->fieldList['actions']['width']    = '160';
$config->sonarqube->dtable->browse->fieldList['actions']['sortType'] = false;
$config->sonarqube->dtable->browse->fieldList['actions']['fixed']    = 'right';
$config->sonarqube->dtable->browse->fieldList['actions']['menu']     = array('list', 'edit', 'delete');
$config->sonarqube->dtable->browse->fieldList['actions']['list']     = $config->sonarqube->actionList;

$config->sonarqube->dtable->project = new stdclass();
$config->sonarqube->dtable->project->fieldList['key']['title']    = $lang->sonarqube->projectKey;
$config->sonarqube->dtable->project->fieldList['key']['type']     = 'text';
$config->sonarqube->dtable->project->fieldList['key']['sortType'] = true;
$config->sonarqube->dtable->project->fieldList['key']['width']    = '150';

$config->sonarqube->dtable->project->fieldList['name']['title']    = $lang->sonarqube->projectName;
$config->sonarqube->dtable->project->fieldList['name']['type']     = 'title';
$config->sonarqube->dtable->project->fieldList['name']['fixed']    = false;
$config->sonarqube->dtable->project->fieldList['name']['sortType'] = 'desc';

$config->sonarqube->dtable->project->fieldList['time']['title']    = $lang->sonarqube->projectlastAnalysis;
$config->sonarqube->dtable->project->fieldList['time']['name']     = 'lastAnalysisDate';
$config->sonarqube->dtable->project->fieldList['time']['type']     = 'datetime';
$config->sonarqube->dtable->project->fieldList['time']['sortType'] = true;
$config->sonarqube->dtable->project->fieldList['time']['width']    = '150';

$config->sonarqube->dtable->project->fieldList['actions']['name']  = 'actions';
$config->sonarqube->dtable->project->fieldList['actions']['title'] = $lang->actions;
$config->sonarqube->dtable->project->fieldList['actions']['type']  = 'actions';
$config->sonarqube->dtable->project->fieldList['actions']['menu']  = array('deleteProject', 'execJob', 'reportView');
$config->sonarqube->dtable->project->fieldList['actions']['list']  = $config->sonarqube->actionList;
