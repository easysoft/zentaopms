#!/usr/bin/env php
<?php

/**

title=测试 testcaseZen::getExportFields();
timeout=0
cid=19090

- 步骤1：normal产品类型，不包含branch字段 @4
- 步骤2：branch产品类型，包含branch字段 @5
- 步骤3：POST数据优先级 @3
- 步骤4：空字段列表使用默认配置 @4
- 步骤5：包含空格字段处理 @2
- 步骤6：platform产品类型测试 @5

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/testcasezen.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$testcaseTest = new testcaseZenTest();

// 4. 必须包含至少5个测试步骤
r($testcaseTest->getExportFieldsTest('normal')) && p() && e('4'); // 步骤1：normal产品类型，不包含branch字段
r($testcaseTest->getExportFieldsTest('branch')) && p() && e('5'); // 步骤2：branch产品类型，包含branch字段
r($testcaseTest->getExportFieldsTest('normal', array('id', 'title', 'status'))) && p() && e('3'); // 步骤3：POST数据优先级
r($testcaseTest->getExportFieldsTest('normal', array())) && p() && e('4'); // 步骤4：空字段列表使用默认配置
r($testcaseTest->getExportFieldsTest('normal', array(' id ', ' title '))) && p() && e('2'); // 步骤5：包含空格字段处理
r($testcaseTest->getExportFieldsTest('platform')) && p() && e('5'); // 步骤6：platform产品类型测试