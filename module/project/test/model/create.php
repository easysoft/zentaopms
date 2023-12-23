#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
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

*/

global $tester;
$projectClass = new project();
$_POST['uid'] = '0';

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
$project->openedBy   = 'user1';
$project->hasProduct = 1;
$project->category   = '';
$project->status     = 'wait';
$project->openedBy   = 'admin';
$project->openedDate = helper::now();

$postData = new stdclass();
$postData->rawdata = clone $project;
$postData->rawdata->uid      = '64dda2xc';
$postData->rawdata->delta    = 0;
$postData->rawdata->products = array(1);

$normalProject = clone $project;

$emptyNameProject = clone $project;
$emptyNameProject->name = '';

$emptyBeginProject = clone $project;
$emptyBeginProject->name  = '测试新增项目二';
$emptyBeginProject->begin = '';

$emptyEndProject = clone $project;
$emptyEndProject->end  = '';
$emptyEndProject->name = '测试新增项目三';

$beginGtEndProject = clone $project;
$beginGtEndProject->end  = '2021-01-10';
$beginGtEndProject->name = '测试新增项目四';

r($projectClass->create($normalProject, $postData))     && p('name')             && e('测试新增项目一');
r($projectClass->create($emptyNameProject, $postData))  && p('message[name]:0')  && e('『项目名称』不能为空。');
r($projectClass->create($emptyEndProject, $postData))   && p('message[end]:0')   && e('『计划完成』不能为空。');
r($projectClass->create($beginGtEndProject, $postData)) && p('message[end]:0')   && e('『计划完成』应当大于『2022-02-07』。');
r($projectClass->create($emptyBeginProject, $postData)) && p('message[begin]:0') && e('『计划开始』不能为空。');
