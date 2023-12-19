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

$config->sonarqube->dtable->report = new stdclass();
$config->sonarqube->dtable->report->fieldList['bugs']['title'] = array('html' => '<i class="icon icon-bug"></i>' . $lang->sonarqube->report->bugs);
$config->sonarqube->dtable->report->fieldList['bugs']['type']  = 'text';

$config->sonarqube->dtable->report->fieldList['vulnerabilities']['title'] = array('html' => '<i class="icon icon-unlock"></i>' . $lang->sonarqube->report->vulnerabilities);
$config->sonarqube->dtable->report->fieldList['vulnerabilities']['type']  = 'text';

$config->sonarqube->dtable->report->fieldList['security_hotspots_reviewed']['title'] = array('html' => '<i class="icon icon-shield"></i>' . $lang->sonarqube->report->security_hotspots_reviewed);
$config->sonarqube->dtable->report->fieldList['security_hotspots_reviewed']['type']  = 'text';

$config->sonarqube->dtable->report->fieldList['code_smells']['title'] = array('html' => '<i class="icon icon-frown"></i>' . $lang->sonarqube->report->code_smells);
$config->sonarqube->dtable->report->fieldList['code_smells']['type']  = 'text';

$config->sonarqube->dtable->report->fieldList['coverage']['title'] = $lang->sonarqube->report->coverage;
$config->sonarqube->dtable->report->fieldList['coverage']['type']  = 'text';

$config->sonarqube->dtable->report->fieldList['duplicated_lines_density']['title'] = $lang->sonarqube->report->duplicated_lines_density;
$config->sonarqube->dtable->report->fieldList['duplicated_lines_density']['type']  = 'text';

$config->sonarqube->dtable->report->fieldList['ncloc']['title'] = $lang->sonarqube->report->ncloc;
$config->sonarqube->dtable->report->fieldList['ncloc']['type']  = 'text';

$config->sonarqube->dtable->issue = new stdclass();
$config->sonarqube->dtable->issue->fieldList['message']['title']  = $lang->sonarqube->issue->message;
$config->sonarqube->dtable->issue->fieldList['message']['type']   = 'title';
$config->sonarqube->dtable->issue->fieldList['message']['link']   = array('url' => '%s/project/issues?id={projectKey}&issues={key}&open={key}', 'target' => '_blank');

$config->sonarqube->dtable->issue->fieldList['severity']['title']    = $lang->sonarqube->issue->severity;
$config->sonarqube->dtable->issue->fieldList['severity']['type']     = 'text';
$config->sonarqube->dtable->issue->fieldList['severity']['sortType'] = true;
$config->sonarqube->dtable->issue->fieldList['severity']['width']    = 120;

$config->sonarqube->dtable->issue->fieldList['type']['title']    = $lang->sonarqube->issue->type;
$config->sonarqube->dtable->issue->fieldList['type']['type']     = 'text';
$config->sonarqube->dtable->issue->fieldList['type']['width']    = 100;
$config->sonarqube->dtable->issue->fieldList['type']['sortType'] = true;

$config->sonarqube->dtable->issue->fieldList['status']['title']    = $lang->sonarqube->issue->status;
$config->sonarqube->dtable->issue->fieldList['status']['type']     = 'text';
$config->sonarqube->dtable->issue->fieldList['status']['width']    = 100;
$config->sonarqube->dtable->issue->fieldList['status']['sortType'] = true;

$config->sonarqube->dtable->issue->fieldList['file']['title']    = $lang->sonarqube->issue->file;
$config->sonarqube->dtable->issue->fieldList['file']['type']     = 'text';
$config->sonarqube->dtable->issue->fieldList['file']['sortType'] = true;

$config->sonarqube->dtable->issue->fieldList['line']['title']    = $lang->sonarqube->issue->line;
$config->sonarqube->dtable->issue->fieldList['line']['type']     = 'text';
$config->sonarqube->dtable->issue->fieldList['line']['width']    = 100;
$config->sonarqube->dtable->issue->fieldList['line']['sortType'] = true;

$config->sonarqube->dtable->issue->fieldList['effort']['title']    = $lang->sonarqube->issue->effort;
$config->sonarqube->dtable->issue->fieldList['effort']['type']     = 'text';
$config->sonarqube->dtable->issue->fieldList['effort']['sortType'] = true;

$config->sonarqube->dtable->issue->fieldList['creationDate']['title']    = $lang->sonarqube->issue->creationDate;
$config->sonarqube->dtable->issue->fieldList['creationDate']['type']     = 'datetime';
$config->sonarqube->dtable->issue->fieldList['creationDate']['sortType'] = true;

$config->sonarqube->dtable->issue->fieldList['actions']['name']  = 'actions';
$config->sonarqube->dtable->issue->fieldList['actions']['title'] = $lang->actions;
$config->sonarqube->dtable->issue->fieldList['actions']['type']  = 'actions';
$config->sonarqube->dtable->issue->fieldList['actions']['maxWidth'] = '60';

$config->sonarqube->dtable->issue->fieldList['actions']['actionsMap']['createBug']['icon'] = 'bug';
$config->sonarqube->dtable->issue->fieldList['actions']['actionsMap']['createBug']['hint'] = $lang->sonarqube->createBug;
$config->sonarqube->dtable->issue->fieldList['actions']['actionsMap']['createBug']['url']  = 'javascript: saveIssueTitle("{productID}", "{sonarqubeID}", "{issueKey}", \'{message}\')';
