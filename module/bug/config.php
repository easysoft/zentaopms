<?php
$config->bug = new stdClass();
$config->bug->batchCreate  = 10;
$config->bug->longlife     = 7;
$config->bug->removeFields = 'objectTypeList,productList,executionList,gitlabID,gitlabProjectID';

$config->bug->create  = new stdclass();
$config->bug->edit    = new stdclass();
$config->bug->resolve = new stdclass();
$config->bug->create->requiredFields  = 'title,openedBuild';
$config->bug->edit->requiredFields    = $config->bug->create->requiredFields;
$config->bug->resolve->requiredFields = 'resolution';

$config->bug->list = new stdclass();
$config->bug->list->allFields = 'id, module, execution, story, task,
    title, keywords, severity, pri, type, os, browser, hardware,
    found, steps, status, deadline, activatedCount, confirmed, mailto,
    openedBy, openedDate, openedBuild,
    assignedTo, assignedDate,
    resolvedBy, resolution, resolvedBuild, resolvedDate,
    closedBy, closedDate,
    duplicateBug, linkBug,
    case,
    lastEditedBy,
    lastEditedDate';

$config->bug->list->defaultFields = 'id,title,severity,pri,openedBy,assignedTo,resolvedBy,resolution';

$config->bug->exportFields = 'id, product, branch, module, project, execution, story, task,
    title, keywords, severity, pri, type, os, browser,
    steps, status, deadline, activatedCount, confirmed, mailto,
    openedBy, openedDate, openedBuild,
    assignedTo, assignedDate,
    resolvedBy, resolution, resolvedBuild, resolvedDate,
    closedBy, closedDate,
    duplicateBug, linkBug,
    case,
    lastEditedBy,
    lastEditedDate, files ,feedbackBy, notifyEmail';


$config->bug->list->customCreateFields      = 'execution,noticefeedbackBy,story,task,pri,severity,os,browser,deadline,mailto,keywords';
$config->bug->list->customBatchEditFields   = 'type,severity,pri,productplan,assignedTo,deadline,resolvedBy,resolution,os,browser,keywords';
$config->bug->list->customBatchCreateFields = 'project,execution,steps,type,pri,deadline,severity,os,browser,keywords';

$config->bug->custom = new stdclass();
$config->bug->custom->createFields      = $config->bug->list->customCreateFields;
$config->bug->custom->batchCreateFields = 'project,execution,deadline,steps,type,severity,os,browser,%s';
$config->bug->custom->batchEditFields   = 'type,severity,pri,assignedTo,deadline,status,resolvedBy,resolution';

$config->bug->excludeCheckFileds = ',severities,oses,browsers,lanes,regions,executions,projects,branches,';

$config->bug->editor = new stdclass();
$config->bug->editor->create     = array('id' => 'steps', 'tools' => 'bugTools');
$config->bug->editor->edit       = array('id' => 'steps,comment', 'tools' => 'bugTools');
$config->bug->editor->view       = array('id' => 'comment,lastComment', 'tools' => 'bugTools');
$config->bug->editor->confirmbug = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->assignto   = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->resolve    = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->close      = array('id' => 'comment', 'tools' => 'bugTools');
$config->bug->editor->activate   = array('id' => 'comment', 'tools' => 'bugTools');

$config->bug->discardedTypes = array('interface', 'designchange', 'newfeature', 'trackthings');

global $lang;
$config->bug->search['module']                   = 'bug';
$config->bug->search['fields']['title']          = $lang->bug->title;
$config->bug->search['fields']['module']         = $lang->bug->module;
$config->bug->search['fields']['keywords']       = $lang->bug->keywords;
$config->bug->search['fields']['steps']          = $lang->bug->steps;
$config->bug->search['fields']['assignedTo']     = $lang->bug->assignedTo;
$config->bug->search['fields']['resolvedBy']     = $lang->bug->resolvedBy;

$config->bug->search['fields']['status']         = $lang->bug->status;
$config->bug->search['fields']['confirmed']      = $lang->bug->confirmed;
$config->bug->search['fields']['story']          = $lang->bug->story;

$config->bug->search['fields']['project']        = $lang->bug->project;
$config->bug->search['fields']['product']        = $lang->bug->product;
$config->bug->search['fields']['branch']         = '';
$config->bug->search['fields']['plan']           = $lang->bug->productplan;
$config->bug->search['fields']['id']             = $lang->bug->id;
$config->bug->search['fields']['execution']      = $lang->bug->execution;

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
$config->bug->search['fields']['activatedDate']  = $lang->bug->activatedDate;

$config->bug->search['params']['title']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['keywords']      = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['steps']         = array('operator' => 'include', 'control' => 'input',  'values' => '');
$config->bug->search['params']['assignedTo']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['resolvedBy']    = array('operator' => '=',       'control' => 'select', 'values' => 'users');

$config->bug->search['params']['status']        = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->statusList);
$config->bug->search['params']['confirmed']     = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->confirmedList);
$config->bug->search['params']['story']         = array('operator' => 'include', 'control' => 'input',  'values' => '');

$config->bug->search['params']['project']       = array('operator' => '=', 'control' => 'select', 'values' => '');
$config->bug->search['params']['product']       = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->bug->search['params']['branch']        = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->bug->search['params']['plan']          = array('operator' => '=',       'control' => 'select', 'values' => '');
$config->bug->search['params']['module']        = array('operator' => 'belong',  'control' => 'select', 'values' => 'modules');
$config->bug->search['params']['execution']     = array('operator' => '=',       'control' => 'select', 'values' => 'executions');
$config->bug->search['params']['severity']      = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->severityList);
$config->bug->search['params']['pri']           = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->priList);
$config->bug->search['params']['type']          = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->typeList);
$config->bug->search['params']['os']            = array('operator' => 'include', 'control' => 'select', 'values' => $lang->bug->osList);
$config->bug->search['params']['browser']       = array('operator' => 'include', 'control' => 'select', 'values' => $lang->bug->browserList);
$config->bug->search['params']['resolution']    = array('operator' => '=',       'control' => 'select', 'values' => $lang->bug->resolutionList);
$config->bug->search['params']['activatedCount']= array('operator' => '>=',      'control' => 'input',  'values' => '');
$config->bug->search['params']['toTask']        = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->bug->search['params']['toStory']       = array('operator' => '=',       'control' => 'input',  'values' => '');
$config->bug->search['params']['openedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['closedBy']      = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['lastEditedBy']  = array('operator' => '=',       'control' => 'select', 'values' => 'users');
$config->bug->search['params']['mailto']        = array('operator' => 'include', 'control' => 'select', 'values' => 'users');
$config->bug->search['params']['openedBuild']   = array('operator' => 'include', 'control' => 'select', 'values' => 'builds');
$config->bug->search['params']['resolvedBuild'] = array('operator' => '=',       'control' => 'select', 'values' => 'builds');
$config->bug->search['params']['openedDate']    = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['assignedDate']  = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['resolvedDate']  = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['closedDate']    = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['lastEditedDate']= array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['deadline']      = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');
$config->bug->search['params']['activatedDate'] = array('operator' => '=',       'control' => 'input',  'values' => '', 'class' => 'date');

$config->bug->datatable = new stdclass();
$config->bug->datatable->defaultField = array('id', 'title', 'severity', 'pri', 'status', 'openedBy', 'openedDate', 'confirmed', 'assignedTo', 'resolution', 'actions');

global $lang;
$config->bug->datatable->fieldList['id']['title']    = 'idAB';
$config->bug->datatable->fieldList['id']['type']     = 'checkID';
$config->bug->datatable->fieldList['id']['fixed']    = 'left';
$config->bug->datatable->fieldList['id']['width']    = '70';
$config->bug->datatable->fieldList['id']['checkbox'] = true;
$config->bug->datatable->fieldList['id']['sortType'] = true;
$config->bug->datatable->fieldList['id']['required'] = 'yes';

$config->bug->datatable->fieldList['product']['title']      = 'product';
$config->bug->datatable->fieldList['product']['control']    = 'hidden';
$config->bug->datatable->fieldList['product']['dataSource'] = array('module' => 'product', 'method' => 'getPairs', 'params' => '&0&&all');
$config->bug->datatable->fieldList['product']['display']    = false; // The configuration of datatable also used to export data, the false value means the field won't display in datatable.

$config->bug->datatable->fieldList['module']['control']    = 'select';
$config->bug->datatable->fieldList['module']['title']      = 'module';
$config->bug->datatable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => '$productID&bug&0&all');
$config->bug->datatable->fieldList['module']['display']    = false; // The configuration of datatable also used to export data, the false value means the field won't display in datatable.

$config->bug->datatable->fieldList['title']['title']    = 'title';
$config->bug->datatable->fieldList['title']['type']     = 'html';
$config->bug->datatable->fieldList['title']['fixed']    = 'left';
$config->bug->datatable->fieldList['title']['width']    = 'auto';
$config->bug->datatable->fieldList['title']['minWidth'] = '200';
$config->bug->datatable->fieldList['title']['sortType'] = true;
$config->bug->datatable->fieldList['title']['required'] = 'yes';

$config->bug->datatable->fieldList['severity']['title']    = 'severityAB';
$config->bug->datatable->fieldList['severity']['type']     = 'html';
$config->bug->datatable->fieldList['severity']['fixed']    = 'left';
$config->bug->datatable->fieldList['severity']['width']    = '60';
$config->bug->datatable->fieldList['severity']['sortType'] = true;
$config->bug->datatable->fieldList['severity']['show']     = true;

$config->bug->datatable->fieldList['pri']['title']    = 'P';
$config->bug->datatable->fieldList['pri']['type']     = 'html';
$config->bug->datatable->fieldList['pri']['fixed']    = 'left';
$config->bug->datatable->fieldList['pri']['width']    = '50';
$config->bug->datatable->fieldList['pri']['sortType'] = true;
$config->bug->datatable->fieldList['pri']['show']     = true;

$config->bug->datatable->fieldList['status']['title']    = 'statusAB';
$config->bug->datatable->fieldList['status']['type']     = 'html';
$config->bug->datatable->fieldList['status']['fixed']    = 'left';
$config->bug->datatable->fieldList['status']['width']    = '80';
$config->bug->datatable->fieldList['status']['sortType'] = true;
$config->bug->datatable->fieldList['status']['show']     = true;

$config->bug->datatable->fieldList['branch']['title']      = 'branch';
$config->bug->datatable->fieldList['branch']['type']       = 'html';
$config->bug->datatable->fieldList['branch']['fixed']      = 'left';
$config->bug->datatable->fieldList['branch']['width']      = '100';
$config->bug->datatable->fieldList['branch']['sortType']   = true;
$config->bug->datatable->fieldList['branch']['control']    = 'select';
$config->bug->datatable->fieldList['branch']['dataSource'] = array('module' => 'branch', 'method' => 'getPairs', 'params' => '$productID');

$config->bug->datatable->fieldList['confirmed']['title']    = 'confirmedAB';
$config->bug->datatable->fieldList['confirmed']['type']     = 'html';
$config->bug->datatable->fieldList['confirmed']['fixed']    = 'no';
$config->bug->datatable->fieldList['confirmed']['width']    = '70';
$config->bug->datatable->fieldList['confirmed']['sortType'] = true;
$config->bug->datatable->fieldList['confirmed']['show']     = true;

$config->bug->datatable->fieldList['type']['title']    = 'type';
$config->bug->datatable->fieldList['type']['type']     = 'html';
$config->bug->datatable->fieldList['type']['fixed']    = 'no';
$config->bug->datatable->fieldList['type']['width']    = '90';
$config->bug->datatable->fieldList['type']['sortType'] = true;

$config->bug->datatable->fieldList['project']['title']      = 'project';
$config->bug->datatable->fieldList['project']['type']       = 'html';
$config->bug->datatable->fieldList['project']['fixed']      = 'no';
$config->bug->datatable->fieldList['project']['width']      = '120';
$config->bug->datatable->fieldList['project']['sortType']   = true;
$config->bug->datatable->fieldList['project']['control']    = 'hidden';
$config->bug->datatable->fieldList['project']['dataSource'] = array('module' => 'project', 'method' => 'getPairs');

$config->bug->datatable->fieldList['execution']['title']      = 'execution';
$config->bug->datatable->fieldList['execution']['type']       = 'html';
$config->bug->datatable->fieldList['execution']['fixed']      = 'no';
$config->bug->datatable->fieldList['execution']['width']      = '120';
$config->bug->datatable->fieldList['execution']['sortType']   = true;
$config->bug->datatable->fieldList['execution']['dataSource'] = array('module' => 'execution', 'method' =>'getPairs');

$config->bug->datatable->fieldList['plan']['title']    = 'plan';
$config->bug->datatable->fieldList['plan']['type']     = 'html';
$config->bug->datatable->fieldList['plan']['fixed']    = 'no';
$config->bug->datatable->fieldList['plan']['width']    = '120';
$config->bug->datatable->fieldList['plan']['sortType'] = true;

$config->bug->datatable->fieldList['openedBy']['title']    = 'openedByAB';
$config->bug->datatable->fieldList['openedBy']['type']     = 'html';
$config->bug->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['openedBy']['width']    = '80';
$config->bug->datatable->fieldList['openedBy']['sortType'] = true;
$config->bug->datatable->fieldList['openedBy']['show']     = true;

$config->bug->datatable->fieldList['openedDate']['title']    = 'openedDateAB';
$config->bug->datatable->fieldList['openedDate']['type']     = 'html';
$config->bug->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['openedDate']['width']    = '90';
$config->bug->datatable->fieldList['openedDate']['sortType'] = true;
$config->bug->datatable->fieldList['openedDate']['show']     = true;

$config->bug->datatable->fieldList['openedBuild']['title']      = 'openedBuild';
$config->bug->datatable->fieldList['openedBuild']['type']       = 'html';
$config->bug->datatable->fieldList['openedBuild']['fixed']      = 'no';
$config->bug->datatable->fieldList['openedBuild']['width']      = '120';
$config->bug->datatable->fieldList['openedBuild']['sortType']   = true;
$config->bug->datatable->fieldList['openedBuild']['control']    = 'multiple';
$config->bug->datatable->fieldList['openedBuild']['dataSource'] = array('module' => 'build', 'method' =>'getBuildPairs', 'params' => '$productID&$branch&noempty,noterminate,nodone,withbranch');

$config->bug->datatable->fieldList['assignedTo']['title']      = 'assignedTo';
$config->bug->datatable->fieldList['assignedTo']['type']       = 'html';
$config->bug->datatable->fieldList['assignedTo']['fixed']      = 'no';
$config->bug->datatable->fieldList['assignedTo']['width']      = '90';
$config->bug->datatable->fieldList['assignedTo']['sortType']   = true;
$config->bug->datatable->fieldList['assignedTo']['show']       = true;
$config->bug->datatable->fieldList['assignedTo']['dataSource'] = array('module' => 'user', 'method' =>'getPairs', 'params' => 'noclosed|noletter');

$config->bug->datatable->fieldList['assignedDate']['title']    = 'assignedDate';
$config->bug->datatable->fieldList['assignedDate']['type']     = 'html';
$config->bug->datatable->fieldList['assignedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['assignedDate']['width']    = '90';
$config->bug->datatable->fieldList['assignedDate']['sortType'] = true;

$config->bug->datatable->fieldList['deadline']['title']    = 'deadline';
$config->bug->datatable->fieldList['deadline']['type']     = 'html';
$config->bug->datatable->fieldList['deadline']['fixed']    = 'no';
$config->bug->datatable->fieldList['deadline']['width']    = '90';
$config->bug->datatable->fieldList['deadline']['sortType'] = true;

$config->bug->datatable->fieldList['resolvedBy']['title']    = 'resolvedBy';
$config->bug->datatable->fieldList['resolvedBy']['type']     = 'html';
$config->bug->datatable->fieldList['resolvedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolvedBy']['width']    = '100';
$config->bug->datatable->fieldList['resolvedBy']['sortType'] = true;

$config->bug->datatable->fieldList['resolution']['title']    = 'resolutionAB';
$config->bug->datatable->fieldList['resolution']['type']     = 'html';
$config->bug->datatable->fieldList['resolution']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolution']['width']    = '110';
$config->bug->datatable->fieldList['resolution']['sortType'] = true;
$config->bug->datatable->fieldList['resolution']['show']     = true;

$config->bug->datatable->fieldList['resolvedDate']['title']    = 'resolvedDateAB';
$config->bug->datatable->fieldList['resolvedDate']['type']     = 'html';
$config->bug->datatable->fieldList['resolvedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolvedDate']['width']    = '120';
$config->bug->datatable->fieldList['resolvedDate']['sortType'] = true;

$config->bug->datatable->fieldList['resolvedBuild']['title']      = 'resolvedBuild';
$config->bug->datatable->fieldList['resolvedBuild']['type']       = 'html';
$config->bug->datatable->fieldList['resolvedBuild']['fixed']      = 'no';
$config->bug->datatable->fieldList['resolvedBuild']['width']      = '120';
$config->bug->datatable->fieldList['resolvedBuild']['sortType']   = true;
$config->bug->datatable->fieldList['resolvedBuild']['control']    = 'select';
$config->bug->datatable->fieldList['resolvedBuild']['dataSource'] = array('module' => 'bug', 'method' =>'getRelatedObjects', 'params' => 'resolvedBuild&id,name');

$config->bug->datatable->fieldList['activatedCount']['title']    = 'activatedCountAB';
$config->bug->datatable->fieldList['activatedCount']['type']     = 'html';
$config->bug->datatable->fieldList['activatedCount']['fixed']    = 'no';
$config->bug->datatable->fieldList['activatedCount']['width']    = '80';
$config->bug->datatable->fieldList['activatedCount']['sortType'] = true;

$config->bug->datatable->fieldList['activatedDate']['title']    = 'activatedDate';
$config->bug->datatable->fieldList['activatedDate']['type']     = 'html';
$config->bug->datatable->fieldList['activatedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['activatedDate']['width']    = '90';
$config->bug->datatable->fieldList['activatedDate']['sortType'] = true;

$config->bug->datatable->fieldList['story']['title']      = 'story';
$config->bug->datatable->fieldList['story']['type']       = 'html';
$config->bug->datatable->fieldList['story']['fixed']      = 'no';
$config->bug->datatable->fieldList['story']['width']      = '120';
$config->bug->datatable->fieldList['story']['sortType']   = true;
$config->bug->datatable->fieldList['story']['control']    = 'select';
$config->bug->datatable->fieldList['story']['dataSource'] = array('module' => 'story', 'method' =>'getProductStoryPairs', 'params' => '$productID');

$config->bug->datatable->fieldList['task']['title']      = 'task';
$config->bug->datatable->fieldList['task']['type']       = 'html';
$config->bug->datatable->fieldList['task']['fixed']      = 'no';
$config->bug->datatable->fieldList['task']['width']      = '120';
$config->bug->datatable->fieldList['task']['sortType']   = true;
$config->bug->datatable->fieldList['task']['dataSource'] = array('module' => 'bug', 'method' =>'getRelatedObjects', 'params' => 'task&id,name');

$config->bug->datatable->fieldList['toTask']['title']    = 'toTask';
$config->bug->datatable->fieldList['toTask']['type']     = 'html';
$config->bug->datatable->fieldList['toTask']['fixed']    = 'no';
$config->bug->datatable->fieldList['toTask']['width']    = '120';
$config->bug->datatable->fieldList['toTask']['sortType'] = true;

$config->bug->datatable->fieldList['keywords']['title']    = 'keywords';
$config->bug->datatable->fieldList['keywords']['type']     = 'html';
$config->bug->datatable->fieldList['keywords']['fixed']    = 'no';
$config->bug->datatable->fieldList['keywords']['width']    = '100';
$config->bug->datatable->fieldList['keywords']['sortType'] = true;

$config->bug->datatable->fieldList['os']['title']    = 'os';
$config->bug->datatable->fieldList['os']['type']     = 'html';
$config->bug->datatable->fieldList['os']['fixed']    = 'no';
$config->bug->datatable->fieldList['os']['width']    = '80';
$config->bug->datatable->fieldList['os']['sortType'] = true;
$config->bug->datatable->fieldList['os']['control']  = 'multiple';

$config->bug->datatable->fieldList['browser']['title']    = 'browser';
$config->bug->datatable->fieldList['browser']['type']     = 'html';
$config->bug->datatable->fieldList['browser']['fixed']    = 'no';
$config->bug->datatable->fieldList['browser']['width']    = '80';
$config->bug->datatable->fieldList['browser']['sortType'] = true;
$config->bug->datatable->fieldList['browser']['control']  = 'multiple';

$config->bug->datatable->fieldList['mailto']['title']    = 'mailto';
$config->bug->datatable->fieldList['mailto']['type']     = 'html';
$config->bug->datatable->fieldList['mailto']['fixed']    = 'no';
$config->bug->datatable->fieldList['mailto']['width']    = '100';
$config->bug->datatable->fieldList['mailto']['sortType'] = true;

$config->bug->datatable->fieldList['closedBy']['title']    = 'closedBy';
$config->bug->datatable->fieldList['closedBy']['type']     = 'html';
$config->bug->datatable->fieldList['closedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['closedBy']['width']    = '80';
$config->bug->datatable->fieldList['closedBy']['sortType'] = true;

$config->bug->datatable->fieldList['closedDate']['title']    = 'closedDate';
$config->bug->datatable->fieldList['closedDate']['type']     = 'html';
$config->bug->datatable->fieldList['closedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['closedDate']['width']    = '90';
$config->bug->datatable->fieldList['closedDate']['sortType'] = true;

$config->bug->datatable->fieldList['lastEditedBy']['title']    = 'lastEditedBy';
$config->bug->datatable->fieldList['lastEditedBy']['type']     = 'html';
$config->bug->datatable->fieldList['lastEditedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['lastEditedBy']['width']    = '90';
$config->bug->datatable->fieldList['lastEditedBy']['sortType'] = true;

$config->bug->datatable->fieldList['lastEditedDate']['title']    = 'lastEditedDateAB';
$config->bug->datatable->fieldList['lastEditedDate']['type']     = 'html';
$config->bug->datatable->fieldList['lastEditedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['lastEditedDate']['width']    = '90';
$config->bug->datatable->fieldList['lastEditedDate']['sortType'] = true;

$config->bug->datatable->fieldList['steps']['title']   = 'steps';
$config->bug->datatable->fieldList['steps']['control'] = 'textarea';
$config->bug->datatable->fieldList['steps']['display'] = false; // The configuration of datatable also used to export data, the false value means the field won't display in datatable.

$config->bug->datatable->fieldList['case']['title']      = 'case';
$config->bug->datatable->fieldList['case']['dataSource'] = array('module' => 'bug', 'method' =>'getRelatedObjects', 'params' => 'case&id,title');
$config->bug->datatable->fieldList['case']['display']    = false; // The configuration of datatable also used to export data, the false value means the field won't display in datatable.

$config->bug->datatable->fieldList['actions']['title']    = 'actions';
$config->bug->datatable->fieldList['actions']['type']     = 'html';
$config->bug->datatable->fieldList['actions']['width']    = '150';
$config->bug->datatable->fieldList['actions']['fixed']    = 'right';
$config->bug->datatable->fieldList['actions']['required'] = 'yes';

$config->bug->colorList = new stdclass();
$config->bug->colorList->pri[0]      = '#c0c0c0';
$config->bug->colorList->pri[1]      = '#d50000';
$config->bug->colorList->pri[2]      = '#ff9800';
$config->bug->colorList->pri[3]      = '#2098ee';
$config->bug->colorList->pri[4]      = '#009688';
$config->bug->colorList->pri[5]      = '#919090';
$config->bug->colorList->pri[6]      = '#B6B4B4';
$config->bug->colorList->pri[7]      = '#BDBEBD';
$config->bug->colorList->severity[1] = '#c62828';
$config->bug->colorList->severity[2] = '#ff8f00';
$config->bug->colorList->severity[3] = '#fdd835';
$config->bug->colorList->severity[4] = '#cddc39';
$config->bug->colorList->severity[5] = '#8bc34a';
$config->bug->colorList->severity[6] = '#B6B4B4';
$config->bug->colorList->severity[7] = '#BDBEBD';
