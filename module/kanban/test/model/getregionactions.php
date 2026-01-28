#!/usr/bin/env php
<?php

/**

title=测试 kanbanModel::getRegionActions();
timeout=0
cid=16941

- 步骤1：管理员用户多区域情况 @8
- 步骤2：单区域情况下操作按钮数量（无删除按钮） @7
- 步骤3：普通用户权限受限情况 @1
- 步骤4：边界值regionCount=0 @7
- 步骤5：字符串类型regionID处理 @8
- 步骤6：验证操作按钮基本结构类型 @dropdown
- 步骤7：验证操作按钮基本结构图标 @ellipsis-v

*/

include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/model.class.php';

zenData('user')->gen(10);
zenData('kanban')->gen(5);
zenData('kanbanregion')->gen(10);

global $tester;
$tester->loadModel('kanban');

su('admin');
$actions1 = $tester->kanban->getRegionActions(1, 1, 3);
$actions2 = $tester->kanban->getRegionActions(1, 1, 1);
$actions3 = $tester->kanban->getRegionActions(1, 2, 2);

su('user1');
$actions4 = $tester->kanban->getRegionActions(1, 1, 2);

su('admin');
$actions5 = $tester->kanban->getRegionActions(1, 1, 0);
$actions6 = $tester->kanban->getRegionActions(1, '2', 3);

r(count($actions1[0]['items'])) && p() && e('8'); // 步骤1：管理员用户多区域情况
r(count($actions2[0]['items'])) && p() && e('7'); // 步骤2：单区域情况下操作按钮数量（无删除按钮）
r(count($actions4[0]['items'])) && p() && e('1'); // 步骤3：普通用户权限受限情况
r(count($actions5[0]['items'])) && p() && e('7'); // 步骤4：边界值regionCount=0
r(count($actions6[0]['items'])) && p() && e('8'); // 步骤5：字符串类型regionID处理
r($actions3[0]['type']) && p() && e('dropdown'); // 步骤6：验证操作按钮基本结构类型
r($actions3[0]['icon']) && p() && e('ellipsis-v'); // 步骤7：验证操作按钮基本结构图标