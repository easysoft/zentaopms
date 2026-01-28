#!/usr/bin/env php
<?php

/**

title=测试 caselibZen::getExportCasesFields();
timeout=0
cid=15546

- 步骤1：测试默认配置字段数量 @19
- 步骤2：测试默认配置包含id字段 @1
- 步骤3：测试自定义字段列表 @3
- 步骤4：测试自定义字段包含title @1
- 步骤5：测试自定义字段不包含id @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

// 2. zendata数据准备（此方法不依赖数据库数据，无需准备）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$caselibTest = new caselibZenTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($caselibTest->getExportCasesFieldsTest(array(), 'count')) && p() && e('19'); // 步骤1：测试默认配置字段数量
r($caselibTest->getExportCasesFieldsTest(array(), 'has_id')) && p() && e('1'); // 步骤2：测试默认配置包含id字段
r($caselibTest->getExportCasesFieldsTest(array('exportFields' => array('title', 'module', 'type')), 'count')) && p() && e('3'); // 步骤3：测试自定义字段列表
r($caselibTest->getExportCasesFieldsTest(array('exportFields' => array('title', 'module', 'type')), 'has_title')) && p() && e('1'); // 步骤4：测试自定义字段包含title
r($caselibTest->getExportCasesFieldsTest(array('exportFields' => array('title', 'module', 'type')), 'has_id')) && p() && e('0'); // 步骤5：测试自定义字段不包含id