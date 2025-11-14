#!/usr/bin/env php
<?php
declare(strict_types=1);

/**

title=测试 myModel::getFlowPairs();
timeout=0
cid=17284

- 测试步骤1：正常获取自定义工作流程键值对 @flow1:流程1,flow2:流程2,flow3:流程3

- 测试步骤2：内置工作流不影响非内置工作流的返回 @flow1:流程1,flow2:流程2,flow3:流程3

- 测试步骤3：数据持久性验证 @flow1:流程1,flow2:流程2,flow3:流程3

- 测试步骤4：方法返回结果的稳定性 @flow1:流程1,flow2:流程2,flow3:流程3

- 测试步骤5：不同用户权限下的数据访问 @flow1:流程1,flow2:流程2,flow3:流程3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/my.unittest.class.php';

zenData('user')->gen(5);

$my = new myTest();

// 1. 测试步骤1：正常获取自定义工作流程键值对
$table = zenData('workflow');
$table->id->range('1-3');
$table->module->range('flow1,flow2,flow3');
$table->name->range('流程1,流程2,流程3');
$table->buildin->range('0{3}');
$table->gen(3);

su('admin');
r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2,flow3:流程3'); // 测试步骤1：正常获取自定义工作流程键值对

// 2. 测试步骤2：测试只有内置工作流情况
$table = zenData('workflow');
$table->id->range('4-6');
$table->module->range('builtin1,builtin2,builtin3');
$table->name->range('内置流程1,内置流程2,内置流程3');
$table->buildin->range('1{3}');
$table->gen(3);

r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2,flow3:流程3'); // 测试步骤2：内置工作流不影响非内置工作流的返回

// 3. 测试步骤3：测试数据持久性
r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2,flow3:流程3'); // 测试步骤3：数据持久性验证

// 4. 测试步骤4：测试方法的稳定性
r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2,flow3:流程3'); // 测试步骤4：方法返回结果的稳定性

// 5. 测试步骤5：测试用户权限验证（不同用户权限下的数据访问）
su('user');
r($my->getFlowPairsTest()) && p() && e('flow1:流程1,flow2:流程2,flow3:流程3'); // 测试步骤5：不同用户权限下的数据访问