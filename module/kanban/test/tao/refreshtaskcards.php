#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshTaskCards();
timeout=0
cid=16992

- 步骤1：正常情况，空的卡片对和执行ID属性wait @0
- 步骤2：测试不存在的执行ID属性wait @~~
- 步骤3：测试空数组参数 @~~
- 步骤4：测试空执行ID属性developing @~~
- 步骤5：测试空的其他卡片列表属性pause @N/A

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（根据需要配置）
// 创建简单的测试数据，避免复杂依赖

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($kanbanTest->refreshTaskCardsTest(array('wait' => '', 'developing' => '', 'developed' => '', 'pause' => '', 'canceled' => '', 'closed' => ''), 0, '')) && p('wait') && e('0'); // 步骤1：正常情况，空的卡片对和执行ID
r($kanbanTest->refreshTaskCardsTest(array('wait' => '', 'developing' => '', 'developed' => '', 'pause' => '', 'canceled' => '', 'closed' => ''), 999, '')) && p('wait') && e('~~'); // 步骤2：测试不存在的执行ID
r($kanbanTest->refreshTaskCardsTest(array(), 0, '')) && p() && e('~~'); // 步骤3：测试空数组参数
r($kanbanTest->refreshTaskCardsTest(array('wait' => '', 'developing' => '', 'developed' => '', 'pause' => '', 'canceled' => '', 'closed' => ''), 0, '')) && p('developing') && e('~~'); // 步骤4：测试空执行ID
r($kanbanTest->refreshTaskCardsTest(array('wait' => '', 'developing' => '', 'developed' => '', 'pause' => '', 'canceled' => '', 'closed' => ''), 0, '')) && p('pause') && e('N/A'); // 步骤5：测试空的其他卡片列表