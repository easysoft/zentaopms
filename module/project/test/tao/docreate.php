#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . "/test/lib/init.php";
include dirname(__FILE__, 2) . '/project.class.php';
su('admin');

$project = zdTable('project');
$project->gen(1);

/**

title=测试 projectTao::doCreate();
timeout=0
cid=1

- 执行projectClass模块的doCreate方法，参数是$normalProject 属性name @测试新增项目一

- 执行projectClass模块的doCreate方法，参数是$normalProject 第name条的0属性 @『项目名称』已经有『测试新增项目一』这条记录了。

- 执行projectClass模块的doCreate方法，参数是$emptyNameProject 第name条的0属性 @『项目名称』不能为空。

- 执行projectClass模块的doCreate方法，参数是$emptyEndProject 第end条的0属性 @『计划完成』不能为空。

- 执行projectClass模块的doCreate方法，参数是$beginGtEndProject 第end条的0属性 @『计划完成』应当大于『2022-02-07』。

- 执行projectClass模块的doCreate方法，参数是$emptyBeginProject 第begin条的0属性 @『计划开始』不能为空。

*/

global $tester;
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

r($projectClass->doCreate($normalProject))     && p('name')    && e('测试新增项目一');
r($projectClass->doCreate($normalProject))     && p('name:0')  && e('『项目名称』已经有『测试新增项目一』这条记录了。');
r($projectClass->doCreate($emptyNameProject))  && p('name:0')  && e('『项目名称』不能为空。');
r($projectClass->doCreate($emptyEndProject))   && p('end:0')   && e('『计划完成』不能为空。');
r($projectClass->doCreate($beginGtEndProject)) && p('end:0')   && e('『计划完成』应当大于『2022-02-07』。');
r($projectClass->doCreate($emptyBeginProject)) && p('begin:0') && e('『计划开始』不能为空。');