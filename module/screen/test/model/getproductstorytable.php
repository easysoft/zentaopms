#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getProductStoryTable();
timeout=0
cid=18251

- 步骤1：正常情况 @0
- 步骤2：空产品列表 @0
- 步骤3：不存在的年月 @0
- 步骤4：验证返回结果 @0
- 步骤5：边界值输入 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';

// 2. zendata数据准备
$productTable = zenData('product');
$productTable->loadYaml('product_getproductstorytable', false, 2)->gen(5);

$storyTable = zenData('story');
$storyTable->loadYaml('story_getproductstorytable', false, 2)->gen(20);

$actionTable = zenData('action');
$actionTable->loadYaml('action_getproductstorytable', false, 2)->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$screenTest = new screenTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($screenTest->getProductStoryTableTest('2023', '12', array(1 => '产品1', 2 => '产品2', 3 => '产品3'))) && p() && e('0'); // 步骤1：正常情况
r($screenTest->getProductStoryTableTest('2023', '12', array())) && p() && e('0'); // 步骤2：空产品列表
r($screenTest->getProductStoryTableTest('2099', '01', array(1 => '产品1', 2 => '产品2'))) && p() && e('0'); // 步骤3：不存在的年月
r($screenTest->getProductStoryTableTest('2023', '12', array(1 => '产品1'))) && p() && e('0'); // 步骤4：验证返回结果
r($screenTest->getProductStoryTableTest('0', '0', array(1 => '产品1'))) && p() && e('0'); // 步骤5：边界值输入