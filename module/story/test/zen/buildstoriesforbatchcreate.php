#!/usr/bin/env php
<?php

/**

title=测试 storyZen::buildStoriesForBatchCreate();
timeout=0
cid=0

- 执行storyTest模块的buildStoriesForBatchCreateTest方法，参数是1, 'story'  @0
- 执行$result1 @0
- 执行$result2 @0
- 执行$result3 @0
- 执行$result4 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/story.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$productTable = zenData('product');
$productTable->id->range('1-5');
$productTable->name->range('产品1,产品2,产品3,产品4,产品5');
$productTable->type->range('normal');
$productTable->status->range('normal');
$productTable->vision->range('rnd');
$productTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$storyTest = new storyTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 步骤1：空POST数据情况，返回空数组
$_POST = array();
r($storyTest->buildStoriesForBatchCreateTest(1, 'story')) && p() && e('0');

// 步骤2：基本数据，也返回空数组
$_POST = array();
$_POST['title'] = array('测试需求1');
$_POST['pri'] = array('1');
$result1 = $storyTest->buildStoriesForBatchCreateTest(1, 'story');
r($result1) && p() && e('0');

// 步骤3：检查requirement类型
$result2 = $storyTest->buildStoriesForBatchCreateTest(1, 'requirement');
r($result2) && p() && e('0');

// 步骤4：检查epic类型
$result3 = $storyTest->buildStoriesForBatchCreateTest(1, 'epic');
r($result3) && p() && e('0');

// 步骤5：检查异常productID
$result4 = $storyTest->buildStoriesForBatchCreateTest(999, 'story');
r($result4) && p() && e('0');