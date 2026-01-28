#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::getDrillCols();
timeout=0
cid=17378

- 步骤1：测试task对象类型，期望返回10个字段 @10
- 步骤2：测试case对象类型（转换为testcase），期望返回8个字段 @8
- 步骤3：测试product对象类型，期望返回10个字段 @10
- 步骤4：测试user对象类型，期望返回5个字段 @5
- 步骤5：测试bug对象类型，期望返回10个字段 @10

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

su('admin');

$pivotTest = new pivotModelTest();

r(count($pivotTest->getDrillCols('task'))) && p() && e('10'); // 步骤1：测试task对象类型，期望返回10个字段
r(count($pivotTest->getDrillCols('case'))) && p() && e('8'); // 步骤2：测试case对象类型（转换为testcase），期望返回8个字段  
r(count($pivotTest->getDrillCols('product'))) && p() && e('10'); // 步骤3：测试product对象类型，期望返回10个字段
r(count($pivotTest->getDrillCols('user'))) && p() && e('5'); // 步骤4：测试user对象类型，期望返回5个字段
r(count($pivotTest->getDrillCols('bug'))) && p() && e('10'); // 步骤5：测试bug对象类型，期望返回10个字段