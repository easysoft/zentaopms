#!/usr/bin/env php
<?php

/**

title=测试 storyZen::setFormOptionsForBatchEdit();
timeout=0
cid=0

- 步骤1：单产品批量编辑表单选项设置 @configured
- 步骤2：多产品批量编辑表单选项设置 @configured
- 步骤3：执行批量编辑表单选项设置 @configured
- 步骤4：空需求列表处理属性error @no_stories
- 步骤5：验证用户选项设置 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zendata('product')->loadYaml('product_setformoptionsforbatchedit', false, 2)->gen(5);
zendata('story')->loadYaml('story_setformoptionsforbatchedit', false, 2)->gen(10);
zendata('module')->loadYaml('module_setformoptionsforbatchedit', false, 2)->gen(10);
zendata('productplan')->loadYaml('productplan_setformoptionsforbatchedit', false, 2)->gen(6);

$user = zenData('user');
$user->id->range('1-5');
$user->account->range('admin,user1,user2,user3,user4');
$user->realname->range('管理员,用户1,用户2,用户3,用户4');
$user->deleted->range('0{5}');
$user->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
// 准备测试用的需求对象
$story1 = new stdclass();
$story1->id = 1;
$story1->product = 1;
$story1->branch = 0;
$story1->module = 1801;
$story1->status = 'active';
$story1->plan = '1';

$story2 = new stdclass();
$story2->id = 2;
$story2->product = 2;
$story2->branch = 0;
$story2->module = 1806;
$story2->status = 'active';
$story2->plan = '2';

$story3 = new stdclass();
$story3->id = 9;
$story3->product = 3;
$story3->branch = 0;
$story3->module = 1809;
$story3->status = 'closed';
$story3->plan = '';

$singleProductStories = array($story1);
$multiProductStories = array($story1, $story2);
$executionStories = array($story1, $story2, $story3);
$emptyStories = array();

r($storyTest->setFormOptionsForBatchEditTest(1, 0, $singleProductStories)) && p() && e('configured'); // 步骤1：单产品批量编辑表单选项设置
r($storyTest->setFormOptionsForBatchEditTest(2, 0, $multiProductStories)) && p() && e('configured'); // 步骤2：多产品批量编辑表单选项设置
r($storyTest->setFormOptionsForBatchEditTest(1, 12, $executionStories)) && p() && e('configured'); // 步骤3：执行批量编辑表单选项设置
r($storyTest->setFormOptionsForBatchEditTest(0, 0, $emptyStories)) && p('error') && e('no_stories'); // 步骤4：空需求列表处理
r($storyTest->setFormOptionsForBatchEditUsersTest(1, 0, $singleProductStories)) && p() && e('5'); // 步骤5：验证用户选项设置