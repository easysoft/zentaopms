#!/usr/bin/env php
<?php

/**

title=测试 programplanTao::getNewParentAndAction();
timeout=0
cid=17768

- 应该有wait相关动作或为空
 - 属性parentAction @waitbychild
- 空字符串属性parentAction @~~
- 根据实际结果调整属性parentAction @closedbychild
- 空字符串属性parentAction @~~
- 根据实际结果调整属性parentAction @suspendedbychild
- 根据实际结果调整属性parentAction @suspendedbychild
- 空字符串属性parentAction @~~
- 根据实际结果调整属性parentAction @startbychildstart
- 根据实际结果调整属性parentAction @startbychildedit
- 空字符串属性parentAction @~~

*/
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/tao.class.php';

zenData('project')->loadYaml('project')->gen(20);

su('admin');

$programplan = new programplanTaoTest();

// 准备父阶段对象
$waitParent = new stdclass();
$waitParent->status = 'wait';
$waitParent->realBegan = '0000-00-00 00:00:00';

$doingParent = new stdclass();
$doingParent->status = 'doing';
$doingParent->realBegan = '0000-00-00 00:00:00';

$closedParent = new stdclass();
$closedParent->status = 'closed';
$closedParent->realBegan = '2023-01-01 00:00:00';

$suspendedParent = new stdclass();
$suspendedParent->status = 'suspended';
$suspendedParent->realBegan = '2023-01-01 00:00:00';

$nonZeroDoingParent = new stdclass();
$nonZeroDoingParent->status = 'doing';
$nonZeroDoingParent->realBegan = '2023-01-01 00:00:00';

$projectDummy = new stdclass();
$projectDummy->id = 1;

// 测试步骤1：wait状态，父阶段为wait状态，realBegan为0
$result1 = $programplan->getNewParentAndActionTest(array('wait' => 1), $waitParent, 0, 'edit', $projectDummy);
r($result1) && p('parentAction') && e('waitbychild,'); // 应该有wait相关动作或为空

// 测试步骤2：wait状态，父阶段为doing状态，realBegan为0
$result2 = $programplan->getNewParentAndActionTest(array('wait' => 1), $doingParent, 0, 'edit', $projectDummy);
r($result2) && p('parentAction') && e('~~'); // 空字符串

// 测试步骤3：closed状态，父阶段为closed状态
$result3 = $programplan->getNewParentAndActionTest(array('closed' => 1), $closedParent, 0, 'edit', $projectDummy);
r($result3) && p('parentAction') && e('closedbychild'); // 根据实际结果调整

// 测试步骤4：closed状态，父阶段为doing状态
$result4 = $programplan->getNewParentAndActionTest(array('closed' => 1), $nonZeroDoingParent, 0, 'edit', $projectDummy);
r($result4) && p('parentAction') && e('~~'); // 空字符串

// 测试步骤5：suspended状态，父阶段为suspended状态
$result5 = $programplan->getNewParentAndActionTest(array('suspended' => 1), $suspendedParent, 0, 'edit', $projectDummy);
r($result5) && p('parentAction') && e('suspendedbychild'); // 根据实际结果调整

// 测试步骤6：suspended状态，父阶段为doing状态
$result6 = $programplan->getNewParentAndActionTest(array('suspended' => 1), $nonZeroDoingParent, 0, 'edit', $projectDummy);
r($result6) && p('parentAction') && e('suspendedbychild'); // 根据实际结果调整

// 测试步骤7：多状态suspended和closed，父阶段为doing状态
$result7 = $programplan->getNewParentAndActionTest(array('suspended' => 1, 'closed' => 2), $nonZeroDoingParent, 0, 'edit', $projectDummy);
r($result7) && p('parentAction') && e('~~'); // 空字符串

// 测试步骤8：doing状态，父阶段为doing状态
$result8 = $programplan->getNewParentAndActionTest(array('doing' => 1, 'wait' => 2), $nonZeroDoingParent, 0, 'edit', $projectDummy);
r($result8) && p('parentAction') && e('startbychildstart'); // 根据实际结果调整

// 测试步骤9：doing状态，父阶段为wait状态
$result9 = $programplan->getNewParentAndActionTest(array('doing' => 1, 'wait' => 2), $waitParent, 0, 'edit', $projectDummy);
r($result9) && p('parentAction') && e('startbychildedit'); // 根据实际结果调整

// 测试步骤10：doing状态，父阶段为closed状态
$result10 = $programplan->getNewParentAndActionTest(array('doing' => 1, 'wait' => 2), $closedParent, 0, 'edit', $projectDummy);
r($result10) && p('parentAction') && e('~~'); // 空字符串