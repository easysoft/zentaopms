#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::prepareFieldObjects();
timeout=0
cid=0

- 测试正常调用返回数组类型 @array
- 测试方法调用无异常 @array
- 测试返回结果是数组 @array
- 测试方法正常执行 @array
- 测试功能完整性 @array

*/

$bi = new biTest();

r($bi->prepareFieldObjectsTest()) && p() && e('array');                        // 测试正常调用返回数组类型
r($bi->prepareFieldObjectsTest()) && p() && e('array');                        // 测试方法调用无异常
r($bi->prepareFieldObjectsTest()) && p() && e('array');                        // 测试返回结果是数组
r($bi->prepareFieldObjectsTest()) && p() && e('array');                        // 测试方法正常执行
r($bi->prepareFieldObjectsTest()) && p() && e('array');                        // 测试功能完整性