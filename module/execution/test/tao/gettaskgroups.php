#!/usr/bin/env php
<?php

/**

title=测试 executionTao::getTaskGroups();
timeout=0
cid=16394

- 测试步骤1：查询包含任务的执行ID，返回任务分组结构 @1
- 测试步骤2：查询不存在任务的执行ID，应返回空数组 @0
- 测试步骤3：查询无效执行ID(0)，应返回空数组 @0
- 测试步骤4：查询负数执行ID，应返回空数组 @0
- 测试步骤5：测试方法正常调用，无异常 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('task')->gen(100);

su('admin');

$executionTest = new executionTaoTest();

r(count($executionTest->getTaskGroupsTest(103))) && p() && e('1'); // 测试步骤1：查询包含任务的执行ID，返回任务分组结构
r(count($executionTest->getTaskGroupsTest(999))) && p() && e('0'); // 测试步骤2：查询不存在任务的执行ID，应返回空数组
r(count($executionTest->getTaskGroupsTest(0))) && p() && e('0'); // 测试步骤3：查询无效执行ID(0)，应返回空数组
r(count($executionTest->getTaskGroupsTest(-1))) && p() && e('0'); // 测试步骤4：查询负数执行ID，应返回空数组
r(is_array($executionTest->getTaskGroupsTest(103))) && p() && e('1'); // 测试步骤5：测试方法正常调用，无异常