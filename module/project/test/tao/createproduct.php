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

/**

title=测试 projectModel->create();
timeout=0
cid=1

- 执行projectClass模块的create方法，参数是$normalProject, $postData属性name @测试新增项目一

- 执行projectClass模块的create方法，参数是$emptyNameProject, $postData @『项目名称』不能为空。

- 执行projectClass模块的create方法，参数是$emptyEndProject, $postData @『计划完成』不能为空。

- 执行projectClass模块的create方法，参数是$beginGtEndProject, $postData @『计划完成』应当大于『2022-02-07』。

- 执行projectClass模块的create方法，参数是$emptyBeginProject, $postData @『计划开始』不能为空。



*/

global $tester;
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

$postData = new stdclass();
$postData->rawdata = clone $project;
$postData->rawdata->uid      = '64dda2xc';
$postData->rawdata->delta    = 0;
$postData->rawdata->products = array(1);

$projectID = $projectClass->create($normalProject, $postData);

r($projectClass->createProduct($projectID, $project, $postData, $program))  && p('name')  && e('测试新增项目一');                       
//r($projectClass->createProduct($emptyNameProject, $postData))  && p('message[name]:0')  && e('『项目名称』不能为空。');               
//r($projectClass->createProduct($emptyEndProject, $postData))   && p('message[end]:0')   && e('『计划完成』不能为空。');               
//r($projectClass->createProduct($beginGtEndProject, $postData)) && p('message[end]:0')   && e('『计划完成』应当大于『2022-02-07』。');
//r($projectClass->createProduct($emptyBeginProject, $postData)) && p('message[begin]:0') && e('『计划开始』不能为空。');
