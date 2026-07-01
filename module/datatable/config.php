<?php
$config->datatable->moduleAlias['product-browse']     = 'story';
$config->datatable->moduleAlias['execution-story']    = 'story';
$config->datatable->moduleAlias['execution-task']     = 'task';
$config->datatable->moduleAlias['program-project']    = 'project';
$config->datatable->moduleAlias['project-bug']        = 'bug';
$config->datatable->moduleAlias['execution-bug']      = 'bug';
$config->datatable->moduleAlias['execution-build']    = 'build';
$config->datatable->moduleAlias['project-build']      = 'build';
$config->datatable->moduleAlias['execution-testcase'] = 'testcase';

$config->datatable->workflowLayoutMap['build-build']           = array('module' => 'build', 'method' => 'browse'); // 版本加载build-browse的layout配置。
$config->datatable->workflowLayoutMap['task-task']             = array('module' => 'task', 'method' => 'browse'); // 任务加载task-browse的layout配置。
$config->datatable->workflowLayoutMap['task-importTask']       = array('module' => 'task', 'method' => 'browse'); // 转入任务加载task-browse的layout配置。
$config->datatable->workflowLayoutMap['my-task']               = array('module' => 'task', 'method' => 'browse'); // 地盘任务加载task-browse的layout配置。
$config->datatable->workflowLayoutMap['my-story']              = array('module' => 'story', 'method' => 'browse'); // 地盘需求加载story-browse的layout配置。
$config->datatable->workflowLayoutMap['my-requirement']        = array('module' => 'requirement', 'method' => 'browse'); // 地盘用户需求加载requirement-browse的layout配置。
$config->datatable->workflowLayoutMap['my-epic']               = array('module' => 'epic', 'method' => 'browse'); // 地盘业务需求加载epic-browse的layout配置。
$config->datatable->workflowLayoutMap['my-bug']                = array('module' => 'bug', 'method' => 'browse'); // 地盘Bug加载bug-browse的layout配置。
$config->datatable->workflowLayoutMap['my-testtask']           = array('module' => 'testtask', 'method' => 'browse'); // 地盘测试单加载testtask-browse的layout配置。
$config->datatable->workflowLayoutMap['bug-bug']               = array('module' => 'bug', 'method' => 'browse'); // 执行bug列表加载bug-browse的layout配置。
$config->datatable->workflowLayoutMap['story-story']           = array('module' => 'story', 'method' => 'browse'); // 执行需求列表加载story-browse的layout配置。
$config->datatable->workflowLayoutMap['testcase-testcase']     = array('module' => 'testcase', 'method' => 'browse'); // 执行用例列表加载testcase-browse的layout配置。
$config->datatable->workflowLayoutMap['projectrelease-browse'] = array('module' => 'release', 'method' => 'browse'); // 项目发布加载release-browse的layout配置。

$config->datatable->noProductModule = ',review,';

$config->datatable->defaultColConfig['id']['width']    = 60;
$config->datatable->defaultColConfig['id']['fixed']    = 'left';
$config->datatable->defaultColConfig['id']['required'] = true;

$config->datatable->defaultColConfig['checkID']['width']    = 80;
$config->datatable->defaultColConfig['checkID']['fixed']    = 'left';
$config->datatable->defaultColConfig['checkID']['required'] = true;

$config->datatable->defaultColConfig['title']['width']    = 0.44;
$config->datatable->defaultColConfig['title']['fixed']    = 'left';
$config->datatable->defaultColConfig['title']['required'] = true;

$config->datatable->defaultColConfig['shortTitle']['width']    = 0.2;
$config->datatable->defaultColConfig['shortTitle']['fixed']    = 'left';
$config->datatable->defaultColConfig['shortTitle']['required'] = true;

$config->datatable->defaultColConfig['nestedTitle']['width']    = 0.5;
$config->datatable->defaultColConfig['nestedTitle']['fixed']    = 'left';
$config->datatable->defaultColConfig['nestedTitle']['required'] = true;

$config->datatable->defaultColConfig['shortNestedTitle']['width']    = 0.33;
$config->datatable->defaultColConfig['shortNestedTitle']['fixed']    = 'left';
$config->datatable->defaultColConfig['shortNestedTitle']['required'] = true;

$config->datatable->defaultColConfig['actions']['minWidth'] = 120;
$config->datatable->defaultColConfig['actions']['fixed']    = 'right';
$config->datatable->defaultColConfig['actions']['required'] = true;

$config->datatable->defaultColConfig['pri']['width'] = 68;

$config->datatable->defaultColConfig['icon']['width'] = 52;

$config->datatable->defaultColConfig['time']['width'] = 64;

$config->datatable->defaultColConfig['burn']['width'] = 88;

$config->datatable->defaultColConfig['date']['width'] = 96;

$config->datatable->defaultColConfig['user']['width'] = 80;

$config->datatable->defaultColConfig['text']['width'] = 136;

$config->datatable->defaultColConfig['desc']['width'] = 160;

$config->datatable->defaultColConfig['count']['width'] = 92;

$config->datatable->defaultColConfig['money']['width'] = 96;

$config->datatable->defaultColConfig['avatar']['width'] = 44;

$config->datatable->defaultColConfig['number']['width'] = 64;

$config->datatable->defaultColConfig['status']['width'] = 80;

$config->datatable->defaultColConfig['percent']['width'] = 64;

$config->datatable->defaultColConfig['assign']['width'] = 108;

$config->datatable->defaultColConfig['progress']['width'] = 64;

$config->datatable->defaultColConfig['category']['width'] = 80;

$config->datatable->defaultColConfig['severity']['width'] = 92;

$config->datatable->defaultColConfig['datetime']['width'] = 128;

$config->datatable->defaultColConfig['avatarBtn']['width'] = 108;

$config->datatable->defaultColConfig['avatarName']['width'] = 108;
