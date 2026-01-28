#!/usr/bin/env php
<?php

/**

title=测试 storyModel::getByOpenedBy();
timeout=0
cid=18505

- 步骤1：正常情况 - 查询admin创建的故事数量 @4
- 步骤2：边界值 - 查询不存在用户 @0
- 步骤3：异常输入 - 指定单个产品ID @2
- 步骤4：权限验证 - 指定分支过滤(branch 1没有admin创建的故事) @0
- 步骤5：业务规则 - 查询需求类型 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1,2,3');
$table->title->range('story1,story2,story3,story4,story5,story6,story7,story8,story9,story10');
$table->openedBy->range('admin{4},user1{3},user2{2},test{1}');
$table->type->range('story{8},requirement{2}');
$table->status->range('active{8},draft{1},closed{1}');
$table->branch->range('0{6},1{2},2{2}');
$table->module->range('1{4},2{3},3{3}');
$table->vision->range('rnd');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyModelTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($storyTest->getByOpenedByTest(array(1,2,3), 'all', '', 'admin', 'story')) && p() && e('4'); // 步骤1：正常情况 - 查询admin创建的故事数量
r($storyTest->getByOpenedByTest(array(1,2,3), 'all', '', 'nonexist', 'story')) && p() && e('0'); // 步骤2：边界值 - 查询不存在用户
r($storyTest->getByOpenedByTest(1, 'all', '', 'admin', 'story')) && p() && e('2'); // 步骤3：异常输入 - 指定单个产品ID
r($storyTest->getByOpenedByTest(array(1,2,3), 1, '', 'admin', 'story')) && p() && e('0'); // 步骤4：权限验证 - 指定分支过滤(branch 1没有admin创建的故事)
r($storyTest->getByOpenedByTest(array(1,2,3), 'all', '', 'user1', 'requirement')) && p() && e('0'); // 步骤5：业务规则 - 查询需求类型