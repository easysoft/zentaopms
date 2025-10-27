#!/usr/bin/env php
<?php

/**

title=测试 kanbanZen::moveCardByModal();
timeout=0
cid=0

- 步骤1：获取有效卡片的区域信息
 - 属性regions @1
 - 属性card @1
- 步骤2：测试移动不存在的卡片属性error @Card not found
- 步骤3：获取另一张卡片的信息
 - 属性regions @1
 - 属性card @1
 - 属性cardName @卡片2
- 步骤4：测试无效卡片ID属性error @Card not found
- 步骤5：测试第三张卡片的基本信息
 - 属性card @1
 - 属性kanban @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
$kanbanTable = zenData('kanban');
$kanbanTable->loadYaml('kanban_movecardbymodal', false, 2)->gen(3);

$kanbanCardTable = zenData('kanbancard');
$kanbanCardTable->loadYaml('kanbancard_movecardbymodal', false, 2)->gen(10);

$kanbanRegionTable = zenData('kanbanregion');
$kanbanRegionTable->loadYaml('kanbanregion_movecardbymodal', false, 2)->gen(3);

$kanbanLaneTable = zenData('kanbanlane');
$kanbanLaneTable->loadYaml('kanbanlane_movecardbymodal', false, 2)->gen(5);

$kanbanColumnTable = zenData('kanbancolumn');
$kanbanColumnTable->loadYaml('kanbancolumn_movecardbymodal', false, 2)->gen(10);

$kanbanCellTable = zenData('kanbancell');
$kanbanCellTable->loadYaml('kanbancell_movecardbymodal', false, 2)->gen(15);

$kanbanGroupTable = zenData('kanbangroup');
$kanbanGroupTable->loadYaml('kanbangroup_movecardbymodal', false, 2)->gen(3);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->moveCardByModalTest(1)) && p('regions,card') && e('1,1'); // 步骤1：获取有效卡片的区域信息
r($kanbanTest->moveCardByModalTest(999)) && p('error') && e('Card not found'); // 步骤2：测试移动不存在的卡片
r($kanbanTest->moveCardByModalTest(2)) && p('regions,card,cardName') && e('1,1,卡片2'); // 步骤3：获取另一张卡片的信息
r($kanbanTest->moveCardByModalTest(-1)) && p('error') && e('Card not found'); // 步骤4：测试无效卡片ID
r($kanbanTest->moveCardByModalTest(3)) && p('card,kanban') && e('1,1'); // 步骤5：测试第三张卡片的基本信息