#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::getRDRegionActions();
timeout=0
cid=16940

- 测试步骤1：admin用户在单个区域时获取操作数量 @3
- 测试步骤2：admin用户在多个区域时获取操作数量 @4
- 测试步骤3：admin用户获取操作项的基本结构 @dropdown
- 测试步骤4：普通用户无权限时获取操作数量 @0
- 测试步骤5：测试边界值regionCount=0时的删除权限 @3
- 测试步骤6：测试无效参数时的处理 @3

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(5);
zenData('kanban')->gen(3);
zenData('kanbanregion')->gen(3);

$kanbanTest = new kanbanModelTest();

su('admin');
r(count($kanbanTest->getRDRegionActionsTest(1, 1, 1)[0]['items'])) && p() && e('3'); // 测试步骤1：admin用户在单个区域时获取操作数量
r(count($kanbanTest->getRDRegionActionsTest(1, 1, 2)[0]['items'])) && p() && e('4'); // 测试步骤2：admin用户在多个区域时获取操作数量
r($kanbanTest->getRDRegionActionsTest(1, 1, 1)[0]['type']) && p() && e('dropdown'); // 测试步骤3：admin用户获取操作项的基本结构

su('user1');
r(count($kanbanTest->getRDRegionActionsTest(1, 1, 1)[0]['items'])) && p() && e('0'); // 测试步骤4：普通用户无权限时获取操作数量

su('admin');
r(count($kanbanTest->getRDRegionActionsTest(1, 1, 0)[0]['items'])) && p() && e('3'); // 测试步骤5：测试边界值regionCount=0时的删除权限
r(count($kanbanTest->getRDRegionActionsTest(0, 0, 1)[0]['items'])) && p() && e('3'); // 测试步骤6：测试无效参数时的处理