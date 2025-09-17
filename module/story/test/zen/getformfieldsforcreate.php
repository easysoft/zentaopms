#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormFieldsForCreate();
timeout=0
cid=0

- 步骤1：正常产品创建表单字段配置属性productDefault @1
- 步骤2：验证标题字段名称设置属性titleName @title
- 步骤3：不同产品ID的字段配置属性productDefault @2
- 步骤4：第三个产品的字段配置属性productDefault @3
- 步骤5：需求类型的表单字段配置属性productDefault @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->PO->range('admin{3},user1{2}');
$product->status->range('normal{5}');
$product->type->range('normal{5}');
$product->deleted->range('0{5}');
$product->gen(5);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->deleted->range('0{5}');
$user->gen(5);

$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1{5},2{3},3{2}');
$story->title->range('需求1,需求2,需求3,需求4,需求5,需求6,需求7,需求8,需求9,需求10');
$story->type->range('story{10}');
$story->status->range('active{10}');
$story->parent->range('0{10}');
$story->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备初始story对象
$initStory = new stdclass();
$initStory->title = '';
$initStory->spec = '';
$initStory->verify = '';
$initStory->pri = 3;
$initStory->estimate = 0;
$initStory->plan = 0;

r($storyTest->getFormFieldsForCreateTest(1, '0', 0, $initStory, 'story')) && p('productDefault') && e('1'); // 步骤1：正常产品创建表单字段配置
r($storyTest->getFormFieldsForCreateTest(1, '0', 0, $initStory, 'story')) && p('titleName') && e('title'); // 步骤2：验证标题字段名称设置
r($storyTest->getFormFieldsForCreateTest(2, '0', 0, $initStory, 'story')) && p('productDefault') && e('2'); // 步骤3：不同产品ID的字段配置
r($storyTest->getFormFieldsForCreateTest(3, '0', 0, $initStory, 'story')) && p('productDefault') && e('3'); // 步骤4：第三个产品的字段配置
r($storyTest->getFormFieldsForCreateTest(1, '0', 0, $initStory, 'requirement')) && p('productDefault') && e('1'); // 步骤5：需求类型的表单字段配置