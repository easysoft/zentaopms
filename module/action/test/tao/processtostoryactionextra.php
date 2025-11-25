#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=- 测试非影子产品的故事action extra处理属性extra @*
timeout=0
cid=14968

- 测试非影子产品的故事action extra处理属性extra @*#1 用户故事1*
- 测试影子产品的故事action extra处理(有项目关联)属性extra @*#4 需求1*
- 测试影子产品的故事action extra处理(无项目关联)属性extra @*#8 Feature1*
- 测试不存在的故事ID属性extra @999
- 测试产品字段为空的情况属性extra @*#2 用户故事2*

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

su('admin');

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,影子产品1,影子产品2');
$product->shadow->range('0{3},1{2}');
$product->gen(5);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1-5');
$story->title->range('用户故事1,用户故事2,用户故事3,需求1,需求2,Epic1,Epic2,Feature1,Feature2,Story10');
$story->type->range('story{7},requirement{2},epic');
$story->gen(10);

$projectstory = zenData('projectstory');
$projectstory->project->range('101-105');
$projectstory->story->range('4,5,4,6,7');
$projectstory->gen(5);

$actionTest = new actionTest();

$action1 = new stdClass();
$action1->objectID = 1;
$action1->product = '1';
$action1->extra = '1';
r($actionTest->processToStoryActionExtraTest($action1)) && p('extra') && e('*#1 用户故事1*'); // 测试非影子产品的故事action extra处理

$action2 = new stdClass();
$action2->objectID = 2;
$action2->product = '4';
$action2->extra = '4';
r($actionTest->processToStoryActionExtraTest($action2)) && p('extra') && e('*#4 需求1*'); // 测试影子产品的故事action extra处理(有项目关联)

$action3 = new stdClass();
$action3->objectID = 3;
$action3->product = '5';
$action3->extra = '8';
r($actionTest->processToStoryActionExtraTest($action3)) && p('extra') && e('*#8 Feature1*'); // 测试影子产品的故事action extra处理(无项目关联)

$action4 = new stdClass();
$action4->objectID = 4;
$action4->product = '1';
$action4->extra = '999';
r($actionTest->processToStoryActionExtraTest($action4)) && p('extra') && e('999'); // 测试不存在的故事ID

$action5 = new stdClass();
$action5->objectID = 5;
$action5->product = '';
$action5->extra = '2';
r($actionTest->processToStoryActionExtraTest($action5)) && p('extra') && e('*#2 用户故事2*'); // 测试产品字段为空的情况