#!/usr/bin/env php
<?php

/**

title=测试 tutorialModel::getBug();
timeout=0
cid=19405

- 步骤1：验证Bug对象ID属性id @1
- 步骤2：验证Bug对象标题属性title @Test bug-active
- 步骤3：验证Bug对象状态属性status @active
- 步骤4：验证Bug对象类型属性type @codeerror
- 步骤5：验证Bug对象项目ID属性project @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$tutorialTest = new tutorialModelTest();

r($tutorialTest->getBugTest()) && p('id') && e('1'); // 步骤1：验证Bug对象ID
r($tutorialTest->getBugTest()) && p('title') && e('Test bug-active'); // 步骤2：验证Bug对象标题
r($tutorialTest->getBugTest()) && p('status') && e('active'); // 步骤3：验证Bug对象状态
r($tutorialTest->getBugTest()) && p('type') && e('codeerror'); // 步骤4：验证Bug对象类型
r($tutorialTest->getBugTest()) && p('project') && e('2'); // 步骤5：验证Bug对象项目ID