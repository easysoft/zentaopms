#!/usr/bin/env php
<?php

/**

title=测试 bugZen::updateKanbanAfterCreate();
timeout=0
cid=0

- 步骤1：正常情况 @1
- 步骤2：空laneID @1
- 步骤3：空columnID @1
- 步骤4：无execution @1
- 步骤5：有from参数 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/bug.unittest.class.php';

// 2. zendata数据准备（简化版，只测试方法逻辑）

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$bugTest = new bugTest();

// 5. 🔴 强制要求：必须包含至少5个测试步骤
r($bugTest->updateKanbanAfterCreateTest((object)array('id' => 1, 'execution' => 1), 1, 1, '')) && p() && e('1'); // 步骤1：正常情况
r($bugTest->updateKanbanAfterCreateTest((object)array('id' => 2, 'execution' => 2), 0, 1, '')) && p() && e('1'); // 步骤2：空laneID
r($bugTest->updateKanbanAfterCreateTest((object)array('id' => 3, 'execution' => 3), 1, 0, '')) && p() && e('1'); // 步骤3：空columnID
r($bugTest->updateKanbanAfterCreateTest((object)array('id' => 4, 'execution' => 0), 1, 1, '')) && p() && e('1'); // 步骤4：无execution
r($bugTest->updateKanbanAfterCreateTest((object)array('id' => 5, 'execution' => 1), 1, 1, 'story')) && p() && e('1'); // 步骤5：有from参数