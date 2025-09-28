#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::addRowSummary();
timeout=0
cid=0

- 执行$result1['rows']['key1']['count'] @10
- 执行$result2['summary']['count']['value'] @$total$
- 执行$result3['rows']['single']['value'] @42
- 执行$result4['summary'] @1
- 执行$result5['rows']) && isset($result5['summary'] @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 强制要求：必须包含至少5个测试步骤

// 步骤1：标量值分组树情况，检查rows中key1的count值
$result1 = $pivotTest->addRowSummaryTest(array('key1', 'key2'), array('key1' => array('count' => 10), 'key2' => array('count' => 5)), array('count'));
r($result1['rows']['key1']['count']) && p() && e(10);

// 步骤2：检查summary中count的value值
$result2 = $pivotTest->addRowSummaryTest(array('key1', 'key2'), array('key1' => array('count' => 10), 'key2' => array('count' => 5)), array('count'));
r($result2['summary']['count']['value']) && p() && e('$total$');

// 步骤3：单层分组情况，检查single的value值
$result3 = $pivotTest->addRowSummaryTest(array('single'), array('single' => array('value' => 42)), array('value'));
r($result3['rows']['single']['value']) && p() && e(42);

// 步骤4：空分组树边界值，检查summary存在
$result4 = $pivotTest->addRowSummaryTest(array(), array(), array('count'));
r(isset($result4['summary'])) && p() && e(1);

// 步骤5：验证返回数据结构正确性
$result5 = $pivotTest->addRowSummaryTest(array('key1'), array('key1' => array('count' => 15)), array('count'));
r(isset($result5['rows']) && isset($result5['summary'])) && p() && e(1);