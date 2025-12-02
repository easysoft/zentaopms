#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/project.unittest.class.php';

$project = zenData('project');
$project->id->range('1-5');
$project->name->range('项目1,项目2,迭代1,迭代2,迭代3');
$project->type->range('project{2},sprint,stage,kanban');
$project->status->range('doing');
$project->parent->range('0,0,1,1,2');
$project->project->range('0,0,1,1,2');
$project->grade->range('2{2},1{3}');
$project->multiple->range('1,0,1{3}');
$project->path->range('1,2,`1,3`,`1,4`,`2,5`')->prefix(',')->postfix(',');
$project->begin->range('20230102 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$project->end->range('20230212 000000:0')->type('timestamp')->format('YYYY-MM-DD');
$project->gen(5);

$product = zenData('product');
$product->type->range('normal,branch,platform');
$product->gen(20);

su('admin');

/**

title=测试projectModel->buildProjectBuildSearchForm();
cid=17802

- 不传递项目ID @0
- 项目ID不存在 @0
- 不启用迭代的项目 @projectBuild
- 启用迭代的项目 @projectBuild|execution
- 正常的产品 @projectBuild|execution
- 多分支产品 @projectBuild|branch|execution
- 正确的搜索类型 @executionBuild|branch
- 错误的搜索类型 @executionBuild|branc

*/

$queryIDList = array('0', '1');

$project = new projectTest();
r($project->buildProjectBuildSearchFormTest(0, 0, 'project'))   && p() && e('0'); // 不传递项目ID
r($project->buildProjectBuildSearchFormTest(100, 0, 'project')) && p() && e('0'); // 项目ID不存在

r($project->buildProjectBuildSearchFormTest(2, 0, 'project')) && p() && e('projectBuild');           // 不启用迭代的项目
r($project->buildProjectBuildSearchFormTest(1, 0, 'project')) && p() && e('projectBuild|execution'); // 启用迭代的项目

r($project->buildProjectBuildSearchFormTest(1, 1, 'project')) && p() && e('projectBuild|execution');        // 正常的产品
r($project->buildProjectBuildSearchFormTest(1, 2, 'project')) && p() && e('projectBuild|branch|execution'); // 多分支产品

r($project->buildProjectBuildSearchFormTest(1, 2, 'execution')) && p() && e('executionBuild|branch'); // 正确的搜索类型
r($project->buildProjectBuildSearchFormTest(1, 2, 'error'))     && p() && e('executionBuild|branch'); // 错误的搜索类型
