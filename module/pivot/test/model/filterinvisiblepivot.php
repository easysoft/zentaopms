#!/usr/bin/env php
<?php

/**

title=测试 pivotModel::filterInvisiblePivot();
timeout=0
cid=17364

- 步骤1：空数组测试 @0
- 步骤2：包含可见pivot @1
- 步骤3：包含不可见pivot @0
- 步骤4：混合可见和不可见pivot @1
- 步骤5：全部不可见pivot @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备
$table = zenData('pivot');
$table->id->range('1-5');
$table->name->range('透视表1,透视表2,透视表3,透视表4,透视表5');
$table->stage->range('published{3},draft{2}');
$table->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 5. 准备测试数据
// 创建一些模拟的pivot对象
$pivot1 = new stdClass();
$pivot1->id = 1;
$pivot1->name = 'Test Pivot 1';

$pivot2 = new stdClass();
$pivot2->id = 2;  
$pivot2->name = 'Test Pivot 2';

$pivot3 = new stdClass();
$pivot3->id = 999;  // 不存在的ID
$pivot3->name = 'Test Pivot 3';

$pivot4 = new stdClass();
$pivot4->id = 1000; // 不存在的ID
$pivot4->name = 'Test Pivot 4';

// 6. 🔴 强制要求：必须包含至少5个测试步骤
r(count($pivotTest->filterInvisiblePivotTest(array(), array()))) && p() && e('0'); // 步骤1：空数组测试
r(count($pivotTest->filterInvisiblePivotTest(array($pivot1), array(1, 2)))) && p() && e('1'); // 步骤2：包含可见pivot
r(count($pivotTest->filterInvisiblePivotTest(array($pivot3), array(1, 2)))) && p() && e('0'); // 步骤3：包含不可见pivot
r(count($pivotTest->filterInvisiblePivotTest(array($pivot1, $pivot3), array(1, 2)))) && p() && e('1'); // 步骤4：混合可见和不可见pivot
r(count($pivotTest->filterInvisiblePivotTest(array($pivot3, $pivot4), array(1, 2)))) && p() && e('0'); // 步骤5：全部不可见pivot