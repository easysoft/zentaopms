#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getSqlTypeAndFields();
timeout=0
cid=1

- 测试简单SELECT语句，检查返回数组结构是否正确 @array
- 测试返回数组包含两个元素属性:count @2
- 测试第一个元素是否为对象 @object
- 测试第二个元素是否为数组属性1 @array
- 测试方法能正确处理SQL和driver参数 @array

*/

$bi = new biTest();

r($bi->getSqlTypeAndFieldsTest('SELECT 1 as test', 'mysql')) && p() && e('array');                          // 测试简单SELECT语句，检查返回数组结构是否正确
r($bi->getSqlTypeAndFieldsTest('SELECT 1 as test', 'mysql')) && p(':count') && e('2');                      // 测试返回数组包含两个元素
r($bi->getSqlTypeAndFieldsTest('SELECT 1 as test', 'mysql')) && p('0') && e('object');                      // 测试第一个元素是否为对象
r($bi->getSqlTypeAndFieldsTest('SELECT 1 as test', 'mysql')) && p('1') && e('array');                       // 测试第二个元素是否为数组
r($bi->getSqlTypeAndFieldsTest('SELECT 1 as id, "test" as name', 'mysql')) && p() && e('array');            // 测试方法能正确处理SQL和driver参数