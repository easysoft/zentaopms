#!/usr/bin/env php
<?php

/**

title=测试 searchTao::checkProjectPriv();
timeout=0
cid=0

- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList1, $userProjects1  @5
- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList2, $userProjects2  @3
- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList3, $userProjects3  @5
- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList4, $userProjects4  @3
- 执行searchTest模块的checkProjectPrivTest方法，参数是$results, $objectIdList5, $userProjects5  @4

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/search.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('project');
$table->id->range('1-10');
$table->name->range('项目A,项目B,项目C,项目D,项目E,项目F,项目G,项目H,项目I,项目J');
$table->code->range('projectA,projectB,projectC,projectD,projectE,projectF,projectG,projectH,projectI,projectJ');
$table->status->range('wait{3},doing{5},done{2}');
$table->deleted->range('0');
$table->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$searchTest = new searchTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤

// 准备测试数据 - 模拟搜索结果记录
$results = array();
for($i = 1; $i <= 5; $i++)
{
    $result = new stdClass();
    $result->id = $i;
    $result->objectID = $i;
    $result->objectType = 'project';
    $result->title = '项目' . chr(64 + $i);
    $results[$i] = $result;
}

// 测试步骤1：有权限用户访问自己项目的搜索结果
$objectIdList1 = array(1 => 1, 2 => 2, 3 => 3);
$userProjects1 = '1,2,3,4,5';
r($searchTest->checkProjectPrivTest($results, $objectIdList1, $userProjects1)) && p() && e(5);

// 测试步骤2：无权限用户访问不在权限范围内的项目
$objectIdList2 = array(4 => 4, 5 => 5);
$userProjects2 = '1,2,3';
r($searchTest->checkProjectPrivTest($results, $objectIdList2, $userProjects2)) && p() && e(3);

// 测试步骤3：空的对象ID列表输入
$objectIdList3 = array();
$userProjects3 = '1,2,3';
r($searchTest->checkProjectPrivTest($results, $objectIdList3, $userProjects3)) && p() && e(5);

// 测试步骤4：用户权限字符串为空时的权限检查
$objectIdList4 = array(1 => 1, 2 => 2);
$userProjects4 = '';
r($searchTest->checkProjectPrivTest($results, $objectIdList4, $userProjects4)) && p() && e(3);

// 测试步骤5：混合权限场景测试不同项目ID
$objectIdList5 = array(1 => 1, 3 => 3, 5 => 5);
$userProjects5 = '1,3,7,9';
r($searchTest->checkProjectPrivTest($results, $objectIdList5, $userProjects5)) && p() && e(4);