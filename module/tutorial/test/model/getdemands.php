#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getDemands();
timeout=0
cid=19420

- 步骤1：验证返回2个需求 @2
- 步骤2：验证活跃需求状态第1条的status属性 @active
- 步骤3：验证活跃需求标题第1条的title属性 @Test Demand
- 步骤4：验证审核需求状态第2条的status属性 @reviewing
- 步骤5：验证审核需求标题第2条的title属性 @Test Reviewing Demand

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tutorial.unittest.class.php';

su('admin');

$tutorialTest = new tutorialTest();

r(count($tutorialTest->getDemandsTest())) && p() && e('2'); // 步骤1：验证返回2个需求
r($tutorialTest->getDemandsTest()) && p('1:status') && e('active'); // 步骤2：验证活跃需求状态
r($tutorialTest->getDemandsTest()) && p('1:title') && e('Test Demand'); // 步骤3：验证活跃需求标题
r($tutorialTest->getDemandsTest()) && p('2:status') && e('reviewing'); // 步骤4：验证审核需求状态
r($tutorialTest->getDemandsTest()) && p('2:title') && e('Test Reviewing Demand'); // 步骤5：验证审核需求标题