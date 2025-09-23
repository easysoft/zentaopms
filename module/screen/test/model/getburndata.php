#!/usr/bin/env php
<?php

/**

title=测试 screenModel::getBurnData();
timeout=0
cid=0

- 步骤1：正常情况测试执行数据数量 @16
- 步骤2：测试执行名称拼接格式第130条的name属性 @项目11--项目30
- 步骤3：验证执行开始日期第130条的begin属性 @2025-07-22
- 步骤4：验证执行结束日期第130条的end属性 @2025-10-31
- 步骤5：验证执行状态为进行中第130条的status属性 @doing

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/screen.unittest.class.php';
su('admin');

// 1. 准备测试数据：项目、产品、执行
zenData('project')->gen(0);
zenData('project')->loadYaml('project')->gen(1, false, false);
zenData('project')->loadYaml('execution_burn')->gen(30, false, false);

$screenTest = new screenTest();

r(count($screenTest->getBurnDataTest())) && p() && e('16');                                      // 步骤1：正常情况测试执行数据数量
r($screenTest->getBurnDataTest()) && p('130:name') && e('项目11--项目30');                       // 步骤2：测试执行名称拼接格式
r($screenTest->getBurnDataTest()) && p('130:begin') && e('2025-07-22');                         // 步骤3：验证执行开始日期
r($screenTest->getBurnDataTest()) && p('130:end') && e('2025-10-31');                           // 步骤4：验证执行结束日期
r($screenTest->getBurnDataTest()) && p('130:status') && e('doing');                             // 步骤5：验证执行状态为进行中