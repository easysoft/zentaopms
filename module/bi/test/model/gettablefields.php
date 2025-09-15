#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getTableFields();
timeout=0
cid=0

- 测试正常调用返回数组类型 @array
- 测试返回结果包含表字段信息属性~ @array
- 测试返回结果中包含表名键第keys条的0属性 @~~
- 测试字段信息完整性属性count @~~
- 测试方法执行无错误 @array

*/

$bi = new biTest();

r($bi->getTableFieldsTest()) && p() && e('array');                          // 测试正常调用返回数组类型
r($bi->getTableFieldsTest()) && p('~') && e('array');                       // 测试返回结果包含表字段信息
r($bi->getTableFieldsTest()) && p('keys:0') && e('~~');                     // 测试返回结果中包含表名键
r($bi->getTableFieldsTest()) && p('count') && e('~~');                      // 测试字段信息完整性
r($bi->getTableFieldsTest()) && p() && e('array');                          // 测试方法执行无错误