#!/usr/bin/env php
<?php

/**

title=测试 testtaskZen::setMenu();
timeout=0
cid=19242

- 步骤1：qa tab默认情况，返回productID @1
- 步骤2：project tab情况，返回projectID @1
- 步骤3：execution tab情况，返回executionID @1
- 步骤4：qa tab多分支产品情况 @1
- 步骤5：其他tab情况测试 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testtaskzen.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('产品1,产品2,产品3,产品4,产品5');
$table->type->range('normal{3},branch{2}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->setMenuTest(1, 0, 0, 0, 'qa')) && p() && e('1'); // 步骤1：qa tab默认情况，返回productID
r($testtaskTest->setMenuTest(1, 'all', 1, 0, 'project')) && p() && e('1'); // 步骤2：project tab情况，返回projectID
r($testtaskTest->setMenuTest(1, 0, 0, 1, 'execution')) && p() && e('1'); // 步骤3：execution tab情况，返回executionID
r($testtaskTest->setMenuTest(2, 'main', 0, 0, 'qa')) && p() && e('1'); // 步骤4：qa tab多分支产品情况
r($testtaskTest->setMenuTest(1, '', 0, 0, 'other')) && p() && e('1'); // 步骤5：其他tab情况测试