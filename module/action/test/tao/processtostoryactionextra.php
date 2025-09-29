#!/usr/bin/env php
<?php

/**

title=测试 actionTao::processToStoryActionExtra();
timeout=0
cid=0

- 执行actionTest模块的processToStoryActionExtraTest方法，参数是$action1 属性extra @#1 登录功能
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是$action2 属性extra @#2 用户管理
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是$action3 属性extra @999
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是$action4 属性extra @
- 执行actionTest模块的processToStoryActionExtraTest方法，参数是$action5 属性extra @#5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/action.unittest.class.php';

// 准备产品数据
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,影子产品4,影子产品5');
$product->shadow->range('0{3},1{2}');
$product->status->range('normal{5}');
$product->type->range('normal{5}');
$product->gen(5);

// 准备故事数据
$story = zenData('story');
$story->id->range('1-5');
$story->title->range('登录功能,用户管理,代码查看,测试故事,');
$story->product->range('1-5');
$story->status->range('active{5}');
$story->type->range('story{5}');
$story->version->range('1{5}');
$story->gen(5);

// 准备项目故事关联数据
$projectStory = zenData('projectstory');
$projectStory->project->range('1-3');
$projectStory->story->range('1-3');
$projectStory->product->range('1-3');
$projectStory->version->range('1{3}');
$projectStory->gen(3);

su('admin');

$actionTest = new actionTest();

// 步骤1：测试普通产品（shadow=0）的故事链接处理
$action1 = new stdClass();
$action1->product = '1';
$action1->extra = '1';
r($actionTest->processToStoryActionExtraTest($action1)) && p('extra') && e('#1 登录功能');

// 步骤2：测试影子产品（shadow=1）的故事链接处理
$action2 = new stdClass();
$action2->product = '4';
$action2->extra = '2';
r($actionTest->processToStoryActionExtraTest($action2)) && p('extra') && e('#2 用户管理');

// 步骤3：测试不存在故事ID的处理
$action3 = new stdClass();
$action3->product = '1';
$action3->extra = '999';
r($actionTest->processToStoryActionExtraTest($action3)) && p('extra') && e('999');

// 步骤4：测试空extra值的处理
$action4 = new stdClass();
$action4->product = '1';
$action4->extra = '';
r($actionTest->processToStoryActionExtraTest($action4)) && p('extra') && e('');

// 步骤5：测试故事标题为空的处理
$action5 = new stdClass();
$action5->product = '1';
$action5->extra = '5';
r($actionTest->processToStoryActionExtraTest($action5)) && p('extra') && e('#5 ');