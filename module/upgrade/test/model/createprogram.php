#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->createProgram();
timeout=0
cid=1

- 测试选择项目集1，没有选择项目，没有选择产品线时
 -  @1
 - 属性1 @0
 - 属性2 @0
- 测试选择项目集1，没有选择项目，选择产品线1时
 -  @1
 - 属性1 @0
 - 属性2 @1
- 测试选择项目集1，选择项目2，选择产品线1时
 -  @1
 - 属性1 @2
 - 属性2 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

su('admin');

$project = zdTable('project');
$project->name->range('项目集1,项目1,项目2,项目3');
$project->parent->range('0,1{2},0');
$project->type->range('program,project{3}');
$project->gen('4');

$line = zdTable('module');
$line->name->range('产品线1');
$line->type->range('line');
$line->gen('1');

$data = new stdclass();
$data->programID     = 1;
$data->projectType   = 'project';
$data->programStatus = 'closed';
$data->begin         = '2023-12-21';
$data->end           = '2023-12-31';

$projectIdList = array();

$upgrade = new upgradeTest();
r($upgrade->createProgramTest($data, $projectIdList)) && p('0,1,2') && e('1,0,0'); // 测试选择项目集1，没有选择项目，没有选择产品线时

$data->lines = 1;
r($upgrade->createProgramTest($data, $projectIdList)) && p('0,1,2') && e('1,0,1'); // 测试选择项目集1，没有选择项目，选择产品线1时

$data->sprints  = '1,2,3';
$data->projects = 2;
$data->projectStatus = 'closed';
r($upgrade->createProgramTest($data, $projectIdList)) && p('0,1,2') && e('1,2,1'); // 测试选择项目集1，选择项目2，选择产品线1时