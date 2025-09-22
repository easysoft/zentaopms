#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::processPivot();
timeout=0
cid=0

- 步骤1：正常对象输入 @1
- 步骤2：数组输入处理 @1
- 步骤3：空对象处理 @1
- 步骤4：空数组处理 @1
- 步骤5：数组模式业务逻辑 @1
- 步骤6：类型控制验证 @1
- 步骤7：JSON解析验证 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

$pivot = new pivotTest();

r($pivot->processPivotTest('single_object_normal')) && p('') && e('1');        // 步骤1：正常对象输入
r($pivot->processPivotTest('array_input_normal')) && p('') && e('1');          // 步骤2：数组输入处理
r($pivot->processPivotTest('empty_object')) && p('') && e('1');                // 步骤3：空对象处理
r($pivot->processPivotTest('empty_array')) && p('') && e('1');                 // 步骤4：空数组处理
r($pivot->processPivotTest('array_no_drill_processing')) && p('') && e('1');   // 步骤5：数组模式业务逻辑
r($pivot->processPivotTest('object_type_validation')) && p('') && e('1');      // 步骤6：类型控制验证
r($pivot->processPivotTest('settings_json_parsing')) && p('') && e('1');       // 步骤7：JSON解析验证