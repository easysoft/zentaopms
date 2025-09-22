#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';
su('admin');

$program = zenData('project');
$program->id->range('1');
$program->name->range('项目集一');
$program->model->range('program');
$program->code->range('项目集代号');
$program->desc->range('测试项目集');
$program->gen(1);

zenData('team')->gen(0);
zenData('product')->gen(0);

/**

title=测试 projectModel->create();
timeout=0
cid=1

- 执行projectClass模块的createProductTest方法，参数是$projectID, $project, $postData, $program  @1
- 执行$product
 - 属性id @1
 - 属性name @测试新增项目一
- 执行projectClass模块的createProductTest方法，参数是$projectID, $emptyNameProject, $postData, $program 第name条的0属性 @『产品名称』不能为空。
- 执行projectClass模块的createProductTest方法，参数是$projectID, $project, $postData, $program 第name条的0属性 @『产品名称』已经有『测试新增项目一』这条记录了。
- 执行projectClass模块的createProductTest方法，参数是$projectID, $hasProductProject, $postData, $program  @1
- 执行$product
 - 属性id @2
 - 属性name @测试新增产品一

*/

global $tester;
$_POST['uid'] = '0';

$program      = $tester->loadModel('project')->getByID(1);
$projectClass = new projectTest();

$project = new stdclass();
$project->parent     = 0;
$project->name       = '测试新增项目一';
$project->budget     = '';
$project->budgetUnit = 'CNY';
$project->begin      = '2022-02-07';
$project->end        = '2023-01-01';
$project->desc       = '测试项目描述';
$project->acl        = 'private';
$project->whitelist  = 'user1,user2,user3';
$project->PM         = 'admin';
$project->type       = 'project';
$project->model      = 'scrum';
$project->multiple   = 1;
$project->hasProduct = 1;
$project->openedBy   = 'admin';
$project->openedDate = '2023-01-01';

$emptyNameProject = clone $project;
unset($emptyNameProject->name);

$hasProductProject = clone $project;
$hasProductProject->hasProduct = 1;
$hasProductProject->name       = '测试新增产品一';

$postData = new stdclass();
$postData->rawdata = clone $project;
$postData->rawdata->uid      = '64dda2xc';
$postData->rawdata->delta    = 0;
$postData->rawdata->products = array(1);

$project = $projectClass->createTest($project, $postData);
$projectID = $project->id;

r($projectClass->createProductTest($projectID, $project,           $postData, $program)) && p()         && e('1');

$product = $tester->dao->select('*')->from(TABLE_PRODUCT)->orderBy('id_desc')->limit(1)->fetch();
r($product) && p('id,name') && e('1,测试新增项目一');

r($projectClass->createProductTest($projectID, $emptyNameProject,  $postData, $program)) && p('name:0') && e('『产品名称』不能为空。');
r($projectClass->createProductTest($projectID, $project,           $postData, $program)) && p('name:0') && e('『产品名称』已经有『测试新增项目一』这条记录了。');
r($projectClass->createProductTest($projectID, $hasProductProject, $postData, $program)) && p()         && e('1');

$product = $tester->dao->select('*')->from(TABLE_PRODUCT)->orderBy('id_desc')->limit(1)->fetch();
r($product) && p('id,name') && e('2,测试新增产品一');
