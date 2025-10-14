#!/usr/bin/env php
<?php

/**

title=测试单报表
timeout=0
cid=1

- 执行tester模块的check方法，参数是'testTaskPerRunResult', $testTaskPerRunResult▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @testTaskPerRunResult报表数据正确
- 执行tester模块的check方法，参数是'testTaskPerType', $testTaskPerType▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @testTaskPerType报表数据正确
- 执行tester模块的check方法，参数是'testTaskPerModule', $testTaskPerModule▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @testTaskPerModule报表数据正确
- 执行tester模块的check方法，参数是'testTaskPerRunner', $testTaskPerRunner▫
 - 最终测试状态 @SUCCESS
 - 测试结果 @testTaskPerRunner报表数据正确

 */

chdir(__DIR__);
include '../lib/ui/report.ui.class.php';

$product = zenData('product');
$product->id->range('1-100');
$product->name->range('产品1, 产品2');
$product->type->range('normal');
$product->gen(2);

$project = zenData('project');
$project->id->range('1-100');
$project->project->range('0, 1{3}');
$project->model->range('scrum, []{3}');
$project->type->range('project, sprint{3}');
$project->auth->range('extend, []{3}');
$project->storytype->range('`story,epic,requirement`');
$project->path->range('`,1,`, `,1,2,`, `,1,3,`, `,1,4,`');
$project->grade->range('1');
$project->name->range('项目1, 项目1执行1, 项目1执行2, 项目1执行3');
$project->hasProduct->range('1');
$project->status->range('wait');
$project->acl->range('open');
$project->gen(4);

$projectProduct = zenData('projectproduct');
$projectProduct->project->range('1, 2, 3, 4');
$projectProduct->product->range('1{4}, 2{4}');
$projectProduct->gen(8);

$module = zenData('module');
$module->id->range('1-100');
$module->root->range('1');
$module->branch->range('0');
$module->name->range('模块1, 模块2, 模块3');
$module->parent->range('0');
$module->path->range('`,1,`, `,2,`, `,3,`');
$module->grade->range('1');
$module->type->range('case');
$module->short->range('0');
$module->gen(3);

$build = zenData('build');
$build->id->range('1-100');
$build->project->range('1');
$build->product->range('1');
$build->branch->range('0');
$build->execution->range('2{4}, 3{2}');
$build->name->range('构建1, 构建2, 构建3, 构建4, 构建5, 构建6');
$build->scmPath->range('[]');
$build->filePath->range('[]');
$build->deleted->range('0');
$build->gen(1);

$testtask = zenData('testtask');
$testtask->id->range('1-100');
$testtask->project->range('1');
$testtask->product->range('1');
$testtask->name->range('测试单1, 测试单2, 测试单3, 测试单4, 测试单5, 测试单6');
$testtask->execution->range('2{4}, 3{2}');
$testtask->build->range('1-6');
$testtask->begin->range('(-2D)-(-D):1D')->type('timestamp')->format('YY/MM/DD');
$testtask->end->range('(+D)-(+2D):1D')->type('timestamp')->format('YY/MM/DD');
$testtask->status->range('wait{5}, doing{5}, done{3}, blocked{2}');
$testtask->deleted->range('0');
$testtask->gen(1);

$case = zenData('case');
$case->id->range('1-100');
$case->project->range('1{2}, 0{100}');
$case->product->range('1{10}, 2{5}');
$case->execution->range('0{5}, 2{10}');
$case->module->range('1{2}, 2{1}, 0{100}');
$case->story->range('1{2}, 2{3}, 0{100}');
$case->title->range('1-100');
$case->type->range('feature{6}, performance{3}, install{1}, security{2}');
$case->status->range('normal');
$case->deleted->range('0{14}, 1');
$case->gen(15);

$testrun = zenData('testrun');
$testrun->id->range('1-100');
$testrun->task->range('1');
$testrun->case->range('1-100');
$testrun->version->range('1');
$testrun->lastRunner->range('admin{2}, user1{3}, user2{4}, []{100}');
$testrun->lastRunResult->range('pass{4}, fail{1}, blocked{4}, []{100}');
$testrun->status->range('normal');
$testrun->gen(10);

$user = zenData('user');
$user->id->range('1-100');
$user->dept->range('0, 1{2}, 2{3}, 3{5}');
$user->account->range('admin, user1, user2, user3, user4, user5, user11, user12, user13, user14, user15');
$user->realname->range('admin, USER1, USER2, USER3, USER4, USER5, USER11, USER12, USER13, USER14, USER15');
$user->password->range($config->uitest->defaultPassword)->format('md5');
$user->gen(5);

$tester = new reportTester();
$tester->login();

$testTaskPerRunResult = array(
    array('zh-cn' => '通过', 'en' => 'Pass', 'value' => '4', 'percent' => '40%'),
    array('zh-cn' => '阻塞', 'en' => 'Blocked', 'value' => '4', 'percent' => '40%'),
    array('zh-cn' => '失败', 'en' => 'Fail', 'value' => '1', 'percent' => '10%'),
    array('zh-cn' => '未执行', 'en' => 'Pending', 'value' => '1', 'percent' => '10%')
);
$testTaskPerType = array(
    array('zh-cn' => '功能测试', 'en' => 'Feature', 'value' => '6', 'percent' => '60%'),
    array('zh-cn' => '性能测试', 'en' => 'Performance', 'value' => '3', 'percent' => '30%'),
    array('zh-cn' => '安装部署', 'en' => 'Installation', 'value' => '1', 'percent' => '10%')
);
$testTaskPerModule = array(
    array('zh-cn' => '/模块1', 'en' => '/模块1', 'value' => '2', 'percent' => '20%'),
    array('zh-cn' => '/模块2', 'en' => '/模块2', 'value' => '1', 'percent' => '10%'),
    array('zh-cn' => '/', 'en' => '/', 'value' => '7', 'percent' => '70%')
);
$testTaskPerRunner = array(
    array('zh-cn' => 'USER1', 'en' => 'USER1', 'value' => '3', 'percent' => '30%'),
    array('zh-cn' => 'admin', 'en' => 'admin', 'value' => '2', 'percent' => '20%'),
    array('zh-cn' => 'USER2', 'en' => 'USER2', 'value' => '4', 'percent' => '40%'),
    array('zh-cn' => '未执行', 'en' => 'Pending', 'value' => '1', 'percent' => '10%')
);
r($tester->check('testTaskPerRunResult', $testTaskPerRunResult)) && p('status,message') && e('SUCCESS,testTaskPerRunResult报表数据正确');
r($tester->check('testTaskPerType', $testTaskPerType))           && p('status,message') && e('SUCCESS,testTaskPerType报表数据正确');
r($tester->check('testTaskPerModule', $testTaskPerModule))       && p('status,message') && e('SUCCESS,testTaskPerModule报表数据正确');
r($tester->check('testTaskPerRunner', $testTaskPerRunner))       && p('status,message') && e('SUCCESS,testTaskPerRunner报表数据正确');
$tester->closeBrowser();
