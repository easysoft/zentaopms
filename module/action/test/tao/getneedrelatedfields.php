#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 actionTao::getNeedRelatedFields();
cid=0

- 测试story类型对象的相关字段获取 >> 期望返回product数组和project、execution字段@1,0,0
- 测试productplan类型对象的相关字段获取 >> 期望正确返回product字段@0,0,0
- 测试branch类型对象的相关字段获取 >> 期望正确返回product字段@0,0,0
- 测试testcase类型对象的相关字段获取 >> 期望正确返回相关字段@1,1,6
- 测试case类型对象的相关字段获取 >> 期望正确返回相关字段@1,1,6
- 测试task类型对象的相关字段获取 >> 期望正确返回相关字段@1,1,6
- 测试release类型对象的相关字段获取 >> 期望正确返回相关字段@1,1
- 测试不存在的对象类型的相关字段获取 >> 期望正确处理不存在的情况@0,0,0

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 准备测试数据
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->gen(5);

$project = zenData('project');
$project->id->range('1-10');
$project->name->range('项目1,项目2,项目3,项目4,项目5,执行1,执行2,执行3,执行4,执行5');
$project->type->range('project{5},execution{5}');
$project->parent->range('0{5},1,2,3,4,5');
$project->gen(10);

$story = zenData('story');
$story->id->range('1-5');
$story->product->range('1-5');
$story->title->range('需求1,需求2,需求3,需求4,需求5');
$story->gen(5);

$productplan = zenData('productplan');
$productplan->id->range('1-5');
$productplan->product->range('1-5');
$productplan->title->range('计划1,计划2,计划3,计划4,计划5');
$productplan->gen(5);

$branch = zenData('branch');
$branch->id->range('1-5');
$branch->product->range('1-5');
$branch->name->range('分支1,分支2,分支3,分支4,分支5');
$branch->gen(5);

$case = zenData('case');
$case->id->range('1-5');
$case->project->range('1-5');
$case->product->range('1-5');
$case->execution->range('6-10');
$case->title->range('测试用例1,测试用例2,测试用例3,测试用例4,测试用例5');
$case->gen(5);

$task = zenData('task');
$task->id->range('1-5');
$task->project->range('1-5');
$task->execution->range('6-10');
$task->name->range('任务1,任务2,任务3,任务4,任务5');
$task->gen(5);

$release = zenData('release');
$release->id->range('1-5');
$release->product->range('1-5');
$release->build->range('1-5');
$release->name->range('发布1,发布2,发布3,发布4,发布5');
$release->gen(5);

$build = zenData('build');
$build->id->range('1-5');
$build->product->range('1-5');
$build->project->range('1-5');
$build->execution->range('6-10');
$build->name->range('版本1,版本2,版本3,版本4,版本5');
$build->gen(5);

$testtask = zenData('testtask');
$testtask->id->range('1-5');
$testtask->project->range('1-5');
$testtask->execution->range('6-10');
$testtask->name->range('测试任务1,测试任务2,测试任务3,测试任务4,测试任务5');
$testtask->gen(5);

su('admin');

$actionTest = new actionTest();

r($actionTest->getNeedRelatedFieldsTest('story', 1, 'created', '')) && p('0:0;1;2', ';') && e('1;0;0'); // 测试story类型对象的相关字段获取
r($actionTest->getNeedRelatedFieldsTest('productplan', 1, '', '')) && p('0,1,2') && e('1,0,0'); // 测试productplan类型对象的相关字段获取
r($actionTest->getNeedRelatedFieldsTest('branch', 1, '', '')) && p('0,1,2') && e('1,0,0'); // 测试branch类型对象的相关字段获取
r($actionTest->getNeedRelatedFieldsTest('testcase', 1, 'linked2testtask', '1')) && p('0:0;1;2', ';') && e('1;1;6'); // 测试testcase类型对象的相关字段获取
r($actionTest->getNeedRelatedFieldsTest('case', 1, 'run', '1')) && p('0:0;1;2', ';') && e('1;1;6'); // 测试case类型对象的相关字段获取
r($actionTest->getNeedRelatedFieldsTest('task', 1, '', '')) && p('0:0;1;2', ';') && e('1;1;6'); // 测试task类型对象的相关字段获取
r($actionTest->getNeedRelatedFieldsTest('release', 1, '', '')) && p('0:0;1', ';') && e('1;1'); // 测试release类型对象的相关字段获取
r($actionTest->getNeedRelatedFieldsTest('unknown', 999, '', '')) && p('0:0;1;2', ';') && e('0;0;0'); // 测试不存在的对象类型的相关字段获取
