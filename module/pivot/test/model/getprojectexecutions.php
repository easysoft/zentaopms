#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getProjectExecutions();
timeout=0
cid=17397

- 测试步骤1：正常情况下获取项目执行列表 @array
- 测试步骤2：multiple为1的执行项目格式化 @项目1/迭代1
- 测试步骤3：multiple为0的执行项目格式化 @项目2
- 测试步骤4：空数据库情况下的处理 @array
- 测试步骤5：验证返回数据的键值结构正确 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

su('admin');

$pivot = new pivotTest();

// 测试不同场景的getProjectExecutions方法
r($pivot->getProjectExecutionsTest('normal_case')) && p() && e('array');      // 测试步骤1：正常情况下获取项目执行列表
r($pivot->getProjectExecutionsTest('multiple_format')) && p() && e('项目1/迭代1'); // 测试步骤2：multiple为1的执行项目格式化
r($pivot->getProjectExecutionsTest('single_format')) && p() && e('项目2');     // 测试步骤3：multiple为0的执行项目格式化
r($pivot->getProjectExecutionsTest('empty_data')) && p() && e('array');       // 测试步骤4：空数据库情况下的处理
r($pivot->getProjectExecutionsTest('structure_test')) && p() && e('1');       // 测试步骤5：验证返回数据的键值结构正确