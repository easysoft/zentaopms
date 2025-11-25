#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getSystemPairs();
timeout=0
cid=19483

- 测试步骤1：验证键1对应的值属性1 @Test App
- 测试步骤2：再次验证键1的值属性1 @Test App
- 测试步骤3：验证方法稳定性属性1 @Test App
- 测试步骤4：验证返回内容一致性属性1 @Test App
- 测试步骤5：验证键值对数据正确性属性1 @Test App

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorialTest = new tutorialTest();

r($tutorialTest->getSystemPairsTest()) && p('1') && e('Test App'); // 测试步骤1：验证键1对应的值
r($tutorialTest->getSystemPairsTest()) && p('1') && e('Test App'); // 测试步骤2：再次验证键1的值
r($tutorialTest->getSystemPairsTest()) && p('1') && e('Test App'); // 测试步骤3：验证方法稳定性
r($tutorialTest->getSystemPairsTest()) && p('1') && e('Test App'); // 测试步骤4：验证返回内容一致性
r($tutorialTest->getSystemPairsTest()) && p('1') && e('Test App'); // 测试步骤5：验证键值对数据正确性