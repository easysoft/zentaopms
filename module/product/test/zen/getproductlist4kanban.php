#!/usr/bin/env php
<?php

/**

title=测试 productZen::getProductList4Kanban();
timeout=0
cid=0

- 步骤1：空参数测试 @0
- 步骤2：正常产品列表测试 @1
- 步骤3：产品状态过滤测试 @0
- 步骤4：包含计划数据测试 @1
- 步骤5：项目关联测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/product.unittest.class.php';

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$productTest = new productTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($productTest->getProductList4KanbanTest(array(), array(), array(), array(), array())) && p() && e(0); // 步骤1：空参数测试
r($productTest->getProductList4KanbanTest(array(1 => (object)array('id' => 1, 'name' => '产品A', 'status' => 'normal', 'program' => 1)), array(), array(), array(), array())) && p() && e(1); // 步骤2：正常产品列表测试
r($productTest->getProductList4KanbanTest(array(1 => (object)array('id' => 1, 'name' => '产品A', 'status' => 'closed', 'program' => 1)), array(), array(), array(), array())) && p() && e(0); // 步骤3：产品状态过滤测试
r($productTest->getProductList4KanbanTest(array(1 => (object)array('id' => 1, 'name' => '产品A', 'status' => 'normal', 'program' => 1)), array(1 => array((object)array('id' => 1, 'title' => '计划A'))), array(), array(), array())) && p() && e(1); // 步骤4：包含计划数据测试
r($productTest->getProductList4KanbanTest(array(1 => (object)array('id' => 1, 'name' => '产品A', 'status' => 'normal', 'program' => 1)), array(), array(1 => (object)array('id' => 1, 'name' => '项目A')), array(), array(1 => array(1 => (object)array('id' => 1, 'name' => '项目A'))))) && p() && e(1); // 步骤5：项目关联测试