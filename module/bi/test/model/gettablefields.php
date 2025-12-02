#!/usr/bin/env php
<?php

/**

title=测试 biModel::getTableFields();
timeout=0
cid=15185

- 测试正常调用返回结果是数组类型 @array
- 测试返回结果不为空 @not_empty
- 测试返回结果包含表名键 @has_tables
- 测试每个表都包含字段信息 @has_fields
- 测试字段信息结构完整性 @valid_structure

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

su('admin');

$bi = new biTest();

r($bi->getTableFieldsTest()) && p() && e('array');          // 测试正常调用返回结果是数组类型
r($bi->getTableFieldsTestNotEmpty()) && p() && e('not_empty');          // 测试返回结果不为空
r($bi->getTableFieldsTestHasTables()) && p() && e('has_tables');          // 测试返回结果包含表名键
r($bi->getTableFieldsTestHasFields()) && p() && e('has_fields');          // 测试每个表都包含字段信息
r($bi->getTableFieldsTestValidStructure()) && p() && e('valid_structure');          // 测试字段信息结构完整性