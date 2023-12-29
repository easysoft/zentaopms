#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 upgradeModel->createNewProject();
timeout=0
cid=1

- 测试结束日期不能为空第end条的0属性 @『结束日期』不能为空。
- 测试新增的项目的 id 和 name
 - 属性id @5
 - 属性name @项目5
- 测试重复项目名称属性result @fail
- 测试项目合并后新的项目id属性4 @6

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/upgrade.class.php';

su('admin');

$project = zdTable('project');
$project->name->range('项目集1,项目1,项目2,项目3');
$project->parent->range('0,1{2},0');
$project->type->range('program,project{3}');
$project->gen('4');

$data = new stdclass();
$data->projectType   = 'project';
$data->projectName   = '';
$data->projectStatus = 'wait';
$data->PM            = '';
$data->begin         = '2023-12-21';
$data->end           = '';

$projectIdList = array();
$programID     = 1;

$upgrade = new upgradeTest();
$data->projectName = '项目4';
r($upgrade->createNewProjectTest($data, $programID, $projectIdList)) && p('end:0') && e('『结束日期』不能为空。'); // 测试结束日期不能为空

$data->projectType = 'execution';
$data->projectName = '项目5';
$data->end         = '2023-12-31';
r($upgrade->createNewProjectTest($data, $programID, $projectIdList)) && p('id,name') && e('5,项目5'); // 测试新增的项目的 id 和 name

$projectIdList = array(1, 2);
$data->projectType = 'project';
r($upgrade->createNewProjectTest($data, $programID, $projectIdList)) && p('result') && e('fail'); // 测试重复项目名称

$projectIdList = array(4);
r($upgrade->createNewProjectTest($data, $programID, $projectIdList)) && p('4') && e('6'); // 测试项目合并后新的项目id
