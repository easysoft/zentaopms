#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshStoryCards();
timeout=0
cid=0

- 执行$result1 @1
- 执行$result2 @1
- 执行$result3 @1
- 执行$result4['backlog'], ',1,') !== false @1
- 执行$result5['designing'], ',3,') !== false @1

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备 - 最简化配置
zenData('story')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 强制要求：必须包含至少5个测试步骤

// 步骤1：测试正常情况 - 传入有效的卡片对和执行ID
$cardPairs1 = array('backlog' => '', 'designing' => '', 'developed' => '');
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

// 步骤4：测试projected阶段需求分类到backlog - 验证projected阶段需求会被分类到backlog
$cardPairs4 = array('backlog' => '', 'designing' => '', 'developed' => '');
$result4 = $kanbanTest->refreshStoryCardsTest($cardPairs4, 1, '');
r(strpos($result4['backlog'], ',1,') !== false) && p() && e('1');

// 步骤5：测试designing阶段需求分类 - 验证designing阶段需求会被分类到designing列
$cardPairs5 = array('backlog' => '', 'designing' => '', 'designed' => '', 'developing' => '', 'developed' => '');
$result5 = $kanbanTest->refreshStoryCardsTest($cardPairs5, 1, '');
r(strpos($result5['designing'], ',3,') !== false) && p() && e('1');