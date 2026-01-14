#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/lib/tao.class.php';
su('admin');

$project = zenData('project');
$project->gen(1);

/**

title=测试 projectTao::doCreate();
timeout=0
cid=17899

*/

global $tester;
$projectClass = new projectTaoTest();

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

r($projectClass->doCreateTest($normalProject))     && p('name')    && e('测试新增项目一');
r($projectClass->doCreateTest($normalProject))     && p('name:0')  && e('『项目名称』已经有『测试新增项目一』这条记录了。');
r($projectClass->doCreateTest($emptyNameProject))  && p('name:0')  && e('『项目名称』不能为空。');
r($projectClass->doCreateTest($emptyEndProject))   && p('end:0')   && e('『计划完成』不能为空。');
r($projectClass->doCreateTest($beginGtEndProject)) && p('end:0')   && e('『计划完成』应当大于『2022-02-07』。');
r($projectClass->doCreateTest($emptyBeginProject)) && p('begin:0') && e('『计划开始』不能为空。');
