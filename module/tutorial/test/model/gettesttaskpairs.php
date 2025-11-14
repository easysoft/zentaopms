#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getTesttaskPairs();
timeout=0
cid=19491

- 步骤1：正常调用获取测试单键值对属性1 @Test testtask
- 步骤2：验证返回数组长度 @1
- 步骤3：验证键名为1 @1
- 步骤4：验证值内容 @Test testtask
- 步骤5：验证返回值是数组类型 @array

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorialTest = new tutorialTest();

r($tutorialTest->getTesttaskPairsTest()) && p('1') && e('Test testtask'); // 步骤1：正常调用获取测试单键值对
r(count($tutorialTest->getTesttaskPairsTest())) && p() && e(1); // 步骤2：验证返回数组长度
r(array_keys($tutorialTest->getTesttaskPairsTest())) && p('0') && e(1); // 步骤3：验证键名为1
r(array_values($tutorialTest->getTesttaskPairsTest())) && p('0') && e('Test testtask'); // 步骤4：验证值内容
r(is_array($tutorialTest->getTesttaskPairsTest()) ? 'array' : 'not_array') && p() && e('array'); // 步骤5：验证返回值是数组类型