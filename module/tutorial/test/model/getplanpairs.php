#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getPlanPairs();
timeout=0
cid=19447

- 测试1：验证键1对应的值属性1 @Test plan
- 测试2：再次验证键1的值属性1 @Test plan
- 测试3：验证方法稳定性属性1 @Test plan
- 测试4：验证返回内容一致性属性1 @Test plan
- 测试5：验证键值对数据正确性属性1 @Test plan

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tutorialTest = new tutorialModelTest();

r($tutorialTest->getPlanPairsTest()) && p('1') && e('Test plan'); // 测试1：验证键1对应的值
r($tutorialTest->getPlanPairsTest()) && p('1') && e('Test plan'); // 测试2：再次验证键1的值
r($tutorialTest->getPlanPairsTest()) && p('1') && e('Test plan'); // 测试3：验证方法稳定性
r($tutorialTest->getPlanPairsTest()) && p('1') && e('Test plan'); // 测试4：验证返回内容一致性
r($tutorialTest->getPlanPairsTest()) && p('1') && e('Test plan'); // 测试5：验证键值对数据正确性