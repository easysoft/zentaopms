#!/usr/bin/env php
<?php
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bi.unittest.class.php';

/**

title=测试 biModel::getTableList();
timeout=0
cid=0

- 测试默认参数(包含数据视图表，带前缀) @array
- 测试仅获取原始表(不包含数据视图表，带前缀) @array
- 测试不带前缀的完整表列表 @array
- 测试不带前缀且不包含数据视图表 @array
- 测试默认参数行为 @array

*/

$biTest = new biTest();

r($biTest->getTableListTest(true, true)) && p() && e('array');   // 测试默认参数(包含数据视图表，带前缀)
r($biTest->getTableListTest(false, true)) && p() && e('array');  // 测试仅获取原始表(不包含数据视图表，带前缀)
r($biTest->getTableListTest(true, false)) && p() && e('array');  // 测试不带前缀的完整表列表
r($biTest->getTableListTest(false, false)) && p() && e('array'); // 测试不带前缀且不包含数据视图表
r($biTest->getTableListTest()) && p() && e('array');             // 测试默认参数行为