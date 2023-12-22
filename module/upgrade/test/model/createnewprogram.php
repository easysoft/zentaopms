#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->createProject();
timeout=0
cid=1

- 测试项目集名称不能为空第programName条的0属性 @『项目集名称』不能为空。
- 测试结束日期不能为空第end条的0属性 @『结束日期』不能为空。
- 测试项目名称不能为空第projectName条的0属性 @『项目名称』不能为空。
- 测试重复项目名称属性result @fail
- 测试完成日期应当大于开始日期第end条的0属性 @『计划完成』应当大于『2023-12-21』。
- 测试新增的项目集的grade 和 path
 - 属性id @4
 - 属性grade @1
 - 属性path @,4,

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

$project = zdTable('project');
$project->name->range('项目1{2},项目2,项目集1');
$project->type->range('project');
$project->gen('3');

$data = new stdclass();
$data->projectType   = 'project';
$data->programName   = '';
$data->programStatus = '';
$data->begin         = '2023-12-21';
$data->end           = '';

$projectIdList = array();

$upgrade = new upgradeTest();
r($upgrade->createNewProgramTest($data, $projectIdList)) && p('programName:0') && e('『项目集名称』不能为空。'); // 测试项目集名称不能为空

$data->programName = '项目集2';
r($upgrade->createNewProgramTest($data, $projectIdList)) && p('end:0') && e('『结束日期』不能为空。'); // 测试结束日期不能为空

$data->end         = '2023-12-31';
$data->projectType = 'execution';
$data->projectName = '';
r($upgrade->createNewProgramTest($data, $projectIdList)) && p('projectName:0') && e('『项目名称』不能为空。'); // 测试项目名称不能为空

$data->projectType = 'project';

$projectIdList = array(1, 2);
r($upgrade->createNewProgramTest($data, $projectIdList)) && p('result') && e('fail'); // 测试重复项目名称

$projectIdList = array(1, 3);
$data->end = '2023-12-20';
r($upgrade->createNewProgramTest($data, $projectIdList)) && p('end:0') && e('『计划完成』应当大于『2023-12-21』。'); // 测试完成日期应当大于开始日期

$data->end = '2023-12-31';
r($upgrade->createNewProgramTest($data, $projectIdList)) && p('id;grade;path', ';') && e('4;1;,4,'); // 测试新增的项目集的grade 和 path