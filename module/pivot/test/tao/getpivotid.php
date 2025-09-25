#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::getPivotID();
timeout=0
cid=0

- 步骤1：正常分组ID获取透视表 @1006
- 步骤2：边界值分组ID为0的情况 @0
- 步骤3：不存在的分组ID @0
- 步骤4：已发布状态的透视表 @1007
- 步骤5：未删除的透视表验证 @1008
- 步骤6：多个透视表的ID排序验证 @1004
- 步骤7：权限验证功能测试 @1005

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/pivot.unittest.class.php';

// zendata数据准备
$pivotTable = zenData('pivot');
$pivotTable->id->range('1001-1010');
$pivotTable->dimension->range('1-5');
$pivotTable->group->range('1,2,3,4,5,1,2,3,4,5');
$pivotTable->code->range('testcode{10}');
$pivotTable->name->range('测试透视表{10}');
$pivotTable->stage->range('published{8},draft{2}');
$pivotTable->deleted->range('0');
$pivotTable->createdBy->range('admin');
$pivotTable->editedBy->range('admin');
$pivotTable->gen(10);

$moduleTable = zenData('module');
$moduleTable->id->range('1-10');
$moduleTable->root->range('1-5');
$moduleTable->name->range('透视表分组{10}');
$moduleTable->type->range('pivot');
$moduleTable->deleted->range('0');
$moduleTable->gen(10);

// 用户登录
su('admin');

// 创建测试实例
$pivotTest = new pivotTest();

r($pivotTest->getPivotIDTest(1)) && p() && e('1006');      // 步骤1：正常分组ID获取透视表
r($pivotTest->getPivotIDTest(0)) && p() && e('0');         // 步骤2：边界值分组ID为0的情况
r($pivotTest->getPivotIDTest(999)) && p() && e('0');       // 步骤3：不存在的分组ID
r($pivotTest->getPivotIDTest(2)) && p() && e('1007');      // 步骤4：已发布状态的透视表
r($pivotTest->getPivotIDTest(3)) && p() && e('1008');      // 步骤5：未删除的透视表验证
r($pivotTest->getPivotIDTest(4)) && p() && e('1004');      // 步骤6：多个透视表的ID排序验证
r($pivotTest->getPivotIDTest(5)) && p() && e('1005');      // 步骤7：权限验证功能测试