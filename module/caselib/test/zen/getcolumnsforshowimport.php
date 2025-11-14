#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::getColumnsForShowImport();
timeout=0
cid=15544

- 步骤1：正常完整映射 @5
- 步骤2：包含空值情况 @1
- 步骤3：不匹配字段 @1
- 步骤4：空表头数据 @1
- 步骤5：混合有效无效字段 @3

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/caselib.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibTest();

// 4. 准备测试数据
$validFields = array(
    '标题' => 'title',
    '所属模块' => 'module',
    '前置条件' => 'precondition',
    '步骤' => 'stepDesc',
    '预期' => 'stepExpect',
    '类型' => 'type',
    '优先级' => 'pri'
);

// 5. 强制要求：必须包含至少5个测试步骤
r($caselibTest->getColumnsForShowImportTest(array('标题', '所属模块', '前置条件', '步骤', '预期'), $validFields, 'count')) && p() && e('5'); // 步骤1：正常完整映射
r($caselibTest->getColumnsForShowImportTest(array('标题', '', '所属模块', '', '步骤'), $validFields, 'count')) && p() && e('1'); // 步骤2：包含空值情况
r($caselibTest->getColumnsForShowImportTest(array('无效字段1', '无效字段2', '无效字段3'), $validFields, 'is_empty')) && p() && e('1'); // 步骤3：不匹配字段
r($caselibTest->getColumnsForShowImportTest(array(), $validFields, 'is_empty')) && p() && e('1'); // 步骤4：空表头数据
r($caselibTest->getColumnsForShowImportTest(array('标题', '无效字段', '所属模块', '无效字段2', '步骤'), $validFields, 'count')) && p() && e('3'); // 步骤5：混合有效无效字段