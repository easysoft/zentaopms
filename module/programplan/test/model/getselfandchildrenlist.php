#!/usr/bin/env php
<?php

/**

title=测试 programplanModel::getSelfAndChildrenList();
timeout=0
cid=0

- 测试步骤1：单个有效ID获取自己及子阶段的状态属性status @doing
- 测试步骤2：单个有效ID获取子阶段数量验证 @3
- 测试步骤3：字符串ID参数的类型转换处理属性name @阶段a
- 测试步骤4：数组ID列表的多阶段查询功能
 -  @2
 - 属性1 @5
- 测试步骤5：不存在ID的边界情况处理（返回包含空数组的结构） @1
- 测试步骤6：空数组输入的异常处理机制 @0
- 测试步骤7：混合有效无效ID的数组处理 @2

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';
su('admin');

zenData('project')->loadYaml('getselfandchildrenlist')->gen(6);

$plan = new programplanModelTest();

$result2 = $plan->getSelfAndChildrenListTest(2);
$result2Count = count($result2[2]);
$resultStr2 = $plan->getSelfAndChildrenListTest('2');
$resultArray = $plan->getSelfAndChildrenListTest(array(2, 5));
$resultNotExist = $plan->getSelfAndChildrenListTest(999);
$resultEmpty = $plan->getSelfAndChildrenListTest(array());
$resultMixed = $plan->getSelfAndChildrenListTest(array(2, 999));

r($result2[2][2]) && p('status') && e('doing');                    // 测试步骤1：单个有效ID获取自己及子阶段的状态
r($result2Count - 1) && p('') && e(3);                             // 测试步骤2：单个有效ID获取子阶段数量验证
r($resultStr2[2][2]) && p('name') && e('阶段a');                   // 测试步骤3：字符串ID参数的类型转换处理
r(array_keys($resultArray)) && p('0,1') && e('2,5');               // 测试步骤4：数组ID列表的多阶段查询功能
r(count($resultNotExist)) && p('') && e(1);                        // 测试步骤5：不存在ID的边界情况处理（返回包含空数组的结构）
r(count($resultEmpty)) && p('') && e(0);                           // 测试步骤6：空数组输入的异常处理机制
r(array_keys($resultMixed)) && p('0') && e('2');                   // 测试步骤7：混合有效无效ID的数组处理