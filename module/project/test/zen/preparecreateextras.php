#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';
su('admin');

$projectTable = zenData('project');
$projectTable->id->range('1-10');
$projectTable->name->range('项目1,项目2,项目3,项目4,项目5,项目6,项目7,项目8,项目9,项目10');
$projectTable->type->range('project{10}');
$projectTable->model->range('waterfall{10}');
$projectTable->status->range('wait{10}');
$projectTable->hasProduct->range('0{10}');
$projectTable->multiple->range('1{10}');
$projectTable->gen(10);

/**

title=测试 projectZen::prepareCreateExtras();
timeout=0
cid=17950

- 执行project模块的prepareCreateExtrasTest方法，参数是$testData, 0 属性end @『计划完成』不能为空。
- 执行project模块的prepareCreateExtrasTest方法，参数是$testData, 0 属性days @可用工作日不能超过『-5』天
- 执行project模块的prepareCreateExtrasTest方法，参数是$testData, 0 属性end @2025-07-17
- 执行project模块的prepareCreateExtrasTest方法，参数是$testData, 1 属性type @project
- 执行project模块的prepareCreateExtrasTest方法，参数是$testData, 0 属性acl @privately
*/

global $tester;
$project = new projectZenTest();

$testData = array();

$testData['name'] = 'test0707';
r($project->prepareCreateExtrasTest($testData, 0)) && p('end') && e('『计划完成』不能为空。');
$testData['end'] = '2025-07-01';
r($project->prepareCreateExtrasTest($testData, 0)) && p('days') && e('可用工作日不能超过『-5』天');
$testData['end'] = '2025-07-17';
r($project->prepareCreateExtrasTest($testData, 0)) && p('end') && e('2025-07-17');
$copyProjectData = array('name' => 'test0708', 'end' => '2025-07-17');
$preparedProject = $project->prepareCreateExtrasTest($copyProjectData, 1);
r($preparedProject->type) && p() && e('project');
$testData['acl'] = 'private';
r($project->prepareCreateExtrasTest($testData, 0)) && p('acl') && e('private');
