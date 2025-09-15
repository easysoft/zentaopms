#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewPlanBug();
timeout=0
cid=0

- 步骤1：正常情况测试setting视图，检查返回数组 @1
- 步骤2：list视图模式，检查data键存在 @1
- 步骤3：测试计划2的Bug数量 @3
- 步骤4：测试空计划ID情况，数据为空 @0
- 步骤5：测试其他视图类型处理，数据为空 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$bug = zenData('bug');
$bug->id->range('1-20');
$bug->title->range('Bug1,Bug2,Bug3,计划Bug4,计划Bug5,Plan Bug6,计划缺陷7,Bug Test8,计划问题9,Bug Issue10{10}');
$bug->plan->range('1,1,1,2,2,2,3,3,3,0{10}');
$bug->product->range('1{20}');
$bug->status->range('active{15},resolved{3},closed{2}');
$bug->pri->range('1{5},2{5},3{5},4{5}');
$bug->severity->range('1{5},2{5},3{5},4{5}');
$bug->type->range('codeerror{10},designdefect{5},others{5}');
$bug->openedBy->range('admin{20}');
$bug->assignedTo->range('admin{10},user1{5},user2{5}');
$bug->deleted->range('0{20}');
$bug->gen(20);

$productplan = zenData('productplan');
$productplan->id->range('1-10');
$productplan->product->range('1{10}');
$productplan->title->range('计划1,计划2,计划3,计划4,计划5,Plan6,Plan7,Plan8,Plan9,Plan10');
$productplan->status->range('wait{3},doing{4},done{3}');
$productplan->gen(10);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 强制要求：必须包含至少5个测试步骤
$result1 = $docTest->previewPlanBugTest('setting', array('action' => 'preview', 'plan' => 1), '');
r(is_array($result1)) && p() && e('1'); // 步骤1：正常情况测试setting视图，检查返回数组
$result2 = $docTest->previewPlanBugTest('list', array('action' => 'list'), '1,2,3');
r(isset($result2['data'])) && p() && e('1'); // 步骤2：list视图模式，检查data键存在
$result3 = $docTest->previewPlanBugTest('setting', array('action' => 'preview', 'plan' => 2), '');
r(count($result3['data'])) && p() && e('3'); // 步骤3：测试计划2的Bug数量
$result4 = $docTest->previewPlanBugTest('setting', array('action' => 'preview', 'plan' => 0), '');
r(count($result4['data'])) && p() && e('0'); // 步骤4：测试空计划ID情况，数据为空
$result5 = $docTest->previewPlanBugTest('other', array('action' => 'other'), '');
r(count($result5['data'])) && p() && e('0'); // 步骤5：测试其他视图类型处理，数据为空