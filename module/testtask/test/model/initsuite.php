#!/usr/bin/env php
<?php

/**

title=测试 testtaskModel::initSuite();
timeout=0
cid=19206

- 步骤1：正常情况属性product @1
- 步骤2：名称检查属性name @Unit Test Suite
- 步骤3：类型检查属性type @unit
- 步骤4：添加人检查属性addedBy @admin
- 步骤5：添加时间检查属性addedDate @2023-01-01 10:00:00

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('product');
$table->id->range('1-5');
$table->name->range('Product1,Product2,Product3,Product4,Product5');
$table->status->range('normal{5}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$testtaskTest = new testtaskModelTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($testtaskTest->initSuiteTest(1, 'Unit Test Suite', '2023-01-01 10:00:00')) && p('product') && e('1'); // 步骤1：正常情况
r($testtaskTest->initSuiteTest(1, 'Unit Test Suite', '2023-01-01 10:00:00')) && p('name') && e('Unit Test Suite'); // 步骤2：名称检查
r($testtaskTest->initSuiteTest(1, 'Unit Test Suite', '2023-01-01 10:00:00')) && p('type') && e('unit'); // 步骤3：类型检查
r($testtaskTest->initSuiteTest(1, 'Unit Test Suite', '2023-01-01 10:00:00')) && p('addedBy') && e('admin'); // 步骤4：添加人检查
r($testtaskTest->initSuiteTest(1, 'Unit Test Suite', '2023-01-01 10:00:00')) && p('addedDate') && e('2023-01-01 10:00:00'); // 步骤5：添加时间检查