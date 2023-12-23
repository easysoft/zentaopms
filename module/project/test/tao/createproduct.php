#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

$program = zdTable('project');
$program->id->range('1');
$program->name->range('项目集一');
$program->model->range('program');
$program->code->range('项目集代号');
$program->desc->range('测试项目集');
$program->gen(1);

zdTable('team')->gen(0);
zdTable('product')->gen(0);

/**

title=测试 projectModel->create();
timeout=0
cid=1

*/

global $tester;
$_POST['uid'] = '0';

$program      = $tester->loadModel('project')->getByID(1);
$projectClass = new project();

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

$project = $projectClass->create($project, $postData);
$projectID = $project->id;

r($projectClass->testCreateProduct($projectID, $project,           $postData, $program)) && p()         && e('1');
r($projectClass->testCreateProduct($projectID, $emptyNameProject,  $postData, $program)) && p('name:0') && e('『产品名称』不能为空。');
r($projectClass->testCreateProduct($projectID, $project,           $postData, $program)) && p('name:0') && e('『产品名称』已经有『测试新增项目一』这条记录了。');
r($projectClass->testCreateProduct($projectID, $hasProductProject, $postData, $program)) && p()         && e('1');
