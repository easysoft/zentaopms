#!/usr/bin/env php
<?php

/**

title=测试 myTao::getProductRelatedAssignedByMe();
timeout=0
cid=17311

- 步骤1：测试story模块正常情况 @2
- 步骤2：测试bug模块正常情况 @3
- 步骤3：测试空对象ID列表 @0
- 步骤4：测试不存在的对象ID @0
- 步骤5：测试按优先级排序 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. zendata数据准备
$productTable = zenData('product');
$productTable->loadYaml('product_getproductrelatedassignedbyme', false, 2);
$productTable->gen(10);

$storyTable = zenData('story');
$storyTable->loadYaml('story_getproductrelatedassignedbyme', false, 2);
$storyTable->gen(20);

$bugTable = zenData('bug');
$bugTable->loadYaml('bug_getproductrelatedassignedbyme', false, 2);
$bugTable->gen(15);

// 3. 用户登录
su('admin');

// 4. 创建测试实例
$myTest = new myTaoTest();

// 5. 测试步骤（强制要求：必须包含至少5个测试步骤）
r($myTest->getProductRelatedAssignedByMeTest(array(1, 2, 3, 4, 5), 'story', 'story', 'id_desc')) && p() && e('2'); // 步骤1：测试story模块正常情况
r($myTest->getProductRelatedAssignedByMeTest(array(1, 2, 3), 'bug', 'bug', 'id_desc')) && p() && e('3'); // 步骤2：测试bug模块正常情况
r($myTest->getProductRelatedAssignedByMeTest(array(), 'story', 'story', 'id_desc')) && p() && e('0'); // 步骤3：测试空对象ID列表
r($myTest->getProductRelatedAssignedByMeTest(array(999, 1000), 'story', 'story', 'id_desc')) && p() && e('0'); // 步骤4：测试不存在的对象ID
r($myTest->getProductRelatedAssignedByMeTest(array(1, 2), 'story', 'story', 'pri_desc')) && p() && e('1'); // 步骤5：测试按优先级排序