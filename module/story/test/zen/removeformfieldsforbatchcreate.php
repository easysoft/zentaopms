#!/usr/bin/env php
<?php

/**

title=测试 storyZen::removeFormFieldsForBatchCreate();
timeout=0
cid=18702

- 步骤1:hiddenPlan为true时,移除plan字段 @1
- 步骤2:executionType为scrum时,移除region和lane字段 @1
- 步骤3:executionType为kanban时,保留region和lane字段 @1
- 步骤4:project tab下隐藏parent字段 @1
- 步骤5:组合测试 @1

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/storyzen.unittest.class.php';

zenData('project')->gen(20);
zenData('team')->gen(50);
zenData('user')->gen(50);

su('admin');

$storyTest = new storyZenTest();

// 准备测试字段数组
$fields = array(
    'plan'   => array('name' => 'plan', 'type' => 'select'),
    'region' => array('name' => 'region', 'type' => 'select'),
    'lane'   => array('name' => 'lane', 'type' => 'select'),
    'parent' => array('name' => 'parent', 'type' => 'select', 'hidden' => false),
    'title'  => array('name' => 'title', 'type' => 'text'),
    'reviewer' => array('name' => 'reviewer', 'type' => 'select', 'options' => array()),
    'assignedTo' => array('name' => 'assignedTo', 'type' => 'select', 'options' => array()),
);

r(!isset($storyTest->removeFormFieldsForBatchCreateTest($fields, true, 'scrum', 0, '')['plan'])) && p() && e('1'); // 步骤1:hiddenPlan为true时,移除plan字段
r(!isset($storyTest->removeFormFieldsForBatchCreateTest($fields, false, 'scrum', 0, '')['region']) && !isset($storyTest->removeFormFieldsForBatchCreateTest($fields, false, 'scrum', 0, '')['lane'])) && p() && e('1'); // 步骤2:executionType为scrum时,移除region和lane字段
r(isset($storyTest->removeFormFieldsForBatchCreateTest($fields, false, 'kanban', 0, '')['region']) && isset($storyTest->removeFormFieldsForBatchCreateTest($fields, false, 'kanban', 0, '')['lane'])) && p() && e('1'); // 步骤3:executionType为kanban时,保留region和lane字段
r($storyTest->removeFormFieldsForBatchCreateTest($fields, false, 'scrum', 11, 'project')['parent']['hidden']) && p() && e('1'); // 步骤4:project tab下隐藏parent字段
r(!isset($storyTest->removeFormFieldsForBatchCreateTest($fields, true, 'scrum', 11, 'project')['plan']) && !isset($storyTest->removeFormFieldsForBatchCreateTest($fields, true, 'scrum', 11, 'project')['region']) && $storyTest->removeFormFieldsForBatchCreateTest($fields, true, 'scrum', 11, 'project')['parent']['hidden']) && p() && e('1'); // 步骤5:组合测试