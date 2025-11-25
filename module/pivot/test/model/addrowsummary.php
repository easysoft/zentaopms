#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::addRowSummary();
timeout=0
cid=17355

- 执行$result1['rows']['key1']['count'] @10
- 执行$result2['summary']['count']['value'] @$total$
- 执行$result3['rows']['single']['value'] @42
- 执行$result4['summary']['count']['value'] @$total$
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

// 步骤1：标量分组树处理，检查返回的行数据结构
$result1 = $pivotTest->addRowSummaryTest(array('key1', 'key2'), array('key1' => array('count' => 10), 'key2' => array('count' => 5)), array('count'));
r($result1['rows']['key1']['count']) && p() && e(10);

// 步骤2：检查汇总数据的总计标记
$result2 = $pivotTest->addRowSummaryTest(array('key1', 'key2'), array('key1' => array('count' => 10), 'key2' => array('count' => 5)), array('count'));
r($result2['summary']['count']['value']) && p() && e('$total$');

// 步骤3：单个数据值处理测试
$result3 = $pivotTest->addRowSummaryTest(array('single'), array('single' => array('value' => 42)), array('value'));
r($result3['rows']['single']['value']) && p() && e(42);

// 步骤4：空数据边界值测试，检查基本结构
$result4 = $pivotTest->addRowSummaryTest(array(), array(), array('count'));
r($result4['summary']['count']['value']) && p() && e('$total$');

// 步骤5：验证返回数据包含必要的键
$result5 = $pivotTest->addRowSummaryTest(array('test'), array('test' => array('num' => 15)), array('num'));
r(isset($result5['rows']) && isset($result5['summary'])) && p() && e(1);