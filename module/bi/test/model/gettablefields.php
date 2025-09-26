#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getTableFields();
timeout=0
cid=0

- 测试方法正常调用返回数组类型 @array
- 测试方法调用成功没有报错 @array
- 测试返回结果为数组类型 @array
- 测试方法执行结果正常 @array
- 测试方法无异常返回 @array

*/

$bi = new biTest();

r($bi->getTableFieldsTest()) && p() && e('array');                         // 测试方法正常调用返回数组类型
r($bi->getTableFieldsTest()) && p() && e('array');                         // 测试方法调用成功没有报错
r($bi->getTableFieldsTest()) && p() && e('array');                         // 测试返回结果为数组类型
r($bi->getTableFieldsTest()) && p() && e('array');                         // 测试方法执行结果正常
r($bi->getTableFieldsTest()) && p() && e('array');                         // 测试方法无异常返回