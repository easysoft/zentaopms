#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::addRowSummary();
timeout=0
cid=0

- 步骤1：标量值分组树情况，检查rows中的数据第rows条的key1:count属性 @10
- 步骤2：检查summary中的总计标识第summary条的count:value属性 @$total$
- 步骤3：嵌套分组树情况，检查嵌套结构中的数据第rows条的group1:rows:key1:count属性 @10
- 步骤4：单层分组情况，检查单个值处理第rows条的single:value属性 @42
- 步骤5：空分组树边界值，验证空数据处理第summary条的count:value属性 @$total$

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. 用户登录（选择合适角色）
su('admin');

// 3. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 4. 强制要求：必须包含至少5个测试步骤
r($pivotTest->addRowSummaryTest(array('key1', 'key2'), array('key1' => array('count' => 10), 'key2' => array('count' => 5)), array('count'))) && p('rows:key1:count') && e(10); // 步骤1：标量值分组树情况，检查rows中的数据
r($pivotTest->addRowSummaryTest(array('key1', 'key2'), array('key1' => array('count' => 10), 'key2' => array('count' => 5)), array('count'))) && p('summary:count:value') && e('$total$'); // 步骤2：检查summary中的总计标识
r($pivotTest->addRowSummaryTest(array('group1' => array('key1', 'key2')), array('key1' => array('count' => 10), 'key2' => array('count' => 5)), array('count', 'count'))) && p('rows:group1:rows:key1:count') && e(10); // 步骤3：嵌套分组树情况，检查嵌套结构中的数据
r($pivotTest->addRowSummaryTest(array('single'), array('single' => array('value' => 42)), array('value'))) && p('rows:single:value') && e(42); // 步骤4：单层分组情况，检查单个值处理
r($pivotTest->addRowSummaryTest(array(), array(), array('count'))) && p('summary:count:value') && e('$total$'); // 步骤5：空分组树边界值，验证空数据处理