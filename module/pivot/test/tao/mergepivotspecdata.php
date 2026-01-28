#!/usr/bin/env php
<?php

/**

title=测试 pivotTao::mergePivotSpecData();
timeout=0
cid=17450

- 步骤1：正常情况，单个对象处理，验证名称被合并属性name @合并后透视表1
- 步骤2：数组输入，检查第一个元素合并后名称第0条的name属性 @合并后透视表1
- 步骤3：单个对象无对应pivotSpec数据，保持原始名称属性name @不存在的透视表
- 步骤4：数组输入，无对应pivotSpec数据，保持原始名称第0条的name属性 @不存在的透视表
- 步骤5：边界值，空数组测试，返回空数组长度为0 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

// 2. 直接准备测试数据
global $tester;
$dao = $tester->dao;

// 清理测试数据
$dao->delete()->from(TABLE_PIVOT)->where('id')->le(5)->exec();
$dao->delete()->from(TABLE_PIVOTSPEC)->where('pivot')->le(5)->exec();

// 插入pivot测试数据
$dao->insert(TABLE_PIVOT)->data(array(
    'id' => 1,
    'name' => '原始透视表1',
    'version' => '1.0',
    'deleted' => '0'
))->exec();

$dao->insert(TABLE_PIVOT)->data(array(
    'id' => 2,
    'name' => '原始透视表2',
    'version' => '2.0',
    'deleted' => '0'
))->exec();

// 插入pivotspec测试数据
$dao->insert(TABLE_PIVOTSPEC)->data(array(
    'pivot' => 1,
    'version' => '1.0',
    'name' => '合并后透视表1',
    'desc' => '合并后描述1'
))->exec();

$dao->insert(TABLE_PIVOTSPEC)->data(array(
    'pivot' => 2,
    'version' => '2.0',
    'name' => '合并后透视表2',
    'desc' => '合并后描述2'
))->exec();

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$pivotTest = new pivotTaoTest();

// 准备测试数据 - 创建pivot对象
$singlePivot = new stdClass();
$singlePivot->id = 1;
$singlePivot->name = '原始透视表1';
$singlePivot->version = '1.0';

$multiplePivots = array();
$pivot1 = new stdClass();
$pivot1->id = 1;
$pivot1->name = '原始透视表1';
$pivot1->version = '1.0';

$pivot2 = new stdClass();
$pivot2->id = 2;
$pivot2->name = '原始透视表2';
$pivot2->version = '2.0';

$multiplePivots[] = $pivot1;
$multiplePivots[] = $pivot2;

$nonExistentPivot = new stdClass();
$nonExistentPivot->id = 999;
$nonExistentPivot->name = '不存在的透视表';
$nonExistentPivot->version = '1.0';

$emptyArray = array();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($pivotTest->mergePivotSpecDataTest($singlePivot, true)) && p('name') && e('合并后透视表1'); // 步骤1：正常情况，单个对象处理，验证名称被合并
r($pivotTest->mergePivotSpecDataTest($multiplePivots, false)) && p('0:name') && e('合并后透视表1'); // 步骤2：数组输入，检查第一个元素合并后名称
r($pivotTest->mergePivotSpecDataTest($nonExistentPivot, true)) && p('name') && e('不存在的透视表'); // 步骤3：单个对象无对应pivotSpec数据，保持原始名称
r($pivotTest->mergePivotSpecDataTest(array($nonExistentPivot), false)) && p('0:name') && e('不存在的透视表'); // 步骤4：数组输入，无对应pivotSpec数据，保持原始名称
r($pivotTest->mergePivotSpecDataTest($emptyArray, false)) && p() && e('0'); // 步骤5：边界值，空数组测试，返回空数组长度为0