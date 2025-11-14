#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getPlans();
timeout=0
cid=19448

- 步骤1：正常调用返回数组长度为1 @1
- 步骤2：验证数组键名为1 @1
- 步骤3：验证计划id第1条的id属性 @1
- 步骤4：验证计划标题第1条的title属性 @Test plan
- 步骤5：验证计划状态第1条的status属性 @wait

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorialTest = new tutorialTest();

r(count($tutorialTest->getPlansTest())) && p() && e(1); // 步骤1：正常调用返回数组长度为1
r(array_keys($tutorialTest->getPlansTest())) && p('0') && e('1'); // 步骤2：验证数组键名为1
r($tutorialTest->getPlansTest()) && p('1:id') && e('1'); // 步骤3：验证计划id
r($tutorialTest->getPlansTest()) && p('1:title') && e('Test plan'); // 步骤4：验证计划标题
r($tutorialTest->getPlansTest()) && p('1:status') && e('wait'); // 步骤5：验证计划状态