#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshStoryCards();
timeout=0
cid=0

- 执行$result1 @1
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4 @1
- 执行$result5 @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备 - 使用最简化的数据准备
zenData('story')->gen(0);  // 清空现有数据

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：测试正常情况 - 传入有效的卡片对和执行ID
$cardPairs1 = array('backlog' => '', 'designing' => '');
$result1 = $kanbanTest->refreshStoryCardsTest($cardPairs1, 1, '');
r(is_array($result1)) && p() && e('1');

// 步骤2：测试边界情况 - 传入空的卡片对数组
$cardPairs2 = array();
$result2 = $kanbanTest->refreshStoryCardsTest($cardPairs2, 1, '');
r(is_array($result2)) && p() && e('1');

// 步骤3：测试无效执行ID - 传入不存在的执行ID
$cardPairs3 = array('backlog' => '');
$result3 = $kanbanTest->refreshStoryCardsTest($cardPairs3, 9999, '');
r(is_array($result3)) && p() && e('1');

// 步骤4：测试空字符串参数 - 传入空的其他卡片列表参数
$cardPairs4 = array('backlog' => '');
$result4 = $kanbanTest->refreshStoryCardsTest($cardPairs4, 1, '');
r(is_array($result4)) && p() && e('1');

// 步骤5：测试基本功能 - 验证方法不返回错误
$cardPairs5 = array('backlog' => '', 'designing' => '', 'developed' => '');
$result5 = $kanbanTest->refreshStoryCardsTest($cardPairs5, 1, '');
r(is_array($result5)) && p() && e('1');