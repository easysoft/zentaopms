#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getTesttasks();
timeout=0
cid=19492

- 步骤1：获取测试单列表数量验证 @1
- 步骤2：验证返回数组的键值 @1
- 步骤3：验证测试单ID第1条的id属性 @1
- 步骤4：验证测试单名称第1条的name属性 @Test testtask
- 步骤5：验证测试单状态第1条的status属性 @wait

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tutorialTest = new tutorialModelTest();

r(count($tutorialTest->getTesttasksTest())) && p() && e(1); // 步骤1：获取测试单列表数量验证
r(array_keys($tutorialTest->getTesttasksTest())) && p('0') && e(1); // 步骤2：验证返回数组的键值
r($tutorialTest->getTesttasksTest()) && p('1:id') && e(1); // 步骤3：验证测试单ID
r($tutorialTest->getTesttasksTest()) && p('1:name') && e('Test testtask'); // 步骤4：验证测试单名称
r($tutorialTest->getTesttasksTest()) && p('1:status') && e('wait'); // 步骤5：验证测试单状态