#!/usr/bin/env php
<?php

/**

title=测试 storyZen::getFormFieldsForChange();
timeout=0
cid=0

- 步骤1：测试需求1的变更表单字段包含title第title条的name属性 @title
- 步骤2：测试需求2的变更表单字段包含spec第spec条的name属性 @spec
- 步骤3：测试需求3的变更表单字段包含verify第verify条的name属性 @verify
- 步骤4：测试需求4的变更表单字段包含reviewer第reviewer条的name属性 @reviewer
- 步骤5：测试需求5的变更表单字段包含comment第comment条的name属性 @comment

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

// 2. zendata数据准备
$story = zenData('story');
$story->id->range('1-10');
$story->product->range('1{10}');
$story->type->range('story{10}');
$story->status->range('active{5},reviewing{5}');
$story->stage->range('wait{10}');
$story->version->range('1{10}');
$story->color->range('#3da7f5{10}');
$story->assignedTo->range('admin{10}');
$story->gen(10);

$storyspec = zenData('storyspec');
$storyspec->story->range('1-10');
$storyspec->version->range('1{10}');
$storyspec->title->range('需求标题1,需求标题2,需求标题3,需求标题4,需求标题5{5}');
$storyspec->spec->range('需求描述1,需求描述2,需求描述3,需求描述4,需求描述5{5}');
$storyspec->verify->range('验收标准1,验收标准2,验收标准3,验收标准4,验收标准5{5}');
$storyspec->gen(10);

$storyreview = zenData('storyreview');
$storyreview->story->range('1-10');
$storyreview->version->range('1{10}');
$storyreview->reviewer->range('user1,user2,user3,admin{7}');
$storyreview->result->range('pass{5},pending{5}');
$storyreview->gen(10);

$product = zenData('product');
$product->id->range('1-5');
$product->name->range('产品1,产品2,产品3,产品4,产品5');
$product->type->range('normal{5}');
$product->status->range('normal{5}');
$product->PO->range('admin{5}');
$product->gen(5);

$user = zenData('user');
$user->id->range('1-10');
$user->account->range('admin,user1,user2,user3,user4,user5,user6,user7,user8,user9');
$user->realname->range('管理员,用户1,用户2,用户3,用户4,用户5,用户6,用户7,用户8,用户9');
$user->deleted->range('0{10}');
$user->gen(10);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$storyTest = new storyZenTest();

// 5. 测试步骤（必须至少5个）
r($storyTest->getFormFieldsForChangeTest(1)) && p('title:name') && e('title'); // 步骤1：测试需求1的变更表单字段包含title
r($storyTest->getFormFieldsForChangeTest(2)) && p('spec:name') && e('spec'); // 步骤2：测试需求2的变更表单字段包含spec
r($storyTest->getFormFieldsForChangeTest(3)) && p('verify:name') && e('verify'); // 步骤3：测试需求3的变更表单字段包含verify
r($storyTest->getFormFieldsForChangeTest(4)) && p('reviewer:name') && e('reviewer'); // 步骤4：测试需求4的变更表单字段包含reviewer
r($storyTest->getFormFieldsForChangeTest(5)) && p('comment:name') && e('comment'); // 步骤5：测试需求5的变更表单字段包含comment