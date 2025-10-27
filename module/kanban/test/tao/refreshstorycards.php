#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshStoryCards();
timeout=0
cid=0

- 步骤1：正常情况 @array
- 步骤2：空执行ID @array
- 步骤3：projected阶段需求
 - 属性backlog @
- 步骤4：designing阶段需求
 - 属性designing @
- 步骤5：多阶段处理
 - 属性designed @

*/

// 1. 导入依赖（路径固定，不可修改）
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. zendata数据准备（基础表数据）
zenData('story')->gen(0);
zenData('project')->gen(0);
zenData('execution')->gen(0);

// 3. 用户登录（选择合适角色）
su('admin');

// 4. 创建测试实例（变量名与模块名一致）
$kanbanTest = new kanbanTest();

// 5. 强制要求：必须包含至少5个测试步骤
r($kanbanTest->refreshStoryCardsTest(array('backlog' => '', 'designing' => '', 'developed' => ''), 1, '')) && p() && e('array'); // 步骤1：正常情况
r($kanbanTest->refreshStoryCardsTest(array(), 0, '')) && p() && e('array'); // 步骤2：空执行ID
r($kanbanTest->refreshStoryCardsTest(array('backlog' => '', 'designing' => ''), 1, '')) && p('backlog') && e(',2,1,'); // 步骤3：projected阶段需求
r($kanbanTest->refreshStoryCardsTest(array('backlog' => '', 'designing' => ''), 1, '')) && p('designing') && e(',3,'); // 步骤4：designing阶段需求
r($kanbanTest->refreshStoryCardsTest(array('backlog' => '', 'designing' => '', 'designed' => '', 'developing' => ''), 1, '')) && p('designed') && e(',4,'); // 步骤5：多阶段处理