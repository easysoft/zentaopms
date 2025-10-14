#!/usr/bin/env php
<?php

/**

title=测试 docZen::previewPlanStory();
timeout=0
cid=0

- 步骤1：preview模式有效计划 @2
- 步骤2：preview模式无效计划 @0
- 步骤3：list模式有效ID列表 @3
- 步骤4：list模式空ID列表 @0
- 步骤5：无效视图模式 @0

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/doc.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$table = zenData('story');
$table->id->range('1-10');
$table->product->range('1');
$table->title->range('需求1,需求2,需求3,需求4,需求5');
$table->status->range('active');
$table->stage->range('planned,developing,testing');
$table->plan->range('1{3},2{2},3{3}');
$table->gen(10);

$planTable = zenData('productplan');
$planTable->id->range('1-5');
$planTable->product->range('1');
$planTable->title->range('计划1,计划2,计划3,计划4,计划5');
$planTable->status->range('doing');
$planTable->gen(5);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$docTest = new docTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
$result1 = $docTest->previewPlanStoryTest('setting', array('action' => 'preview', 'plan' => 1), '');
r(count($result1['data'])) && p() && e('2'); // 步骤1：preview模式有效计划

$result2 = $docTest->previewPlanStoryTest('setting', array('action' => 'preview', 'plan' => 0), '');
r(count($result2['data'])) && p() && e('0'); // 步骤2：preview模式无效计划

$result3 = $docTest->previewPlanStoryTest('list', array(), '1,2,3');
r(count($result3['data'])) && p() && e('3'); // 步骤3：list模式有效ID列表

$result4 = $docTest->previewPlanStoryTest('list', array(), '');
r(count($result4['data'])) && p() && e('0'); // 步骤4：list模式空ID列表

$result5 = $docTest->previewPlanStoryTest('invalid', array(), '');
r(count($result5['data'])) && p() && e('0'); // 步骤5：无效视图模式