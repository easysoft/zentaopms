#!/usr/bin/env php
<?php

/**

title=测试 executionZen::checkLinkPlan();
timeout=0
cid=16421

- 测试步骤1:没有新计划关联 @0
- 测试步骤2:有新计划关联 @保存成功
- 测试步骤3:有多个新计划关联 @保存成功
- 测试步骤4:oldPlans为空,POST中有plans @保存成功
- 测试步骤5:oldPlans有值,POST中plans为空 @0
- 测试步骤6:oldPlans和POST plans都为空 @0
- 测试步骤7:POST plans中包含空值和0值 @保存成功

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/executionzen.unittest.class.php';

$execution = zenData('project');
$execution->id->range('1');
$execution->name->range('迭代1');
$execution->type->range('sprint');
$execution->project->range('0');
$execution->status->range('doing');
$execution->multiple->range('1');
$execution->deleted->range('0');
$execution->gen(1);

su('admin');

$executionTest = new executionZenTest();

r($executionTest->checkLinkPlanTest(1, array(1, 2), array(array(1, 2)))) && p() && e('0'); // 测试步骤1:没有新计划关联
r($executionTest->checkLinkPlanTest(1, array(1, 2), array(array(1, 2, 3)))) && p() && e('保存成功'); // 测试步骤2:有新计划关联
r($executionTest->checkLinkPlanTest(1, array(1, 2), array(array(1, 2, 4, 5)))) && p() && e('保存成功'); // 测试步骤3:有多个新计划关联
r($executionTest->checkLinkPlanTest(1, array(), array(array(1, 2)))) && p() && e('保存成功'); // 测试步骤4:oldPlans为空,POST中有plans
r($executionTest->checkLinkPlanTest(1, array(1, 2), array())) && p() && e('0'); // 测试步骤5:oldPlans有值,POST中plans为空
r($executionTest->checkLinkPlanTest(1, array(), array())) && p() && e('0'); // 测试步骤6:oldPlans和POST plans都为空
r($executionTest->checkLinkPlanTest(1, array(1), array(array(1, 2, '', 0, 3)))) && p() && e('保存成功'); // 测试步骤7:POST plans中包含空值和0值
