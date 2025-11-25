#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getDataOfStoriesPerModule();
timeout=0
cid=18517

- 测试默认story类型的模块统计数据 @3
- 测试带查询条件的模块统计第1条的value属性 @6
- 测试requirement类型的模块统计 @3
- 测试空数据的处理 @0
- 测试模块名称数据结构第1条的name属性 @/这是一个模块1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 准备story测试数据
$story = zenData('story');
$story->id->range('1-20');
$story->product->range('1');
$story->branch->range('0{15},1{5}');
$story->module->range('1{6},2{8},3{6}');
$story->type->range('story{15},requirement{5}');
$story->status->range('active');
$story->deleted->range('0');
$story->gen(20);

// 准备module测试数据
$module = zenData('module');
$module->id->range('1-5');
$module->root->range('1');
$module->name->range('这是一个模块1,这是一个模块2,这是一个模块3,这是一个模块4,这是一个模块5');
$module->type->range('story');
$module->deleted->range('0');
$module->gen(5);

// 准备branch测试数据
$branch = zenData('branch');
$branch->id->range('1-2');
$branch->product->range('1');
$branch->name->range('主分支,开发分支');
$branch->status->range('active');
$branch->deleted->range('0');
$branch->gen(2);

// 准备product测试数据
zenData('product')->gen(1);

su('admin');

$storyTest = new storyTest();

// 清除session条件，测试无条件情况
unset($_SESSION['storyOnlyCondition']);
unset($_SESSION['storyQueryCondition']);

r(count($storyTest->getDataOfStoriesPerModuleTest('story'))) && p() && e('3'); // 测试默认story类型的模块统计数据

// 设置查询条件测试
$_SESSION['storyOnlyCondition'] = true;
$_SESSION['storyQueryCondition'] = "`id` <= 10";

$result = $storyTest->getDataOfStoriesPerModuleTest('story');
r($result) && p('1:value') && e('6'); // 测试带查询条件的模块统计

// 测试requirement类型
r(count($storyTest->getDataOfStoriesPerModuleTest('requirement'))) && p() && e('3'); // 测试requirement类型的模块统计

// 测试空数据情况
$_SESSION['storyQueryCondition'] = "`id` > 30";
r(count($storyTest->getDataOfStoriesPerModuleTest('story'))) && p() && e('0'); // 测试空数据的处理

// 重置条件，测试模块名称拼接
unset($_SESSION['storyOnlyCondition']);
unset($_SESSION['storyQueryCondition']);
$finalResult = $storyTest->getDataOfStoriesPerModuleTest('story');
r($finalResult) && p('1:name') && e('/这是一个模块1'); // 测试模块名称数据结构