#!/usr/bin/env php
<?php

/**

title=测试 convertTao::buildOSPropertyEntryData();
timeout=0
cid=15821

- 步骤1：正常完整数据id字段属性id @1001
- 步骤2：正常完整数据entity_name字段属性entity_name @Issue
- 步骤3：正常完整数据entity_id字段属性entity_id @10001
- 步骤4：正常完整数据property_key字段属性property_key @status
- 步骤5：正常完整数据propertytype字段属性propertytype @string

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/convert.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$convertTest = new convertTest();

// 4. 🔴 强制要求：必须包含至少5个测试步骤
r($convertTest->buildOSPropertyEntryDataTest(array('id' => '1001', 'entityName' => 'Issue', 'entityId' => '10001', 'propertyKey' => 'status', 'type' => 'string'))) && p('id') && e('1001'); // 步骤1：正常完整数据id字段
r($convertTest->buildOSPropertyEntryDataTest(array('id' => '1001', 'entityName' => 'Issue', 'entityId' => '10001', 'propertyKey' => 'status', 'type' => 'string'))) && p('entity_name') && e('Issue'); // 步骤2：正常完整数据entity_name字段
r($convertTest->buildOSPropertyEntryDataTest(array('id' => '1001', 'entityName' => 'Issue', 'entityId' => '10001', 'propertyKey' => 'status', 'type' => 'string'))) && p('entity_id') && e('10001'); // 步骤3：正常完整数据entity_id字段
r($convertTest->buildOSPropertyEntryDataTest(array('id' => '1001', 'entityName' => 'Issue', 'entityId' => '10001', 'propertyKey' => 'status', 'type' => 'string'))) && p('property_key') && e('status'); // 步骤4：正常完整数据property_key字段
r($convertTest->buildOSPropertyEntryDataTest(array('id' => '1001', 'entityName' => 'Issue', 'entityId' => '10001', 'propertyKey' => 'status', 'type' => 'string'))) && p('propertytype') && e('string'); // 步骤5：正常完整数据propertytype字段