#!/usr/bin/env php
<?php

/**

title=测试 programplanZen::sortPlans();
timeout=0
cid=17795

- 执行programplanTest模块的sortPlansTest方法，参数是array  @0
- 执行programplanTest模块的sortPlansTest方法，参数是$singlePlan 第1条的id属性 @1
- 第一个应该是子阶段，因为它在原数组中排在前面 @2
- 父阶段在前，其子阶段紧随其后 @1,2

- 验证所有5个计划都被处理 @5

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/zen.class.php';

su('admin');

$programplanTest = new programplanZenTest();

// 测试步骤1：空数组排序
r($programplanTest->sortPlansTest(array())) && p() && e(0);

// 测试步骤2：单个计划排序
$singlePlan = array();
$plan = new stdClass();
$plan->id = 1;
$plan->parent = 0;
$singlePlan[1] = $plan;
r($programplanTest->sortPlansTest($singlePlan)) && p('1:id') && e('1');

// 测试步骤3：父子关系排序（子阶段在前，父阶段在后的情况）
$parentChildPlans = array();
$childPlan = new stdClass();
$childPlan->id = 2;
$childPlan->parent = 1;
$parentPlan = new stdClass();
$parentPlan->id = 1;
$parentPlan->parent = 0;
$parentChildPlans[2] = $childPlan;  // 子阶段先加入
$parentChildPlans[1] = $parentPlan; // 父阶段后加入
$result = $programplanTest->sortPlansTest($parentChildPlans);
$keys = array_keys($result);
r($keys[0]) && p() && e('2'); // 第一个应该是子阶段，因为它在原数组中排在前面

// 测试步骤4：父子关系排序（父阶段在前，子阶段在后的情况）
$parentFirstPlans = array();
$parentPlan = new stdClass();
$parentPlan->id = 1;
$parentPlan->parent = 0;
$childPlan = new stdClass();
$childPlan->id = 2;
$childPlan->parent = 1;
$parentFirstPlans[1] = $parentPlan; // 父阶段先加入
$parentFirstPlans[2] = $childPlan;  // 子阶段后加入
$result = $programplanTest->sortPlansTest($parentFirstPlans);
$keys = array_keys($result);
r(implode(',', $keys)) && p() && e('1,2'); // 父阶段在前，其子阶段紧随其后

// 测试步骤5：复杂多层次排序
$complexPlans = array();
$plans = array(
    array('id' => 4, 'parent' => 0), // 根节点2（先处理）
    array('id' => 1, 'parent' => 0), // 根节点1（后处理）
    array('id' => 2, 'parent' => 1), // 1的子节点
    array('id' => 5, 'parent' => 4), // 4的子节点
    array('id' => 3, 'parent' => 1), // 1的子节点
);
foreach($plans as $planData) {
    $plan = new stdClass();
    $plan->id = $planData['id'];
    $plan->parent = $planData['parent'];
    $complexPlans[$plan->id] = $plan;
}
$result = $programplanTest->sortPlansTest($complexPlans);
$keys = array_keys($result);
r(count($keys)) && p() && e('5'); // 验证所有5个计划都被处理