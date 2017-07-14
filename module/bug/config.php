<?php
$config->bug = new stdClass();
$config->bug->batchCreate = 10;
$config->bug->longlife    = 7;

$config->bug->create  = new stdclass();
$config->bug->edit    = new stdclass();
$config->bug->resolve = new stdclass();
$config->bug->create->requiredFields  = 'title,openedBuild';
$config->bug->edit->requiredFields    = $config->bug->create->requiredFields;
$config->bug->resolve->requiredFields = 'resolution';

$config->bug->list = new stdclass();
$config->bug->list->allFields = 'id, module, project, story, task, 
    title, keywords, severity, pri, type, os, browser, hardware,
    found, steps, status, activatedCount, confirmed, mailto,
    openedBy, openedDate, openedBuild, 
    assignedTo, assignedDate,
    resolvedBy, resolution, resolvedBuild, resolvedDate,
    closedBy, closedDate, 
    duplicateBug, linkBug, 
    case,
    lastEditedBy,
    lastEditedDate';

$config->bug->list->defaultFields = 'id,severity,pri,title,openedBy,assignedTo,resolvedBy,resolution';

$config->bug->list->exportFields = 'id, product, branch, module, project, story, task, 
    title, keywords, severity, pri, type, os, browser,
    steps, status, deadline, activatedCount, confirmed, mailto,
    openedBy, openedDate, openedBuild, 
    assignedTo, assignedDate,
    resolvedBy, resolution, resolvedBuild, resolvedDate,
    closedBy, closedDate, 
    duplicateBug, linkBug, 
    case,
    lastEditedBy,
    lastEditedDate, files';

$config->bug->list->customCreateFields      = 'project,story,task,pri,severity,os,browser,deadline,mailto,keywords';
$config->bug->list->customBatchCreateFields = 'module,project,steps,type,pri,severity,os,browser,keywords';
$config->bug->list->customBatchEditFields   = 'type,severity,pri,productplan,assignedTo,deadline,status,resolvedBy,resolution,os,browser,keywords';

$config->bug->custom = new stdclass();
$config->bug->custom->createFields      = $config->bug->list->customCreateFields;
$config->bug->custom->batchCreateFields = 'module,project,steps,type,severity,os,browser';
$config->bug->custom->batchEditFields   = 'type,severity,pri,branch,assignedTo,deadline,status,resolvedBy,resolution';

if($config->global->flow == 'onlyTest')
{
    $config->bug->list->allFields    = str_replace(array('project, ', 'story, ', 'task,'), '', $config->bug->list->allFields);
    $config->bug->list->exportFields = str_replace(array('project, ', 'story, ', 'task,'), '', $config->bug->list->exportFields);

    $config->bug->list->customCreateFields      = str_replace(array('project,', 'story,', 'task,'), '', $config->bug->list->customCreateFields);
    $config->bug->list->customBatchCreateFields = str_replace('project,', '', $config->bug->list->customBatchCreateFields);

    $config->bug->custom->batchCreateFields = str_replace('project,', '', $config->bug->custom->batchCreateFields);
}

$config->bug->editor = new stdclass();
$config->bug->editor->create     = array('id' => 'steps', 'tools' => 'bugTools');
$config->bug->editor->edit       = array('id' => 'steps,comment', 'tools' => 'bugTools');
$config->bug->editor->view       = array('id' => 'comment,lastComment', 'tools' => 'bugTools');
$config->bug->editor->confirmbug = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->assignto   = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->resolve    = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->close      = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->activate   = array('id' => 'comment', 'tools' => 'bugTools');

global $lang;
$config->bug->search['module']                   = 'bug';
$config->bug->search['fields']['title']          = $lang->bug->title;
$config->bug->search['fields']['id']             = $lang->bug->id;
$config->bug->search['fields']['keywords']       = $lang->bug->keywords;
$config->bug->search['fields']['steps']          = $lang->bug->steps;
$config->bug->search['fields']['assignedTo']     = $lang->bug->assignedTo;
$config->bug->search['fields']['resolvedBy']     = $lang->bug->resolvedBy;

$config->bug->search['fields']['status']         = $lang->bug->status;
$config->bug->search['fields']['confirmed']      = $lang->bug->confirmed;

$config->bug->search['fields']['product']        = $lang->bug->product;
$config->bug->search['fields']['branch']         = '';
$config->bug->search['fields']['plan']          = $lang->bug->productplan;
$config->bug->search['fields']['module']         = $lang->bug->module;
$config->bug->search['fields']['project']        = $lang->bug->project;

$config->bug->search['fields']['severity']       = $lang->bug->severity;
$config->bug->search['fields']['pri']            = $lang->bug->pri;
$config->bug->search['fields']['type']           = $lang->bug->type;
$config->bug->search['fields']['os']             = $lang->bug->os;
$config->bug->search['fields']['browser']        = $lang->bug->browser;
$config->bug->search['fields']['resolution']     = $lang->bug->resolution;

$config->bug->search['fields']['activatedCount'] = $lang->bug->activatedCount;

$config->bug->search['fields']['toTask']         = $lang->bug->toTask;
$config->bug->search['fields']['toStory']        = $lang->bug->toStory;

$config->bug->search['fields']['openedBy']       = $lang->bug->openedBy;
$config->bug->search['fields']['closedBy']       = $lang->bug->closedBy;
$config->bug->search['fields']['lastEditedBy']   = $lang->bug->lastEditedByAB;

$config->bug->search['fields']['mailto']         = $lang->bug->mailto;

$config->bug->search['fields']['openedBuild']    = $lang->bug->openedBuild;
$config->bug->search['fields']['resolvedBuild']  = $lang->bug->resolvedBuild;

$config->bug->search['fields']['openedDate']     = $lang->bug->openedDate;
$config->bug->search['fields']['assignedDate']   = $lang->bug->assignedDate;
$config->bug->search['fields']['resolvedDate']   = $lang->bug->resolvedDate;
$config->bug->search['fields']['closedDate']     = $lang->bug->closedDate;
$config->bug->search['fields']['lastEditedDate'] = $lang->bug->lastEditedDateAB;
$config->bug->search['fields']['deadline']       = $lang->bug->deadline;

if($config->global->flow == 'onlyTest')
{
    unset($config->bug->search['fields']['project']);
    unset($config->bug->search['fields']['plan']);
    unset($config->bug->search['fields']['toTask']);
    unset($config->bug->search['fields']['toStory']);
}

$config->bug->search['params']['title']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['keywords']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['steps']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['assignedTo']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['resolvedBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->bug->search['params']['status']        = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->statusList);
$config->bug->search['params']['confirmed']     = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->confirmedList);

$config->bug->search['params']['product']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->bug->search['params']['branch']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->bug->search['params']['plan']          = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->bug->search['params']['module']        = array('operator' => 'belong',  'control' => 'select', 'values' => 'modules');
$config->bug->search['params']['project']       = array('operator' => '=',       'control' => 'select', 'values' => 'projects');

$config->bug->search['params']['severity']      = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->severityList);
$config->bug->search['params']['pri']           = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->priList);
$config->bug->search['params']['type']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->typeList);
$config->bug->search['params']['os']            = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->osList);
$config->bug->search['params']['browser']       = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->browserList);
$config->bug->search['params']['resolution']    = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->resolutionList);

$config->bug->search['params']['activatedCount']= array('operator' => '>=',      'control' => 'input',  'values' => '');

$config->bug->search['params']['toTask']        = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->bug->search['params']['toStory']       = array('operator' => '=',       'control' => 'input',  'values' => '');

$config->bug->search['params']['openedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['closedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['lastEditedBy']  = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->bug->search['params']['mailto']        = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->bug->search['params']['openedBuild']   = array('operator' => 'include', 'control' => 'select', 'values' => 'builds');
$config->bug->search['params']['resolvedBuild'] = array('operator' => '=',       'control' => 'select', 'values' => 'builds');

$config->bug->search['params']['openedDate']    = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['assignedDate']  = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['resolvedDate']  = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['closedDate']    = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['lastEditedDate']= array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['deadline']      = array('operator' => '=',      'control' => 'input',  'values' => '', 'class' => 'date');

$config->bug->datatable = new stdclass();
$config->bug->datatable->defaultField = array('id', 'severity', 'pri', 'title', 'status', 'openedBy', 'openedDate', 'assignedTo', 'resolvedBy', 'resolution', 'resolvedDate', 'actions');

$config->bug->datatable->fieldList['id']['title']    = 'idAB';
$config->bug->datatable->fieldList['id']['fixed']    = 'left';
$config->bug->datatable->fieldList['id']['width']    = '70';
$config->bug->datatable->fieldList['id']['required'] = 'yes';

$config->bug->datatable->fieldList['severity']['title']    = 'severityAB';
$config->bug->datatable->fieldList['severity']['fixed']    = 'left';
$config->bug->datatable->fieldList['severity']['width']    = '50';
$config->bug->datatable->fieldList['severity']['required'] = 'no';

$config->bug->datatable->fieldList['pri']['title']    = 'P';
$config->bug->datatable->fieldList['pri']['fixed']    = 'left';
$config->bug->datatable->fieldList['pri']['width']    = '40';
$config->bug->datatable->fieldList['pri']['required'] = 'no';

$config->bug->datatable->fieldList['title']['title']    = 'title';
$config->bug->datatable->fieldList['title']['fixed']    = 'left';
$config->bug->datatable->fieldList['title']['width']    = 'auto';
$config->bug->datatable->fieldList['title']['required'] = 'yes';

$config->bug->datatable->fieldList['type']['title']    = 'type';
$config->bug->datatable->fieldList['type']['fixed']    = 'no';
$config->bug->datatable->fieldList['type']['width']    = '90';
$config->bug->datatable->fieldList['type']['required'] = 'no';

$config->bug->datatable->fieldList['status']['title']    = 'statusAB';
$config->bug->datatable->fieldList['status']['fixed']    = 'no';
$config->bug->datatable->fieldList['status']['width']    = '80';
$config->bug->datatable->fieldList['status']['required'] = 'no';

$config->bug->datatable->fieldList['activatedCount']['title']    = 'activatedCountAB';
$config->bug->datatable->fieldList['activatedCount']['fixed']    = 'no';
$config->bug->datatable->fieldList['activatedCount']['width']    = '80';
$config->bug->datatable->fieldList['activatedCount']['required'] = 'no';

$config->bug->datatable->fieldList['openedBy']['title']    = 'openedByAB';
$config->bug->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['openedBy']['width']    = '80';
$config->bug->datatable->fieldList['openedBy']['required'] = 'no';

$config->bug->datatable->fieldList['openedDate']['title']    = 'openedDateAB';
$config->bug->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['openedDate']['width']    = '90';
$config->bug->datatable->fieldList['openedDate']['required'] = 'no';

$config->bug->datatable->fieldList['openedBuild']['title']    = 'openedBuild';
$config->bug->datatable->fieldList['openedBuild']['fixed']    = 'no';
$config->bug->datatable->fieldList['openedBuild']['width']    = '120';
$config->bug->datatable->fieldList['openedBuild']['required'] = 'no';

$config->bug->datatable->fieldList['assignedTo']['title']    = 'assignedTo';
$config->bug->datatable->fieldList['assignedTo']['fixed']    = 'no';
$config->bug->datatable->fieldList['assignedTo']['width']    = '80';
$config->bug->datatable->fieldList['assignedTo']['required'] = 'no';

$config->bug->datatable->fieldList['assignedDate']['title']    = 'assignedDate';
$config->bug->datatable->fieldList['assignedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['assignedDate']['width']    = '90';
$config->bug->datatable->fieldList['assignedDate']['required'] = 'no';

$config->bug->datatable->fieldList['deadline']['title']    = 'deadline';
$config->bug->datatable->fieldList['deadline']['fixed']    = 'no';
$config->bug->datatable->fieldList['deadline']['width']    = '90';
$config->bug->datatable->fieldList['deadline']['required'] = 'no';

$config->bug->datatable->fieldList['resolvedBy']['title']    = 'resolvedByAB';
$config->bug->datatable->fieldList['resolvedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolvedBy']['width']    = '80';
$config->bug->datatable->fieldList['resolvedBy']['required'] = 'no';

$config->bug->datatable->fieldList['resolution']['title']    = 'resolutionAB';
$config->bug->datatable->fieldList['resolution']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolution']['width']    = '80';
$config->bug->datatable->fieldList['resolution']['required'] = 'no';

$config->bug->datatable->fieldList['resolvedDate']['title']    = 'resolvedDateAB';
$config->bug->datatable->fieldList['resolvedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolvedDate']['width']    = '90';
$config->bug->datatable->fieldList['resolvedDate']['required'] = 'no';

$config->bug->datatable->fieldList['resolvedBuild']['title']    = 'resolvedBuild';
$config->bug->datatable->fieldList['resolvedBuild']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolvedBuild']['width']    = '120';
$config->bug->datatable->fieldList['resolvedBuild']['required'] = 'no';

$config->bug->datatable->fieldList['closedBy']['title']    = 'closedBy';
$config->bug->datatable->fieldList['closedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['closedBy']['width']    = '80';
$config->bug->datatable->fieldList['closedBy']['required'] = 'no';

$config->bug->datatable->fieldList['closedDate']['title']    = 'closedDate';
$config->bug->datatable->fieldList['closedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['closedDate']['width']    = '90';
$config->bug->datatable->fieldList['closedDate']['required'] = 'no';

$config->bug->datatable->fieldList['lastEditedDate']['title']    = 'lastEditedDateAB';
$config->bug->datatable->fieldList['lastEditedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['lastEditedDate']['width']    = '90';
$config->bug->datatable->fieldList['lastEditedDate']['required'] = 'no';

$config->bug->datatable->fieldList['actions']['title']    = 'actions';
$config->bug->datatable->fieldList['actions']['fixed']    = 'right';
$config->bug->datatable->fieldList['actions']['width']    = '140';
$config->bug->datatable->fieldList['actions']['required'] = 'yes';

$config->bug->datatable->fieldList['branch']['title']    = 'branch';
$config->bug->datatable->fieldList['branch']['fixed']    = 'left';
$config->bug->datatable->fieldList['branch']['width']    = '100';
$config->bug->datatable->fieldList['branch']['required'] = 'no';
