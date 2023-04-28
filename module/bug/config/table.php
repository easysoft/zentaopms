<?php
global $lang;
$config->bug->datatable = new stdclass();
$config->bug->datatable->defaultField = array('id', 'title', 'severity', 'pri', 'status', 'openedBy', 'openedDate', 'confirmed', 'assignedTo', 'resolution', 'actions');

$config->bug->datatable->fieldList['id']['title']    = 'idAB';
$config->bug->datatable->fieldList['id']['fixed']    = 'left';
$config->bug->datatable->fieldList['id']['width']    = '70';
$config->bug->datatable->fieldList['id']['required'] = 'yes';

$config->bug->datatable->fieldList['product']['title']      = 'product';
$config->bug->datatable->fieldList['product']['control']    = 'hidden';
$config->bug->datatable->fieldList['product']['dataSource'] = array('module' => 'product', 'method' => 'getPairs', 'params' => '&0&&all');

$config->bug->datatable->fieldList['module']['control']    = 'select';
$config->bug->datatable->fieldList['module']['title']      = 'module';
$config->bug->datatable->fieldList['module']['dataSource'] = array('module' => 'tree', 'method' => 'getOptionMenu', 'params' => '$productID&bug&0&all');

$config->bug->datatable->fieldList['title']['title']    = 'title';
$config->bug->datatable->fieldList['title']['fixed']    = 'left';
$config->bug->datatable->fieldList['title']['width']    = 'auto';
$config->bug->datatable->fieldList['title']['required'] = 'yes';
$config->bug->datatable->fieldList['title']['minWidth'] = '200';

$config->bug->datatable->fieldList['severity']['title']    = 'severityAB';
$config->bug->datatable->fieldList['severity']['fixed']    = 'left';
$config->bug->datatable->fieldList['severity']['width']    = '50';
$config->bug->datatable->fieldList['severity']['required'] = 'no';
$config->bug->datatable->fieldList['severity']['name']     = $lang->bug->severity;

$config->bug->datatable->fieldList['pri']['title']    = 'P';
$config->bug->datatable->fieldList['pri']['fixed']    = 'left';
$config->bug->datatable->fieldList['pri']['width']    = '50';
$config->bug->datatable->fieldList['pri']['required'] = 'no';
$config->bug->datatable->fieldList['pri']['name']     = $lang->bug->pri;

$config->bug->datatable->fieldList['confirmed']['title']    = 'confirmedAB';
$config->bug->datatable->fieldList['confirmed']['fixed']    = 'left';
$config->bug->datatable->fieldList['confirmed']['width']    = '80';
$config->bug->datatable->fieldList['confirmed']['required'] = 'no';

$config->bug->datatable->fieldList['title']['title']    = 'title';
$config->bug->datatable->fieldList['title']['fixed']    = 'left';
$config->bug->datatable->fieldList['title']['width']    = 'auto';
$config->bug->datatable->fieldList['title']['required'] = 'yes';
$config->bug->datatable->fieldList['title']['minWidth'] = '200';

$config->bug->datatable->fieldList['status']['title']    = 'statusAB';
$config->bug->datatable->fieldList['status']['fixed']    = 'left';
$config->bug->datatable->fieldList['status']['width']    = '80';
$config->bug->datatable->fieldList['status']['required'] = 'no';

$config->bug->datatable->fieldList['branch']['title']      = 'branch';
$config->bug->datatable->fieldList['branch']['fixed']      = 'left';
$config->bug->datatable->fieldList['branch']['width']      = '100';
$config->bug->datatable->fieldList['branch']['required']   = 'no';
$config->bug->datatable->fieldList['branch']['control']    = 'select';
$config->bug->datatable->fieldList['branch']['dataSource'] = array('module' => 'branch', 'method' => 'getPairs', 'params' => '$productID');

$config->bug->datatable->fieldList['type']['title']    = 'type';
$config->bug->datatable->fieldList['type']['fixed']    = 'no';
$config->bug->datatable->fieldList['type']['width']    = '90';
$config->bug->datatable->fieldList['type']['required'] = 'no';

$config->bug->datatable->fieldList['project']['title']      = 'project';
$config->bug->datatable->fieldList['project']['fixed']      = 'no';
$config->bug->datatable->fieldList['project']['width']      = '120';
$config->bug->datatable->fieldList['project']['required']   = 'no';
$config->bug->datatable->fieldList['project']['control']    = 'hidden';
$config->bug->datatable->fieldList['project']['dataSource'] = array('module' => 'product', 'method' => 'getProjectPairsByProduct', 'params' => '$productID&');

$config->bug->datatable->fieldList['execution']['title']      = 'execution';
$config->bug->datatable->fieldList['execution']['fixed']      = 'no';
$config->bug->datatable->fieldList['execution']['width']      = '120';
$config->bug->datatable->fieldList['execution']['required']   = 'no';
$config->bug->datatable->fieldList['execution']['dataSource'] = array('module' => 'product', 'method' =>'getAllExecutionPairsByProduct', 'params' => '$productID&$branch&0&stagefilter');

$config->bug->datatable->fieldList['plan']['title']    = 'plan';
$config->bug->datatable->fieldList['plan']['fixed']    = 'no';
$config->bug->datatable->fieldList['plan']['width']    = '120';
$config->bug->datatable->fieldList['plan']['required'] = 'no';

$config->bug->datatable->fieldList['openedBy']['title']    = 'openedByAB';
$config->bug->datatable->fieldList['openedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['openedBy']['width']    = '80';
$config->bug->datatable->fieldList['openedBy']['required'] = 'no';

$config->bug->datatable->fieldList['openedDate']['title']    = 'openedDateAB';
$config->bug->datatable->fieldList['openedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['openedDate']['width']    = '90';
$config->bug->datatable->fieldList['openedDate']['required'] = 'no';

$config->bug->datatable->fieldList['openedBuild']['title']      = 'openedBuild';
$config->bug->datatable->fieldList['openedBuild']['fixed']      = 'no';
$config->bug->datatable->fieldList['openedBuild']['width']      = '120';
$config->bug->datatable->fieldList['openedBuild']['required']   = 'no';
$config->bug->datatable->fieldList['openedBuild']['control']    = 'multiple';
$config->bug->datatable->fieldList['openedBuild']['dataSource'] = array('module' => 'build', 'method' =>'getBuildPairs', 'params' => '$productID&$branch&noempty,noterminate,nodone,withbranch');

$config->bug->datatable->fieldList['confirmed']['title']    = 'confirmedAB';
$config->bug->datatable->fieldList['confirmed']['fixed']    = 'no';
$config->bug->datatable->fieldList['confirmed']['width']    = '100';
$config->bug->datatable->fieldList['confirmed']['required'] = 'no';

$config->bug->datatable->fieldList['assignedTo']['title']      = 'assignedToAB';
$config->bug->datatable->fieldList['assignedTo']['fixed']      = 'no';
$config->bug->datatable->fieldList['assignedTo']['width']      = '120';
$config->bug->datatable->fieldList['assignedTo']['required']   = 'no';
$config->bug->datatable->fieldList['assignedTo']['dataSource'] = array('module' => 'user', 'method' =>'getPairs', 'params' => 'noclosed|noletter');

$config->bug->datatable->fieldList['assignedDate']['title']    = 'assignedDate';
$config->bug->datatable->fieldList['assignedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['assignedDate']['width']    = '90';
$config->bug->datatable->fieldList['assignedDate']['required'] = 'no';

$config->bug->datatable->fieldList['deadline']['title']    = 'deadline';
$config->bug->datatable->fieldList['deadline']['fixed']    = 'no';
$config->bug->datatable->fieldList['deadline']['width']    = '90';
$config->bug->datatable->fieldList['deadline']['required'] = 'no';
$config->bug->datatable->fieldList['deadline']['control']  = 'date';

$config->bug->datatable->fieldList['resolvedBy']['title']    = 'resolvedBy';
$config->bug->datatable->fieldList['resolvedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolvedBy']['width']    = '100';
$config->bug->datatable->fieldList['resolvedBy']['required'] = 'no';

$config->bug->datatable->fieldList['resolution']['title']    = 'resolutionAB';
$config->bug->datatable->fieldList['resolution']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolution']['width']    = '110';
$config->bug->datatable->fieldList['resolution']['required'] = 'no';

$config->bug->datatable->fieldList['resolvedDate']['title']    = 'resolvedDateAB';
$config->bug->datatable->fieldList['resolvedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolvedDate']['width']    = '120';
$config->bug->datatable->fieldList['resolvedDate']['required'] = 'no';

$config->bug->datatable->fieldList['resolvedBuild']['title']    = 'resolvedBuild';
$config->bug->datatable->fieldList['resolvedBuild']['fixed']    = 'no';
$config->bug->datatable->fieldList['resolvedBuild']['width']    = '120';
$config->bug->datatable->fieldList['resolvedBuild']['required'] = 'no';
$config->bug->datatable->fieldList['resolvedBuild']['control']  = 'select';
$config->bug->datatable->fieldList['resolvedBuild']['dataSource'] = array('module' => 'bug', 'method' =>'getRelatedObjects', 'params' => 'resolvedBuild&id,name');

$config->bug->datatable->fieldList['activatedCount']['title']    = 'activatedCountAB';
$config->bug->datatable->fieldList['activatedCount']['fixed']    = 'no';
$config->bug->datatable->fieldList['activatedCount']['width']    = '80';
$config->bug->datatable->fieldList['activatedCount']['required'] = 'no';

$config->bug->datatable->fieldList['activatedDate']['title']    = 'activatedDate';
$config->bug->datatable->fieldList['activatedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['activatedDate']['width']    = '90';
$config->bug->datatable->fieldList['activatedDate']['required'] = 'no';

$config->bug->datatable->fieldList['story']['title']      = 'story';
$config->bug->datatable->fieldList['story']['fixed']      = 'no';
$config->bug->datatable->fieldList['story']['width']      = '120';
$config->bug->datatable->fieldList['story']['required']   = 'no';
$config->bug->datatable->fieldList['story']['control']    = 'select';
$config->bug->datatable->fieldList['story']['dataSource'] = array('module' => 'story', 'method' =>'getProductStoryPairs', 'params' => '$productID');

$config->bug->datatable->fieldList['task']['title']      = 'task';
$config->bug->datatable->fieldList['task']['fixed']      = 'no';
$config->bug->datatable->fieldList['task']['width']      = '120';
$config->bug->datatable->fieldList['task']['required']   = 'no';
$config->bug->datatable->fieldList['task']['dataSource'] = array('module' => 'bug', 'method' =>'getRelatedObjects', 'params' => 'task&id,name');

$config->bug->datatable->fieldList['toTask']['title']    = 'toTask';
$config->bug->datatable->fieldList['toTask']['fixed']    = 'no';
$config->bug->datatable->fieldList['toTask']['width']    = '120';
$config->bug->datatable->fieldList['toTask']['required'] = 'no';

$config->bug->datatable->fieldList['keywords']['title']    = 'keywords';
$config->bug->datatable->fieldList['keywords']['fixed']    = 'no';
$config->bug->datatable->fieldList['keywords']['width']    = '100';
$config->bug->datatable->fieldList['keywords']['required'] = 'no';

$config->bug->datatable->fieldList['os']['title']    = 'os';
$config->bug->datatable->fieldList['os']['fixed']    = 'no';
$config->bug->datatable->fieldList['os']['width']    = '80';
$config->bug->datatable->fieldList['os']['required'] = 'no';
$config->bug->datatable->fieldList['os']['control']  = 'multiple';

$config->bug->datatable->fieldList['browser']['title']    = 'browser';
$config->bug->datatable->fieldList['browser']['fixed']    = 'no';
$config->bug->datatable->fieldList['browser']['width']    = '80';
$config->bug->datatable->fieldList['browser']['required'] = 'no';
$config->bug->datatable->fieldList['browser']['control']  = 'multiple';

$config->bug->datatable->fieldList['mailto']['title']    = 'mailto';
$config->bug->datatable->fieldList['mailto']['fixed']    = 'no';
$config->bug->datatable->fieldList['mailto']['width']    = '100';
$config->bug->datatable->fieldList['mailto']['required'] = 'no';

$config->bug->datatable->fieldList['closedBy']['title']    = 'closedBy';
$config->bug->datatable->fieldList['closedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['closedBy']['width']    = '80';
$config->bug->datatable->fieldList['closedBy']['required'] = 'no';

$config->bug->datatable->fieldList['closedDate']['title']    = 'closedDate';
$config->bug->datatable->fieldList['closedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['closedDate']['width']    = '90';
$config->bug->datatable->fieldList['closedDate']['required'] = 'no';

$config->bug->datatable->fieldList['lastEditedBy']['title']    = 'lastEditedBy';
$config->bug->datatable->fieldList['lastEditedBy']['fixed']    = 'no';
$config->bug->datatable->fieldList['lastEditedBy']['width']    = '90';
$config->bug->datatable->fieldList['lastEditedBy']['required'] = 'no';

$config->bug->datatable->fieldList['lastEditedDate']['title']    = 'lastEditedDateAB';
$config->bug->datatable->fieldList['lastEditedDate']['fixed']    = 'no';
$config->bug->datatable->fieldList['lastEditedDate']['width']    = '90';
$config->bug->datatable->fieldList['lastEditedDate']['required'] = 'no';

$config->bug->datatable->fieldList['actions']['title']    = 'actions';
$config->bug->datatable->fieldList['actions']['fixed']    = 'right';
$config->bug->datatable->fieldList['actions']['width']    = '150';
$config->bug->datatable->fieldList['actions']['required'] = 'yes';

$config->bug->datatable->fieldList['steps']['title']   = 'steps';
$config->bug->datatable->fieldList['steps']['control'] = 'textarea';

$config->bug->datatable->fieldList['case']['title'] = 'case';
$config->bug->datatable->fieldList['case']['dataSource'] = array('module' => 'bug', 'method' =>'getRelatedObjects', 'params' => 'case&id,title');
