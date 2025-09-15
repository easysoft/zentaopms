#!/usr/bin/env php
<?php

/**

title=测试 bugZen::updateTodoAfterCreate();
timeout=0
cid=0

- 测试步骤1：正常情况下更新todo状态 @1
- 测试步骤2：更新不存在的todo @1
- 测试步骤3：使用无效bugID参数 @1
- 测试步骤4：使用0作为todoID参数 @1
- 测试步骤5：测试feedback类型todo的处理 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

zendata('todo')->gen(10);
zendata('action')->gen(20);

su('admin');

$bugTest = new bugTest();

r($bugTest->updateTodoAfterCreateTest(1, 1)) && p() && e('1');             // 测试步骤1：正常情况下更新todo状态
r($bugTest->updateTodoAfterCreateTest(1, 999)) && p() && e('1');           // 测试步骤2：更新不存在的todo
r($bugTest->updateTodoAfterCreateTest(0, 2)) && p() && e('1');             // 测试步骤3：使用无效bugID参数
r($bugTest->updateTodoAfterCreateTest(2, 0)) && p() && e('1');             // 测试步骤4：使用0作为todoID参数
r($bugTest->updateTodoAfterCreateTest(3, 9)) && p() && e('1');             // 测试步骤5：测试feedback类型todo的处理