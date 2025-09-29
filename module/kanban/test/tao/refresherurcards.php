#!/usr/bin/env php
<?php

/**

title=测试 kanbanTao::refreshERURCards();
timeout=0
cid=0

- 步骤1：正常story类型的卡片刷新 @Array
- 步骤2：parentStory类型的卡片处理 @Array
- 步骤3：epic类型的卡片处理 @Array
- 步骤4：空卡片对的处理情况 @Array
- 步骤5：需求阶段变更的处理 @Array

*/

// 1. 导入依赖
include dirname(__FILE__, 5) . '/test/lib/init.php';
include dirname(__FILE__, 2) . '/lib/kanban.unittest.class.php';

// 2. 用户登录
su('admin');

// 3. 创建测试实例
$kanbanTest = new kanbanTest();

// 4. 测试步骤
r($kanbanTest->refreshERURCardsTest(array('wait' => ',1,2,', 'planned' => ',3,'), 101, '1,2,3', 'story')) && p() && e('Array'); // 步骤1：正常story类型的卡片刷新
r($kanbanTest->refreshERURCardsTest(array('wait' => ',9,10,'), 101, '9,10', 'parentStory')) && p() && e('Array'); // 步骤2：parentStory类型的卡片处理
r($kanbanTest->refreshERURCardsTest(array('wait' => ',6,7,8,'), 101, '6,7,8', 'epic')) && p() && e('Array'); // 步骤3：epic类型的卡片处理
r($kanbanTest->refreshERURCardsTest(array(), 101, '', 'story')) && p() && e('Array'); // 步骤4：空卡片对的处理情况
r($kanbanTest->refreshERURCardsTest(array('wait' => ',1,', 'planned' => ''), 101, '1', 'story')) && p() && e('Array'); // 步骤5：需求阶段变更的处理