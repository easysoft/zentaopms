#!/usr/bin/env php
<?php

/**

title=测试 productZen::getStoriesByStoryType();
timeout=0
cid=0

- 步骤1：正常情况获取所有类型需求 @6
- 步骤2：指定获取用户需求 @2
- 步骤3：指定获取研发需求 @3
- 步骤4：使用不存在的产品ID @0
- 步骤5：测试按优先级排序功能 @6

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-20');
$table->product->range('1-3');
$table->type->range('story{5},requirement{5},epic{5}');
$table->title->range('需求1,需求2,需求3,需求4,需求5,用户需求1,用户需求2,用户需求3,用户需求4,用户需求5,史诗1,史诗2,史诗3,史诗4,史诗5');
$table->pri->range('1-4');
$table->status->range('active{10},draft{5},closed{5}');
$table->deleted->range('0{18},1{2}');
$table->parent->range('0{18},1{2}');
$table->isParent->range('0{18},1{2}');
$table->version->range('1');
$table->vision->range('rnd');
$table->gen(20);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 强制要求：必须包含至少5个测试步骤
r(count($productTest->getStoriesByStoryTypeTest(1, '', 'all', 'id_desc'))) && p() && e('6'); // 步骤1：正常情况获取所有类型需求
r(count($productTest->getStoriesByStoryTypeTest(1, '', 'requirement', 'id_desc'))) && p() && e('2'); // 步骤2：指定获取用户需求
r(count($productTest->getStoriesByStoryTypeTest(1, '', 'story', 'id_desc'))) && p() && e('3'); // 步骤3：指定获取研发需求
r(count($productTest->getStoriesByStoryTypeTest(999, '', 'all', 'id_desc'))) && p() && e('0'); // 步骤4：使用不存在的产品ID
r(count($productTest->getStoriesByStoryTypeTest(1, '', 'all', 'pri_desc'))) && p() && e('6'); // 步骤5：测试按优先级排序功能