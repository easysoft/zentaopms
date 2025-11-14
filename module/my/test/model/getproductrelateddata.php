#!/usr/bin/env php
<?php

/**

title=测试 myModel::getProductRelatedData();
timeout=0
cid=17286

- 步骤1：正常产品ID数组输入 @4
- 步骤2：空数组输入情况 @4
- 步骤3：不存在的产品ID输入 @4
- 步骤4：单个产品ID输入 @4
- 步骤5：多个有效产品ID输入 @4

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

// 简单测试不生成复杂数据，专注于测试方法的基本功能

su('admin');

$myTest = new myTest();

r($myTest->getProductRelatedDataTest([1, 2])) && p() && e('4'); // 步骤1：正常产品ID数组输入
r($myTest->getProductRelatedDataTest([])) && p() && e('4'); // 步骤2：空数组输入情况
r($myTest->getProductRelatedDataTest([999])) && p() && e('4'); // 步骤3：不存在的产品ID输入
r($myTest->getProductRelatedDataTest([1])) && p() && e('4'); // 步骤4：单个产品ID输入
r($myTest->getProductRelatedDataTest([1, 2, 3])) && p() && e('4'); // 步骤5：多个有效产品ID输入