#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::mergePivotSpecData();
timeout=0
cid=0

- 步骤1：正常情况，单个对象处理属性id @1
- 步骤2：数组输入，检查第一个元素第0条的id属性 @1
- 步骤3：单个对象无对应pivotSpec数据属性name @不存在的透视表
- 步骤4：数组输入，无对应pivotSpec数据第0条的name属性 @不存在的透视表
- 步骤5：边界值，空数组测试 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
zenData('pivot')->gen(10);
zenData('pivotspec')->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTest();

// 准备测试数据 - 创建pivot对象
$singlePivot = new stdClass();
$singlePivot->id = 1;
$singlePivot->name = '原始透视表1';
$singlePivot->version = '1';

$multiplePivots = array();
$pivot1 = new stdClass();
$pivot1->id = 1;
$pivot1->name = '原始透视表1';
$pivot1->version = '1';

$pivot2 = new stdClass();
$pivot2->id = 2;
$pivot2->name = '原始透视表2';
$pivot2->version = '2';

$multiplePivots[] = $pivot1;
$multiplePivots[] = $pivot2;

$nonExistentPivot = new stdClass();
$nonExistentPivot->id = 999;
$nonExistentPivot->name = '不存在的透视表';
$nonExistentPivot->version = '1';

$emptyArray = array();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->mergePivotSpecDataTest($singlePivot, true)) && p('id') && e('1'); // 步骤1：正常情况，单个对象处理
r($pivotTest->mergePivotSpecDataTest($multiplePivots, false)) && p('0:id') && e('1'); // 步骤2：数组输入，检查第一个元素
r($pivotTest->mergePivotSpecDataTest($nonExistentPivot, true)) && p('name') && e('不存在的透视表'); // 步骤3：单个对象无对应pivotSpec数据
r($pivotTest->mergePivotSpecDataTest(array($nonExistentPivot), false)) && p('0:name') && e('不存在的透视表'); // 步骤4：数组输入，无对应pivotSpec数据  
r($pivotTest->mergePivotSpecDataTest($emptyArray, false)) && p() && e('0'); // 步骤5：边界值，空数组测试